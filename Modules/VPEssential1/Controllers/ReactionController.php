<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Reaction;
use Modules\VPEssential1\Models\SocialSetting;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    /**
     * Toggle reaction on content
     */
    public function toggle(Request $request)
    {
        if (!SocialSetting::isFeatureEnabled('reactions')) {
            abort(404);
        }
        
        $validated = $request->validate([
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|string',
            'type' => 'required|in:like,love,haha,wow,sad,angry',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        // Check if user already reacted
        $existing = Reaction::where('user_id', Auth::id())
            ->where('reactable_id', $validated['reactable_id'])
            ->where('reactable_type', $validated['reactable_type'])
            ->first();
        
        if ($existing) {
            if ($existing->type === $validated['type']) {
                // Remove reaction
                $existing->delete();
                
                // Decrement likes count
                $reactable = $existing->reactable;
                if ($reactable && property_exists($reactable, 'likes_count')) {
                    $reactable->decrement('likes_count');
                    $reactable->refresh();
                }
                
                return response()->json([
                    'status' => 'removed',
                    'reacted' => false,
                    'likes_count' => $reactable ? $reactable->likes_count : 0
                ]);
            } else {
                // Change reaction type
                $existing->update(['type' => $validated['type']]);
                $reactable = $existing->reactable;
                
                return response()->json([
                    'status' => 'updated',
                    'reacted' => true,
                    'likes_count' => $reactable ? $reactable->likes_count : 0
                ]);
            }
        } else {
            // Create new reaction
            $reaction = Reaction::create($validated);
            
            // Increment likes count
            $reactable = $reaction->reactable;
            if ($reactable && property_exists($reactable, 'likes_count')) {
                $reactable->increment('likes_count');
                $reactable->refresh();
            }
            
            // Create notification
            if ($reactable->user_id !== Auth::id()) {
                \Modules\VPEssential1\Services\NotificationService::create([
                    'user_id' => $reactable->user_id,
                    'from_user_id' => Auth::id(),
                    'type' => class_basename($reactable) === 'Post' ? 'post_like' : 'tweet_like',
                    'notifiable_id' => $reactable->id,
                    'notifiable_type' => get_class($reactable),
                    'title' => 'New reaction',
                    'message' => Auth::user()->name . ' reacted to your ' . class_basename($reactable),
                    'link' => '#',
                ]);
            }
            
            return response()->json([
                'status' => 'created',
                'reacted' => true,
                'likes_count' => $reactable ? $reactable->likes_count : 0
            ]);
        }
    }
}
