<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Comment;
use Modules\VPEssential1\Models\SocialSetting;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store new comment
     */
    public function store(Request $request)
    {
        if (!SocialSetting::isFeatureEnabled('comments')) {
            abort(404);
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'parent_id' => 'nullable|exists:vp_comments,id',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        $comment = Comment::create($validated);
        
        // Update parent replies count
        if ($comment->parent_id) {
            $comment->parent()->increment('replies_count');
        }
        
        // Update commentable comments count
        $commentable = $comment->commentable;
        if ($commentable && property_exists($commentable, 'comments_count')) {
            $commentable->increment('comments_count');
        }
        
        // Create notification
        $notifiableUser = $comment->parent_id 
            ? $comment->parent->user_id 
            : $commentable->user_id;
        
        if ($notifiableUser !== Auth::id()) {
            \Modules\VPEssential1\Services\NotificationService::create([
                'user_id' => $notifiableUser,
                'from_user_id' => Auth::id(),
                'type' => $comment->parent_id ? 'comment_reply' : 'post_comment',
                'notifiable_id' => $comment->id,
                'notifiable_type' => Comment::class,
                'title' => $comment->parent_id ? 'New reply' : 'New comment',
                'message' => Auth::user()->name . ' commented on your ' . class_basename($commentable),
                'link' => '#comment-' . $comment->id,
            ]);
        }
        
        return redirect()->back()->with('success', 'Comment posted!');
    }
    
    /**
     * Delete comment
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $comment->delete();
        
        return redirect()->back()->with('success', 'Comment deleted!');
    }
}
