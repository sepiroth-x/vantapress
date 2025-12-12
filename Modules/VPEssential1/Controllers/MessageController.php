<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Conversation;
use Modules\VPEssential1\Models\ConversationParticipant;
use Modules\VPEssential1\Models\Message;
use Modules\VPEssential1\Models\SocialSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display all conversations
     */
    public function index()
    {
        if (!SocialSetting::isFeatureEnabled('messaging')) {
            abort(404);
        }
        
        $conversations = ConversationParticipant::where('user_id', Auth::id())
            ->with(['conversation.lastMessage', 'conversation.participants.user'])
            ->latest('updated_at')
            ->get()
            ->map(function($participant) {
                return $participant->conversation;
            });
        
        return view('vpessential1::messages.index', compact('conversations'));
    }
    
    /**
     * Show conversation
     */
    public function show($identifier)
    {
        // Try to find conversation by ID first
        $conversation = Conversation::find($identifier);
        
        // If not found, try to find by username (create or find existing conversation)
        if (!$conversation) {
            $user = \App\Models\User::where('username', $identifier)
                ->orWhere('id', $identifier)
                ->firstOrFail();
            
            // Find existing conversation with this user
            $conversationId = DB::table('vp_conversation_participants as cp1')
                ->join('vp_conversation_participants as cp2', 'cp1.conversation_id', '=', 'cp2.conversation_id')
                ->join('vp_conversations as c', 'c.id', '=', 'cp1.conversation_id')
                ->where('cp1.user_id', Auth::id())
                ->where('cp2.user_id', $user->id)
                ->where('c.type', 'private')
                ->value('c.id');
            
            if ($conversationId) {
                $conversation = Conversation::findOrFail($conversationId);
            } else {
                // Create new conversation
                $conversation = Conversation::create(['type' => 'private']);
                
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => Auth::id(),
                ]);
                
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                ]);
            }
        }
        
        // Check if user is participant
        if (!$conversation->participants()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        
        $messages = $conversation->messages()
            ->with('user')
            ->oldest()
            ->get();
        
        // Mark messages as read
        $participant = $conversation->participants()->where('user_id', Auth::id())->first();
        $participant->update(['last_read_at' => now()]);
        
        return view('vpessential1::messages.show', compact('conversation', 'messages'));
    }
    
    /**
     * Create new conversation
     */
    public function create($identifier)
    {
        // Find user by username or ID
        $user = \App\Models\User::where('username', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();
        $userId = $user->id;
        
        // Check if conversation already exists
        $existing = DB::table('vp_conversation_participants as cp1')
            ->join('vp_conversation_participants as cp2', 'cp1.conversation_id', '=', 'cp2.conversation_id')
            ->join('vp_conversations as c', 'c.id', '=', 'cp1.conversation_id')
            ->where('cp1.user_id', Auth::id())
            ->where('cp2.user_id', $userId)
            ->where('c.type', 'private')
            ->value('c.id');
        
        if ($existing) {
            return redirect()->route('social.messages.show', $existing);
        }
        
        // Create new conversation
        $conversation = Conversation::create(['type' => 'private']);
        
        // Add participants
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
        ]);
        
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
        ]);
        
        return redirect()->route('social.messages.show', $conversation->id);
    }
    
    /**
     * Send message
     */
    public function send(Request $request, $identifier)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ]);
        
        // Find conversation
        $conversation = Conversation::findOrFail($identifier);
        
        // Check if user is participant
        if (!$conversation->participants()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }
        
        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachmentFiles = [];
            foreach ($request->file('attachments') as $file) {
                $attachmentFiles[] = $file->store('messages', 'public');
            }
            $validated['attachments'] = $attachmentFiles;
        }
        
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'attachments' => $validated['attachments'] ?? null,
        ]);
        
        // Update conversation timestamp
        $conversation->touch();
        
        // Create notifications for other participants
        $participants = $conversation->participants()
            ->where('user_id', '!=', Auth::id())
            ->get();
        
        foreach ($participants as $participant) {
            \Modules\VPEssential1\Services\NotificationService::create([
                'user_id' => $participant->user_id,
                'from_user_id' => Auth::id(),
                'type' => 'message',
                'notifiable_id' => $message->id,
                'notifiable_type' => Message::class,
                'title' => 'New message',
                'message' => Auth::user()->name . ' sent you a message',
                'link' => route('social.messages.show', $conversation->id),
            ]);
        }
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at->toIso8601String(),
                ],
            ]);
        }
        
        return redirect()->back()->with('success', 'Message sent!');
    }
}
