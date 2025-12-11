<?php

namespace Modules\VPEssential1\Services;

use Modules\VPEssential1\Models\Friend;
use Modules\VPEssential1\Models\Follower;
use App\Models\User;

class SocialService
{
    /**
     * Get mutual friends between two users
     */
    public static function getMutualFriends(int $userId1, int $userId2)
    {
        $user1Friends = Friend::where(function($query) use ($userId1) {
            $query->where('user_id', $userId1)
                  ->orWhere('friend_id', $userId1);
        })
        ->where('status', 'accepted')
        ->get()
        ->map(function($friend) use ($userId1) {
            return $friend->user_id === $userId1 ? $friend->friend_id : $friend->user_id;
        });
        
        $user2Friends = Friend::where(function($query) use ($userId2) {
            $query->where('user_id', $userId2)
                  ->orWhere('friend_id', $userId2);
        })
        ->where('status', 'accepted')
        ->get()
        ->map(function($friend) use ($userId2) {
            return $friend->user_id === $userId2 ? $friend->friend_id : $friend->user_id;
        });
        
        return $user1Friends->intersect($user2Friends);
    }
    
    /**
     * Get friend suggestions for a user
     */
    public static function getFriendSuggestions(int $userId, int $limit = 10)
    {
        $user = User::find($userId);
        if (!$user) {
            return collect();
        }
        
        // Get IDs of current friends
        $friendIds = Friend::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })
        ->get()
        ->map(function($friend) use ($userId) {
            return $friend->user_id === $userId ? $friend->friend_id : $friend->user_id;
        })
        ->toArray();
        
        // Get mutual friends of friends (friends of friends)
        $suggestions = User::whereIn('id', function($query) use ($friendIds) {
            $query->select('friend_id')
                  ->from('vp_friends')
                  ->whereIn('user_id', $friendIds)
                  ->where('status', 'accepted')
                  ->union(
                      \DB::table('vp_friends')
                          ->select('user_id')
                          ->whereIn('friend_id', $friendIds)
                          ->where('status', 'accepted')
                  );
        })
        ->whereNotIn('id', array_merge($friendIds, [$userId]))
        ->limit($limit)
        ->get();
        
        return $suggestions;
    }
    
    /**
     * Get user's network statistics
     */
    public static function getNetworkStats(int $userId): array
    {
        return [
            'friends_count' => Friend::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('friend_id', $userId);
            })
            ->where('status', 'accepted')
            ->count(),
            
            'followers_count' => Follower::where('user_id', $userId)->count(),
            
            'following_count' => Follower::where('follower_id', $userId)->count(),
            
            'pending_requests_count' => Friend::where('friend_id', $userId)
                ->where('status', 'pending')
                ->count(),
        ];
    }
}
