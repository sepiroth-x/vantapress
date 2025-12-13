@extends('themes.VPSocial.layouts.app')

@section('title', 'Story')

@section('content')
<div class="min-h-screen bg-black">
    <!-- Story Viewer -->
    <div class="relative h-screen w-full flex items-center justify-center">
        <!-- Close Button -->
        <a href="{{ route('social.newsfeed') }}" class="absolute top-4 right-4 z-50 text-white hover:text-gray-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>

        <!-- Story Navigation -->
        <div class="absolute inset-0 flex items-center justify-between px-4 z-40">
            @if($userStories->first()->id !== $story->id)
            <button onclick="navigateStory('prev')" class="text-white hover:text-gray-300 p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            @else
            <div></div>
            @endif

            @if($userStories->last()->id !== $story->id)
            <button onclick="navigateStory('next')" class="text-white hover:text-gray-300 p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            @else
            <div></div>
            @endif
        </div>

        <!-- Story Content -->
        <div class="relative w-full max-w-md h-full">
            <!-- Progress Bars -->
            <div class="absolute top-0 left-0 right-0 z-30 flex gap-1 p-2">
                @foreach($userStories as $index => $userStory)
                <div class="flex-1 h-1 bg-gray-600 rounded-full overflow-hidden">
                    <div class="h-full bg-white transition-all duration-300 {{ $userStory->id === $story->id ? 'w-full' : ($userStory->id < $story->id ? 'w-full' : 'w-0') }}"></div>
                </div>
                @endforeach
            </div>

            <!-- User Info -->
            <div class="absolute top-4 left-4 right-16 z-30 flex items-center gap-3">
                <img src="{{ $story->user->profile->avatar_url ?? asset('images/default-avatar.png') }}" 
                     alt="{{ $story->user->name }}" 
                     class="w-10 h-10 rounded-full border-2 border-white">
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-sm">{{ $story->user->name }}</h3>
                    <p class="text-gray-300 text-xs">{{ $story->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Story Media/Content -->
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-900 to-black">
                @if($story->type === 'image')
                    <img src="{{ Storage::url($story->media_url) }}" 
                         alt="Story" 
                         class="max-w-full max-h-full object-contain">
                @elseif($story->type === 'video')
                    <video src="{{ Storage::url($story->media_url) }}" 
                           class="max-w-full max-h-full object-contain" 
                           controls 
                           autoplay 
                           loop></video>
                @elseif($story->type === 'text')
                    <div class="p-8 text-center" style="background-color: {{ $story->background_color ?? '#1877f2' }}">
                        <p class="text-white text-2xl font-bold">{{ $story->content }}</p>
                    </div>
                @endif
            </div>

            <!-- Story Actions -->
            @if($story->user_id === auth()->id())
            <div class="absolute bottom-4 left-4 right-4 z-30">
                <div class="bg-black/50 backdrop-blur-sm rounded-lg p-4">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <p class="text-sm">Views: {{ $story->views_count ?? 0 }}</p>
                        </div>
                        <form action="{{ route('social.stories.destroy', $story->id) }}" method="POST" onsubmit="return confirm('Delete this story?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-semibold">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Story navigation data
const stories = @json($userStories->map(function($s) {
    return ['id' => $s->id, 'url' => route('social.stories.show', $s->id)];
})->values());

const currentIndex = stories.findIndex(s => s.id === {{ $story->id }});

function navigateStory(direction) {
    let newIndex = currentIndex;
    
    if (direction === 'next' && currentIndex < stories.length - 1) {
        newIndex = currentIndex + 1;
    } else if (direction === 'prev' && currentIndex > 0) {
        newIndex = currentIndex - 1;
    }
    
    if (newIndex !== currentIndex) {
        window.location.href = stories[newIndex].url;
    }
}

// Auto-advance after duration (for images/text)
@if($story->type !== 'video')
setTimeout(() => {
    if (currentIndex < stories.length - 1) {
        navigateStory('next');
    } else {
        window.location.href = '{{ route('social.newsfeed') }}';
    }
}, {{ ($story->duration ?? 5) * 1000 }});
@endif

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') navigateStory('prev');
    if (e.key === 'ArrowRight') navigateStory('next');
    if (e.key === 'Escape') window.location.href = '{{ route('social.newsfeed') }}';
});
</script>
@endsection
