<!-- Right Sidebar - Suggestions & Trending -->
<div class="space-y-4">
    <!-- Friend Suggestions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3">People You May Know</h3>
        <div class="space-y-3">
            @forelse(auth()->user()->suggestedFriends()->take(3)->get() as $suggestion)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <img src="{{ $suggestion->avatar_url }}" 
                         alt="{{ $suggestion->name }}" 
                         class="h-10 w-10 rounded-full object-cover">
                    <div>
                        <p class="font-medium text-sm">{{ $suggestion->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $suggestion->mutualFriendsCount() }} mutual friends</p>
                    </div>
                </div>
                <form action="{{ route('social.friends.request', $suggestion->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Add
                    </button>
                </form>
            </div>
            @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No suggestions available</p>
            @endforelse
        </div>
    </div>
    
    <!-- Trending Hashtags -->
    @if(vp_social_setting('enable_hashtags'))
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3">Trending Hashtags</h3>
        <div class="space-y-2">
            @php
                $trendingHashtags = \Modules\VPEssential1\Models\Hashtag::orderBy('usage_count', 'desc')->take(5)->get();
            @endphp
            
            @forelse($trendingHashtags as $hashtag)
            <a href="{{ route('social.hashtag', $hashtag->tag) }}" class="block p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <p class="font-medium text-blue-600 dark:text-blue-400">#{{ $hashtag->tag }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $hashtag->usage_count }} posts</p>
            </a>
            @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No trending hashtags</p>
            @endforelse
        </div>
    </div>
    @endif
    
    <!-- Active Users -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="font-semibold mb-3">Active Now</h3>
        <div class="space-y-2">
            @php
                $activeUsers = \App\Models\User::where('last_active_at', '>=', now()->subMinutes(15))
                                               ->where('id', '!=', auth()->id())
                                               ->take(5)
                                               ->get();
            @endphp
            
            @forelse($activeUsers as $user)
            <a href="{{ route('social.profile', $user->id) }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}" 
                         class="h-10 w-10 rounded-full object-cover">
                    <span class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-green-400 ring-2 ring-white dark:ring-gray-800"></span>
                </div>
                <span class="text-sm font-medium">{{ $user->name }}</span>
            </a>
            @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-2">No active users</p>
            @endforelse
        </div>
    </div>
</div>
