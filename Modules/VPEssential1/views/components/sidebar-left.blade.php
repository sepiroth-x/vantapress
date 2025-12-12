{{-- VP Social Left Sidebar --}}
<aside class="hidden lg:block lg:col-span-3 space-y-4">
    {{-- User Profile Summary --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex items-center gap-3 mb-4">
            @if(auth()->user()->profile && auth()->user()->profile->avatar)
                <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-12 h-12 rounded-full object-cover">
            @else
                <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1">
                <a href="{{ route('social.profile.user', auth()->id()) }}" 
                   class="font-semibold text-gray-900 dark:text-white hover:underline">
                    {{ auth()->user()->name }}
                </a>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ '@' . (auth()->user()->username ?? auth()->user()->name) }}
                </p>
            </div>
        </div>
        
        <div class="grid grid-cols-3 gap-2 text-center text-sm">
            <div>
                <div class="font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->posts()->count() }}
                </div>
                <div class="text-gray-600 dark:text-gray-400">Posts</div>
            </div>
            <div>
                <div class="font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->friends()->where('status', 'accepted')->count() }}
                </div>
                <div class="text-gray-600 dark:text-gray-400">Friends</div>
            </div>
            <div>
                <div class="font-bold text-gray-900 dark:text-white">
                    {{ auth()->user()->followers()->count() }}
                </div>
                <div class="text-gray-600 dark:text-gray-400">Followers</div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Quick Links</h3>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('social.newsfeed') }}" 
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="text-2xl">🏠</span>
                    <span class="text-gray-700 dark:text-gray-300">Newsfeed</span>
                </a>
            </li>
            <li>
                <a href="{{ route('social.profile.user', auth()->id()) }}" 
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="text-2xl">👤</span>
                    <span class="text-gray-700 dark:text-gray-300">My Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('social.friends.index') }}" 
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="text-2xl">👥</span>
                    <span class="text-gray-700 dark:text-gray-300">Friends</span>
                </a>
            </li>
            <li>
                <a href="{{ route('social.messages.index') }}" 
                   class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <span class="text-2xl">💬</span>
                    <span class="text-gray-700 dark:text-gray-300">Messages</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
