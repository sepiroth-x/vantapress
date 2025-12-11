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
        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $post->content }}</p>
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
            <span>{{ $post->likes_count }} likes</span>
            <span>{{ $post->comments_count }} comments</span>
            <span>{{ $post->shares_count }} shares</span>
        </div>
    </div>

    {{-- Post Actions --}}
    <div class="flex items-center gap-2">
        {{-- Like Button --}}
        <button onclick="toggleReaction({{ $post->id }}, 'post')" 
                class="flex-1 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium">
            👍 Like
        </button>

        {{-- Comment Button --}}
        <button onclick="toggleComments({{ $post->id }})" 
                class="flex-1 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium">
            💬 Comment
        </button>

        {{-- Share Button --}}
        <form action="{{ route('social.posts.share', $post->id) }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" 
                    class="w-full py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg font-medium">
                🔄 Share
            </button>
        </form>
    </div>

    {{-- Comments Section --}}
    <div id="comments-{{ $post->id }}" class="hidden mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        {{-- Comment Form --}}
        <form action="{{ route('social.comments.store') }}" method="POST" class="mb-4">
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
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Post
                </button>
            </div>
        </form>

        {{-- Comments List --}}
        @foreach($post->comments()->whereNull('parent_id')->latest()->take(5)->get() as $comment)
            <div class="flex gap-3 mb-3">
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
                        <button class="hover:underline">Like</button>
                        <button class="hover:underline">Reply</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function toggleComments(postId) {
    const commentsDiv = document.getElementById('comments-' + postId);
    commentsDiv.classList.toggle('hidden');
}

function toggleReaction(id, type) {
    fetch('{{ route("social.reactions.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reactable_id: id,
            reactable_type: type === 'post' ? 'Modules\\VPEssential1\\Models\\Post' : 'Modules\\VPEssential1\\Models\\Tweet',
            type: 'like'
        })
    }).then(() => location.reload());
}
</script>
@endpush
