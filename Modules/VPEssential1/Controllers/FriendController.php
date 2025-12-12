<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Friend;
use Modules\VPEssential1\Models\SocialSetting;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Send friend request
     */
    public function sendRequest($identifier)
    {
        if (!SocialSetting::isFeatureEnabled('friends')) {
            abort(404);
        }
        
        // Find user by username or ID
        $user = \App\Models\User::where('username', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();
        $userId = $user->id;
        
        if ($userId == Auth::id()) {
            return redirect()->back()->with('error', 'You cannot send a friend request to yourself.');
        }
        
        // Check if request already exists
        $existing = Friend::where('user_id', Auth::id())
            ->where('friend_id', $userId)
            ->orWhere(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('friend_id', Auth::id());
            })
            ->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'Friend request already exists.');
        }
        
        Friend::create([
            'user_id' => Auth::id(),
            'friend_id' => $userId,
            'status' => 'pending',
        ]);
        
        // Create notification
        \Modules\VPEssential1\Services\NotificationService::create([
            'user_id' => $userId,
            'from_user_id' => Auth::id(),
            'type' => 'friend_request',
            'title' => 'New friend request',
            'message' => Auth::user()->name . ' sent you a friend request',
            'link' => route('social.friends.requests'),
        ]);
        
        return redirect()->back()->with('success', 'Friend request sent!');
    }
    
    /**
     * Accept friend request
     */
    public function acceptRequest(Friend $friend)
    {
        if ($friend->friend_id !== Auth::id()) {
            abort(403);
        }
        
        $friend->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
        
        // Create notification
        \Modules\VPEssential1\Services\NotificationService::create([
            'user_id' => $friend->user_id,
            'from_user_id' => Auth::id(),
            'type' => 'friend_accepted',
            'title' => 'Friend request accepted',
            'message' => Auth::user()->name . ' accepted your friend request',
            'link' => route('social.profile.user', Auth::id()),
        ]);
        
        return redirect()->back()->with('success', 'Friend request accepted!');
    }
    
    /**
     * Reject friend request
     */
    public function rejectRequest(Friend $friend)
    {
        if ($friend->friend_id !== Auth::id()) {
            abort(403);
        }
        
        $friend->delete();
        
        return redirect()->back()->with('success', 'Friend request rejected.');
    }
    
    /**
     * Remove friend
     */
    public function remove($identifier)
    {
        // Find user by username or ID
        $user = \App\Models\User::where('username', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();
        $userId = $user->id;
        
        Friend::where(function($query) use ($userId) {
            $query->where('user_id', Auth::id())
                  ->where('friend_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('friend_id', Auth::id());
        })->delete();
        
        return redirect()->back()->with('success', 'Friend removed.');
    }
    
    /**
     * List friends
     */
    public function index()
    {
        $friends = Friend::where(function($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('friend_id', Auth::id());
        })
        ->where('status', 'accepted')
        ->with(['user', 'friend'])
        ->get()
        ->map(function($friend) {
            return $friend->user_id === Auth::id() ? $friend->friend : $friend->user;
        });
        
        return view('vpessential1::friends.index', compact('friends'));
    }
    
    /**
     * List friend requests
     */
    public function requests()
    {
        $requests = Friend::where('friend_id', Auth::id())
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();
        
        return view('vpessential1::friends.requests', compact('requests'));
    }
}
