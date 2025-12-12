{{-- VP Social Right Sidebar --}}
<aside class="hidden lg:block lg:col-span-3 space-y-4">
    {{-- Friend Requests --}}
    @php
        $pendingRequests = \Modules\VPEssential1\Models\Friend::where('friend_id', auth()->id())
            ->where('status', 'pending')
            ->with('user')
            ->latest()
            ->take(3)
            ->get();
    @endphp
    
    @if($pendingRequests->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-900 dark:text-white">Friend Requests</h3>
                <a href="{{ route('social.friends.requests') }}" 
                   class="text-sm text-blue-600 hover:underline">
                    See All
                </a>
            </div>
            <div class="space-y-3">
                @foreach($pendingRequests as $request)
                    <div class="flex items-center gap-3">
                        @if($request->user->profile && $request->user->profile->avatar)
                            <img src="{{ asset('storage/' . $request->user->profile->avatar) }}" 
                                 alt="{{ $request->user->name }}" 
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                                {{ strtoupper(substr($request->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <a href="{{ route('social.profile.user', $request->user_id) }}" 
                               class="font-semibold text-sm text-gray-900 dark:text-white hover:underline">
                                {{ $request->user->name }}
                            </a>
                            <div class="flex gap-2 mt-1">
                                <form action="{{ route('social.friends.accept', $request->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="text-xs px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Accept
                                    </button>
                                </form>
                                <form action="{{ route('social.friends.reject', $request->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Friend Suggestions --}}
    @php
        $suggestions = \App\Models\User::where('id', '!=', auth()->id())
            ->whereNotIn('id', function($query) {
                $query->select('friend_id')
                    ->from('vp_friends')
                    ->where('user_id', auth()->id());
            })
            ->whereNotIn('id', function($query) {
                $query->select('user_id')
                    ->from('vp_friends')
                    ->where('friend_id', auth()->id());
            })
            ->inRandomOrder()
            ->take(5)
            ->get();
    @endphp

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">People You May Know</h3>
        <div class="space-y-3">
            @foreach($suggestions as $user)
                <div class="flex items-center gap-3">
                    @if($user->profile && $user->profile->avatar)
                        <img src="{{ asset('storage/' . $user->profile->avatar) }}" 
                             alt="{{ $user->name }}" 
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <a href="{{ route('social.profile.user', $user->id) }}" 
                           class="font-semibold text-sm text-gray-900 dark:text-white hover:underline">
                            {{ $user->name }}
                        </a>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            {{ '@' . ($user->username ?? $user->name) }}
                        </p>
                    </div>
                    <form action="{{ route('social.friends.request', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Add
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    {{-- My Groups Widget --}}
    @php
        $myGroups = auth()->user()->groups()
            ->wherePivot('status', 'approved')
            ->orderByDesc('vp_groups.members_count')
            ->take(5)
            ->get();
    @endphp

    @if($myGroups->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-900 dark:text-white">My Groups</h3>
                <a href="{{ route('social.groups.index') }}" 
                   class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                    See All
                </a>
            </div>
            <div class="space-y-2">
                @foreach($myGroups as $group)
                    <a href="{{ route('social.groups.show', $group->slug) }}" 
                       class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        @if($group->avatar)
                            <img src="{{ asset('storage/' . $group->avatar) }}" 
                                 class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($group->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">
                                {{ $group->name }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ number_format($group->members_count) }} members
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Trending Hashtags --}}
    @php
        $trendingTags = \Modules\VPEssential1\Models\Hashtag::orderByDesc('usage_count')
            ->take(10)
            ->get();
    @endphp

    @if($trendingTags->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-bold text-gray-900 dark:text-white mb-3">Trending Hashtags</h3>
            <div class="space-y-2">
                @foreach($trendingTags as $tag)
                    <a href="{{ route('social.hashtag', $tag->slug) }}" 
                       class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <span class="text-blue-600 dark:text-blue-400 font-medium">
                            #{{ $tag->name }}
                        </span>
                        <span class="text-xs text-gray-600 dark:text-gray-400">
                            {{ $tag->usage_count }} {{ Str::plural('post', $tag->usage_count) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Footer Links --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
            <div class="flex flex-wrap gap-2">
                <a href="#" class="hover:underline">About</a>
                <span>·</span>
                <a href="#" class="hover:underline">Help</a>
                <span>·</span>
                <a href="#" class="hover:underline">Privacy</a>
                <span>·</span>
                <a href="#" class="hover:underline">Terms</a>
            </div>
            <p class="text-gray-500 dark:text-gray-500">
                © {{ date('Y') }} VantaPress Social
            </p>
        </div>
    </div>
</aside>
