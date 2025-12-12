<?php

namespace Modules\VPEssential1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\VPEssential1\Models\Post;
use Modules\VPEssential1\Models\PostShare;
use Modules\VPEssential1\Models\SocialSetting;
use Modules\VPEssential1\Services\HashtagService;
use Modules\VPEssential1\Services\UrlPreviewService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $hashtagService;
    protected $urlPreviewService;
    
    public function __construct(HashtagService $hashtagService, UrlPreviewService $urlPreviewService)
    {
        $this->hashtagService = $hashtagService;
        $this->urlPreviewService = $urlPreviewService;
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
            ->withCount('comments')
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
            'media.*' => 'file|mimetypes:image/*,video/mp4,video/mpeg,video/quicktime,video/x-msvideo,video/webm|max:102400',
            'link_url' => 'nullable|url',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['type'] = $validated['type'] ?? 'status';
        $validated['visibility'] = $validated['visibility'] ?? 'public';
        
        // Handle media uploads (photos and videos)
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
        
        // Extract URL preview if URL found in content
        $urlPreview = $this->urlPreviewService->getFirstUrlPreview($validated['content']);
        if ($urlPreview) {
            $post->url_preview = $urlPreview;
            $post->save();
        }
        
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
     * Show edit form
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('vpessential1::posts.edit', compact('post'));
    }
    
    /**
     * Update post
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:' . SocialSetting::get('max_post_length', 5000),
            'visibility' => 'in:public,friends,private',
        ]);
        
        $post->update($validated);
        
        // Re-extract and attach hashtags
        $this->hashtagService->detach($post);
        $this->hashtagService->extractAndAttach($post, $validated['content']);
        
        // Re-extract URL preview
        $urlPreview = $this->urlPreviewService->getFirstUrlPreview($validated['content']);
        $post->url_preview = $urlPreview;
        $post->save();
        
        return redirect()->route('social.posts.index')->with('success', 'Post updated successfully!');
    }
    
    /**
     * Toggle pin status
     */
    public function pin(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Unpin other posts if pinning this one
        if (!$post->is_pinned) {
            Post::where('user_id', Auth::id())
                ->where('is_pinned', true)
                ->update(['is_pinned' => false]);
        }
        
        $post->is_pinned = !$post->is_pinned;
        $post->save();
        
        $message = $post->is_pinned ? 'Post pinned to your profile!' : 'Post unpinned from your profile.';
        return redirect()->back()->with('success', $message);
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
            'link' => route('social.posts.show', $post->id),
        ]);
        
        return redirect()->back()->with('success', 'Post shared successfully!');
    }
    
    /**
     * Show posts by hashtag
     */
    public function hashtag(string $tag)
    {
        $posts = $this->hashtagService->search($tag, Post::class);
        
        // Eager load relationships for post cards
        $postIds = $posts->pluck('id');
        $posts = Post::whereIn('id', $postIds)
            ->with(['user', 'user.profile', 'comments', 'reactions'])
            ->get()
            ->sortByDesc('created_at')
            ->values();
        
        return view('vpessential1::posts.hashtag', compact('posts', 'tag'));
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
