{{--
/**
 * The Villain Arise - Header Partial
 * 
 * Global header component with navigation and branding.
 * Integrates with VP Essential 1 menu system.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 * 
 * DEVELOPER NOTES:
 * - Uses vp_get_menu() helper from VP Essential 1
 * - Logo customizable via theme settings
 * - Responsive mobile menu
 * - Dark theme with red accent
 */
--}}
<header class="villain-header bg-gray-950 border-b border-villain-600/30 sticky top-0 z-50 backdrop-blur-sm bg-gray-950/90">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            
            {{-- Logo / Branding --}}
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    @if(function_exists('vp_get_theme_setting'))
                        @php
                            $logo = vp_get_theme_setting('logo');
                        @endphp
                        @if($logo)
                            <img src="{{ asset($logo) }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
                        @else
                            <div class="text-2xl font-black font-orbitron">
                                <span class="text-villain-500 group-hover:text-villain-400 transition">VILLAIN</span>
                                <span class="text-gray-100">PRESS</span>
                            </div>
                        @endif
                    @else
                        <div class="text-2xl font-black font-orbitron">
                            <span class="text-villain-500 group-hover:text-villain-400 transition">VILLAIN</span>
                            <span class="text-gray-100">PRESS</span>
                        </div>
                    @endif
                </a>
            </div>
            
            {{-- Primary Navigation --}}
            <nav class="hidden md:flex items-center space-x-8">
                @if(function_exists('vp_get_menu'))
                    @php
                        $primaryMenu = vp_get_menu('primary');
                    @endphp
                    @if($primaryMenu && count($primaryMenu) > 0)
                        @foreach($primaryMenu as $item)
                            <a href="{{ $item['url'] }}" 
                               class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider
                                      {{ request()->is(trim($item['url'], '/')) ? 'text-villain-500' : '' }}">
                                {{ $item['title'] }}
                            </a>
                        @endforeach
                    @else
                        {{-- Default Menu --}}
                        <a href="{{ url('/') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Home</a>
                        <a href="{{ url('/pages') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Pages</a>
                        <a href="{{ url('/about') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">About</a>
                        <a href="{{ url('/contact') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Contact</a>
                    @endif
                @else
                    {{-- Fallback Default Menu --}}
                    <a href="{{ url('/') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Home</a>
                    <a href="{{ url('/pages') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Pages</a>
                    <a href="{{ url('/about') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">About</a>
                    <a href="{{ url('/contact') }}" class="text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">Contact</a>
                @endif
            </nav>
            
            {{-- Action Buttons --}}
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ url('/admin') }}" 
                       class="bg-villain-600 hover:bg-villain-700 text-white px-4 py-2 rounded font-medium text-sm transition">
                        Admin Panel
                    </a>
                    @if(function_exists('vp_get_current_user_profile'))
                        <a href="{{ url('/profile') }}" 
                           class="text-gray-300 hover:text-villain-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="text-gray-300 hover:text-villain-500 transition font-medium text-sm">
                        Login
                    </a>
                @endauth
                
                {{-- Mobile Menu Toggle --}}
                <button id="mobile-menu-toggle" class="md:hidden text-gray-300 hover:text-villain-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-villain-600/30 mt-2 pt-4">
            @if(function_exists('vp_get_menu'))
                @php
                    $primaryMenu = vp_get_menu('primary');
                @endphp
                @if($primaryMenu && count($primaryMenu) > 0)
                    @foreach($primaryMenu as $item)
                        <a href="{{ $item['url'] }}" 
                           class="block py-2 text-gray-300 hover:text-villain-500 transition font-medium uppercase text-sm tracking-wider">
                            {{ $item['title'] }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ url('/') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Home</a>
                    <a href="{{ url('/pages') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Pages</a>
                    <a href="{{ url('/about') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">About</a>
                    <a href="{{ url('/contact') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Contact</a>
                @endif
            @else
                <a href="{{ url('/') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Home</a>
                <a href="{{ url('/pages') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Pages</a>
                <a href="{{ url('/about') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">About</a>
                <a href="{{ url('/contact') }}" class="block py-2 text-gray-300 hover:text-villain-500 transition">Contact</a>
            @endif
        </div>
    </div>
</header>
