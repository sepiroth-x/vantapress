<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Post;
use Modules\VPEssential1\Models\PostShare;
use Modules\VPEssential1\Models\SocialSetting;
use Modules\VPEssential1\Services\HashtagService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $hashtagService;
    
    public function __construct(HashtagService $hashtagService)
    {
        $this->hashtagService = $hashtagService;
    }
    
    /**
     * Display newsfeed
     */
    public function index()
    {
        if (!SocialSetting::isFeatureEnabled('posts')) {
            abort(404);
        }
        
        $posts = Post::with(['user', 'user.profile', 'comments', 'reactions'])
            ->where('visibility', 'public')
            ->orWhere(function($query) {
                $query->where('visibility', 'friends')
                      ->whereHas('user.friends', function($q) {
                          $q->where('friend_id', Auth::id());
                      });
            })
            ->latest()
            ->paginate(SocialSetting::get('posts_per_page', 20));
        
        return view('vpessential1::posts.index', compact('posts'));
    }
    
    /**
     * Store new post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:' . SocialSetting::get('max_post_length', 5000),
            'type' => 'in:status,photo,video,link',
            'visibility' => 'in:public,friends,private',
            'media' => 'nullable|array',
            'media.*' => 'image|max:5120',
            'link_url' => 'nullable|url',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['type'] = $validated['type'] ?? 'status';
        $validated['visibility'] = $validated['visibility'] ?? 'public';
        
        // Handle media uploads
        if ($request->hasFile('media')) {
            $mediaFiles = [];
            foreach ($request->file('media') as $file) {
                $mediaFiles[] = $file->store('posts', 'public');
            }
            $validated['media'] = $mediaFiles;
        }
        
        $post = Post::create($validated);
        
        // Extract and attach hashtags
        $this->hashtagService->extractAndAttach($post, $validated['content']);
        
        return redirect()->back()->with('success', 'Post created successfully!');
    }
    
    /**
     * Show single post
     */
    public function show(Post $post)
    {
        $post->load(['user', 'user.profile', 'comments.user', 'reactions']);
        
        return view('vpessential1::posts.show', compact('post'));
    }
    
    /**
     * Share a post
     */
    public function share(Request $request, Post $post)
    {
        if (!SocialSetting::isFeatureEnabled('sharing')) {
            abort(404);
        }
        
        $validated = $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);
        
        PostShare::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'] ?? null,
        ]);
        
        $post->increment('shares_count');
        
        // Create notification for post author
        \Modules\VPEssential1\Services\NotificationService::create([
            'user_id' => $post->user_id,
            'from_user_id' => Auth::id(),
            'type' => 'post_share',
            'notifiable_id' => $post->id,
            'notifiable_type' => Post::class,
            'title' => 'Post shared',
            'message' => Auth::user()->name . ' shared your post',
            'link' => route('posts.show', $post->id),
        ]);
        
        return redirect()->back()->with('success', 'Post shared successfully!');
    }
    
    /**
     * Delete post
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Delete media files
        if ($post->media) {
            foreach ($post->media as $media) {
                Storage::delete($media);
            }
        }
        
        $post->delete();
        
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }
}
