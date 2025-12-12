{{-- VP Social Theme Header --}}
<header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo / Brand --}}
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('social.newsfeed') : url('/') }}" class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">
                        {{ vp_get_theme_setting('site_title', 'VP Social') }}
                    </span>
                </a>
            </div>

            @auth
            {{-- Navigation Links (Logged In) --}}
            <nav class="hidden md:flex space-x-4">
                <a href="{{ route('social.newsfeed') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('social.newsfeed') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🏠 Newsfeed
                </a>
                <a href="{{ route('social.profile.show') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('social.profile.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    👤 Profile
                </a>
                <a href="{{ route('social.friends.index') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('social.friends.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    👥 Friends
                </a>
                <a href="{{ route('social.messages.index') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('social.messages.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    💬 Messages
                </a>
                <a href="{{ route('social.groups.index') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('social.groups.*') ? 'bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    🏘️ Groups
                </a>
            </nav>

            {{-- User Menu (Logged In) --}}
            <div class="flex items-center space-x-4">
                {{-- Dark Mode Toggle --}}
                <button id="darkModeToggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        @if(auth()->user()->profile && auth()->user()->profile->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50"
                         style="display: none;">
                        <a href="{{ route('social.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Your Profile
                        </a>
                        <a href="{{ route('social.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Settings
                        </a>
                        @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
                            <a href="{{ url('/admin') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Admin Panel
                            </a>
                        @endif
                        <hr class="my-2 border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ url('/logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            {{-- Guest Navigation --}}
            <div class="flex items-center space-x-4">
                <a href="{{ url('/login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                    Login
                </a>
                <a href="{{ url('/register') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    Sign Up
                </a>
            </div>
            @endauth
        </div>
    </div>
</header>

{{-- Alpine.js for dropdown (if not already included) --}}
@once
@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endonce
