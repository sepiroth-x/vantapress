<header class="sticky top-0 z-50 shadow-sm" style="background-color: var(--header-bg-color); color: var(--header-text-color);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo & Search -->
            <div class="flex items-center space-x-4 flex-1">
                <!-- Logo -->
                <a href="{{ route('social.newsfeed') }}" class="flex items-center space-x-2">
                    @if(vp_get_theme_setting('site_logo'))
                        <img src="{{ vp_get_theme_setting('site_logo') }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-xl" 
                             style="background-color: var(--color-primary);">
                            VP
                        </div>
                    @endif
                    <span class="font-bold text-xl hidden sm:block" data-vp-element="site_title">
                        {{ vp_get_theme_setting('site_title', 'VP Social') }}
                    </span>
                </a>
                
                <!-- Search Bar -->
                @if(vp_get_theme_setting('show_search', true))
                <div class="hidden md:block flex-1 max-w-md">
                    <form action="{{ route('social.search') }}" method="GET" class="relative">
                        <input type="text" 
                               name="q" 
                               placeholder="Search..." 
                               class="w-full px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 border-0 focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            
            <!-- Navigation Icons -->
            <nav class="flex items-center space-x-2 md:space-x-4">
                <a href="{{ route('social.newsfeed') }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                   title="Newsfeed">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
                
                <a href="{{ route('social.friends') }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                   title="Friends">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </a>
                
                <a href="{{ route('social.messages') }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition relative"
                   title="Messages">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    @if(auth()->user()->unreadMessagesCount() > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                        {{ auth()->user()->unreadMessagesCount() }}
                    </span>
                    @endif
                </a>
                
                @if(vp_get_theme_setting('show_notifications', true))
                <a href="{{ route('social.notifications') }}" 
                   class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition relative"
                   title="Notifications">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                        {{ auth()->user()->unreadNotifications()->count() }}
                    </span>
                    @endif
                </a>
                @endif
                
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <img src="{{ auth()->user()->avatar_url }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="h-8 w-8 rounded-full object-cover">
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            <a href="{{ route('social.profile', auth()->id()) }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                My Profile
                            </a>
                            <a href="{{ route('social.profile.edit') }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                Edit Profile
                            </a>
                            <a href="{{ route('social.settings') }}" 
                               class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                Settings
                            </a>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            <button id="darkModeToggle" 
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                Toggle Dark Mode
                            </button>
                            <hr class="my-1 border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
