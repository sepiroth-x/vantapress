@php
    use Modules\VPEssential1\Models\SocialSetting;
    $commentsDisplayCount = (int) SocialSetting::get('default_comments_display', 10);
@endphp
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-4">
    {{-- Post Header --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            {{-- User Avatar --}}
            <a href="{{ route('social.profile.user', $post->user_id) }}">
                @if($post->user->profile && $post->user->profile->avatar)
                    <img src="{{ asset('storage/' . $post->user->profile->avatar) }}" 
                         alt="{{ $post->user->name }}" 
                         class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                    </div>
                @endif
            </a>

            {{-- User Info --}}
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('social.profile.user', $post->user_id) }}" 
                       class="font-semibold text-gray-900 dark:text-white hover:underline">
                        {{ $post->user->profile->display_name ?? $post->user->name }}
                    </a>
                    @if($post->user->isVerified())
                        <span class="text-blue-500" title="Verified">✓</span>
                    @endif
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $post->created_at->diffForHumans() }}
                    @if($post->visibility === 'friends')
                        · 👥 Friends
                    @elseif($post->visibility === 'private')
                        · 🔒 Private
                    @endif
                </div>
            </div>
        </div>

        {{-- Post Menu --}}
        @if(auth()->id() === $post->user_id)
            <div class="relative">
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    {{-- Post Content --}}
    <div class="mb-4">
        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{!! preg_replace('/#(\w+)/', '<a href="' . route('social.hashtag', '$1') . '" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">#$1</a>', e($post->content)) !!}</p>
    </div>

    {{-- Post Media --}}
    @if($post->media)
        <div class="grid grid-cols-2 gap-2 mb-4">
            @foreach($post->media as $media)
                <img src="{{ asset('storage/' . $media) }}" 
                     alt="Post image" 
                     class="rounded-lg w-full h-64 object-cover">
            @endforeach
        </div>
    @endif

    {{-- Post Stats --}}
    <div class="flex items-center justify-between py-2 border-t border-b border-gray-200 dark:border-gray-700 mb-2">
        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
            <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }} {{ Str::plural('like', $post->likes_count) }}</span>
            <span id="comments-count-{{ $post->id }}">{{ $post->comments->count() }} {{ Str::plural('comment', $post->comments->count()) }}</span>
            <span>{{ $post->shares_count }} {{ Str::plural('share', $post->shares_count) }}</span>
        </div>
    </div>

    {{-- Post Actions --}}
    <div class="flex items-center gap-2">
        {{-- Like Button with Reactions --}}
        <div class="flex-1 relative" x-data="{ showReactions: false }">
            <button @mouseenter="showReactions = true" 
                    @mouseleave="showReactions = false"
                    onclick="toggleReaction({{ $post->id }}, 'post', 'like', this)" 
                    id="like-btn-{{ $post->id }}"
                    class="w-full py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition">
                <span id="like-icon-{{ $post->id }}">👍</span> Like
            </button>
            
            {{-- Reaction Picker --}}
            <div x-show="showReactions" 
                 x-transition
                 @mouseenter="showReactions = true"
                 @mouseleave="showReactions = false"
                 class="absolute bottom-full left-0 mb-2 bg-white dark:bg-gray-700 shadow-lg rounded-full px-3 py-2 flex gap-2 z-10">
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'like', this)" class="text-2xl hover:scale-125 transition-transform" title="Like">👍</button>
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'love', this)" class="text-2xl hover:scale-125 transition-transform" title="Love">❤️</button>
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'haha', this)" class="text-2xl hover:scale-125 transition-transform" title="Haha">😂</button>
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'wow', this)" class="text-2xl hover:scale-125 transition-transform" title="Wow">😮</button>
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'sad', this)" class="text-2xl hover:scale-125 transition-transform" title="Sad">😢</button>
                <button onclick="toggleReaction({{ $post->id }}, 'post', 'angry', this)" class="text-2xl hover:scale-125 transition-transform" title="Angry">😠</button>
            </div>
        </div>

        {{-- Comment Button --}}
        <button onclick="toggleComments({{ $post->id }})" 
                class="flex-1 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition">
            💬 Comment
        </button>

        {{-- Share Button --}}
        <form action="{{ route('social.posts.share', $post->id) }}" method="POST" class="flex-1" onsubmit="return handleShare(event, {{ $post->id }})">
            @csrf
            <button type="submit" 
                    class="w-full py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium transition">
                🔄 Share
            </button>
        </form>
    </div>

    {{-- Comments Section --}}
    <div id="comments-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        {{-- Comment Form --}}
        <form action="{{ route('social.comments.store') }}" method="POST" class="mb-4" onsubmit="return handleCommentSubmit(event, {{ $post->id }})">
            @csrf
            <input type="hidden" name="commentable_id" value="{{ $post->id }}">
            <input type="hidden" name="commentable_type" value="{{ get_class($post) }}">
            
            <div class="flex gap-2">
                <input type="text" 
                       name="content" 
                       placeholder="Write a comment..."
                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                       required>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Post
                </button>
            </div>
        </form>

        {{-- Comments List --}}
        <div class="space-y-3" id="comments-list-{{ $post->id }}">
            @foreach($post->comments()->whereNull('parent_id')->latest()->take($commentsDisplayCount)->get() as $comment)
                <div class="comment-item" id="comment-{{ $comment->id }}">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-3 py-2">
                                <a href="{{ route('social.profile.user', $comment->user_id) }}" 
                                   class="font-semibold text-gray-900 dark:text-white hover:underline text-sm">
                                    {{ $comment->user->name }}
                                </a>
                                <p class="text-gray-900 dark:text-white text-sm">{{ $comment->content }}</p>
                            </div>
                            <div class="flex gap-4 mt-1 text-xs text-gray-600 dark:text-gray-400">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                                <button onclick="toggleReplyForm({{ $comment->id }})" class="hover:underline font-medium">Reply</button>
                                @if($comment->replies_count > 0)
                                    <button onclick="toggleReplies({{ $comment->id }})" class="hover:underline font-medium">
                                        {{ $comment->replies_count }} {{ Str::plural('reply', $comment->replies_count) }}
                                    </button>
                                @endif
                            </div>
                            
                            {{-- Reply Form --}}
                            <form action="{{ route('social.comments.store') }}" 
                                  method="POST" 
                                  id="reply-form-{{ $comment->id }}"
                                  class="hidden mt-2"
                                  onsubmit="return handleCommentSubmit(event, {{ $post->id }})">
                                @csrf
                                <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                                <input type="hidden" name="commentable_type" value="{{ get_class($post) }}">
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="content" 
                                           placeholder="Write a reply..."
                                           class="flex-1 px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                           required>
                                    <button type="submit" 
                                            class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        Reply
                                    </button>
                                </div>
                            </form>
                            
                            {{-- Replies --}}
                            @if($comment->replies_count > 0)
                                <div id="replies-{{ $comment->id }}" class="hidden mt-3 pl-4 border-l-2 border-gray-300 dark:border-gray-600 space-y-2">
                                    @foreach($comment->replies()->latest()->get() as $reply)
                                        <div class="flex gap-2" id="comment-{{ $reply->id }}">
                                            <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                                {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-2 py-1">
                                                    <a href="{{ route('social.profile.user', $reply->user_id) }}" 
                                                       class="font-semibold text-gray-900 dark:text-white hover:underline text-xs">
                                                        {{ $reply->user->name }}
                                                    </a>
                                                    <p class="text-gray-900 dark:text-white text-xs">{{ $reply->content }}</p>
                                                </div>
                                                <div class="flex gap-3 mt-1 text-xs text-gray-600 dark:text-gray-400">
                                                    <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if($post->comments_count > $commentsDisplayCount)
                <button onclick="loadMoreComments({{ $post->id }})" 
                        class="text-sm text-blue-600 hover:underline">
                    Load more comments...
                </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleComments(postId) {
    const commentsDiv = document.getElementById('comments-' + postId);
    commentsDiv.classList.toggle('hidden');
}

function toggleReplyForm(commentId) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    replyForm.classList.toggle('hidden');
    if (!replyForm.classList.contains('hidden')) {
        replyForm.querySelector('input[name="content"]').focus();
    }
}

function toggleReplies(commentId) {
    const repliesDiv = document.getElementById('replies-' + commentId);
    repliesDiv.classList.toggle('hidden');
}

function handleCommentSubmit(event, postId) {
    event.preventDefault();
    const form = event.target;
    const button = form.querySelector('button[type="submit"]');
    const originalText = button.textContent;
    
    button.disabled = true;
    button.textContent = 'Posting...';
    
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            // Increment comment count
            const commentsCountElement = document.getElementById('comments-count-' + postId);
            if (commentsCountElement) {
                const match = commentsCountElement.textContent.match(/\d+/);
                const currentCount = match ? parseInt(match[0]) : 0;
                const newCount = currentCount + 1;
                commentsCountElement.textContent = newCount + ' ' + (newCount === 1 ? 'comment' : 'comments');
            }
            
            // Reload page to show new comment
            setTimeout(() => location.reload(), 500);
        } else {
            throw new Error('Comment post failed');
        }
    })
    .catch(error => {
        console.error('Error posting comment:', error);
        button.disabled = false;
        button.textContent = originalText;
        alert('Failed to post comment. Please try again.');
    });
    
    return false;
}

function toggleReaction(id, contentType, reactionType, button) {
    // Prevent double clicks
    if (button.disabled) return;
    button.disabled = true;
    
    const reactionIcons = {
        'like': '👍',
        'love': '❤️',
        'haha': '😂',
        'wow': '😮',
        'sad': '😢',
        'angry': '😠'
    };
    
    fetch('{{ route("social.reactions.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reactable_id: id,
            reactable_type: contentType === 'post' ? 'Modules\\VPEssential1\\Models\\Post' : 'Modules\\VPEssential1\\Models\\Tweet',
            type: reactionType
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update the like count
        const likesCountElement = document.getElementById('likes-count-' + id);
        if (likesCountElement && data.likes_count !== undefined) {
            const newCount = data.likes_count;
            likesCountElement.textContent = newCount + ' ' + (newCount === 1 ? 'like' : 'likes');
        }
        
        // Update button style and icon
        const likeBtn = document.getElementById('like-btn-' + id);
        const likeIcon = document.getElementById('like-icon-' + id);
        
        if (data.reacted) {
            if (likeBtn) {
                likeBtn.classList.add('text-blue-600', 'dark:text-blue-400');
            }
            if (likeIcon) {
                likeIcon.textContent = reactionIcons[reactionType] || '👍';
            }
        } else {
            if (likeBtn) {
                likeBtn.classList.remove('text-blue-600', 'dark:text-blue-400');
            }
            if (likeIcon) {
                likeIcon.textContent = '👍';
            }
        }
        
        // Re-enable button
        button.disabled = false;
    })
    .catch(error => {
        console.error('Error toggling reaction:', error);
        button.disabled = false;
        alert('Failed to update reaction. Please try again.');
    });
}

function handleShare(event, postId) {
    event.preventDefault();
    const form = event.target;
    const button = form.querySelector('button');
    
    button.disabled = true;
    button.textContent = 'Sharing...';
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            button.textContent = '✓ Shared';
            button.classList.add('text-green-600');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error('Share failed');
        }
    })
    .catch(error => {
        console.error('Error sharing post:', error);
        button.disabled = false;
        button.textContent = '🔄 Share';
        alert('Failed to share post. Please try again.');
    });
    
    return false;
}

function loadMoreComments(postId) {
    // Placeholder for pagination - would need AJAX implementation
    alert('Load more comments feature coming soon!');
}
</script>
@endpush
