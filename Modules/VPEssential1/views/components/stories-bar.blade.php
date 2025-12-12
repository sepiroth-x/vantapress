{{-- Stories Bar Component --}}
@php
    $friendIds = auth()->user()->friends()
        ->where('status', 'accepted')
        ->pluck('friend_id')
        ->merge(auth()->user()->friendRequestsReceived()->where('status', 'accepted')->pluck('user_id'))
        ->push(auth()->id())
        ->unique();

    $stories = \Modules\VPEssential1\Models\Story::with(['user', 'user.profile'])
        ->whereIn('user_id', $friendIds)
        ->active()
        ->orderByDesc('created_at')
        ->get()
        ->groupBy('user_id');
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
    <div class="flex gap-3 overflow-x-auto scrollbar-hide">
        {{-- Create Story Button --}}
        <a href="{{ route('social.stories.create') }}" 
           class="flex-shrink-0 w-24 h-32 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex flex-col items-center justify-center text-white hover:scale-105 transition cursor-pointer">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="text-xs font-medium">Create Story</span>
        </a>

        {{-- User Stories --}}
        @foreach($stories as $userId => $userStories)
            @php
                $firstStory = $userStories->first();
                $user = $firstStory->user;
                $hasViewed = $userStories->every(fn($story) => $story->hasViewedBy(auth()->id()));
            @endphp
            
            <a href="{{ route('social.stories.show', $firstStory->id) }}" 
               class="flex-shrink-0 relative group">
                <div class="w-24 h-32 rounded-lg overflow-hidden {{ $hasViewed ? 'ring-2 ring-gray-400' : 'ring-4 ring-blue-500' }}">
                    @if($firstStory->type === 'image')
                        <img src="{{ asset('storage/' . $firstStory->media_url) }}" 
                             class="w-full h-full object-cover">
                    @elseif($firstStory->type === 'video')
                        <video src="{{ asset('storage/' . $firstStory->media_url) }}" 
                               class="w-full h-full object-cover"></video>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white text-xs text-center p-2" 
                             style="background-color: {{ $firstStory->background_color }}">
                            {{ \Str::limit($firstStory->content, 80) }}
                        </div>
                    @endif
                    
                    {{-- Gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-transparent"></div>
                    
                    {{-- User avatar --}}
                    <div class="absolute top-2 left-2">
                        @if($user->profile && $user->profile->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" 
                                 class="w-10 h-10 rounded-full border-2 border-white object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-300 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    {{-- User name --}}
                    <div class="absolute bottom-2 left-2 right-2">
                        <p class="text-white text-xs font-semibold truncate drop-shadow-lg">
                            {{ $user->name }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach

        @if($stories->count() === 0)
            <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm py-8">
                No stories yet. Be the first to share!
            </div>
        @endif
    </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
