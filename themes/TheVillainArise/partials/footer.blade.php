{{--
/**
 * The Villain Arise - Footer Partial
 * 
 * Global footer component with links, copyright, and social media.
 * Integrates with VP Essential 1 menu system.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 * 
 * DEVELOPER NOTES:
 * - Uses vp_get_menu() for footer menu
 * - Dark theme with red accent
 * - Responsive grid layout
 */
--}}
<footer class="villain-footer bg-gray-950 border-t border-villain-600/30 mt-20">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- About Section --}}
            <div class="col-span-1 md:col-span-2">
                <div class="text-2xl font-black font-orbitron mb-4">
                    <span class="text-villain-500">VILLAIN</span><span class="text-gray-100">PRESS</span>
                </div>
                <p class="text-gray-400 mb-4">
                    @if(function_exists('vp_get_theme_setting'))
                        {{ vp_get_theme_setting('footer_text', 'A powerful modular CMS built with Laravel. Unleash your inner villain and create something extraordinary.') }}
                    @else
                        A powerful modular CMS built with Laravel. Unleash your inner villain and create something extraordinary.
                    @endif
                </p>
                @if(function_exists('vp_get_theme_setting'))
                    @php
                        $social = vp_get_theme_setting('social_links', []);
                    @endphp
                    @if(!empty($social))
                        <div class="flex space-x-4">
                            @foreach($social as $platform => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" 
                                   class="text-gray-400 hover:text-villain-500 transition">
                                    <span class="sr-only">{{ ucfirst($platform) }}</span>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
            
            {{-- Quick Links --}}
            <div>
                <h3 class="text-lg font-bold font-orbitron text-villain-500 mb-4 uppercase tracking-wider">Quick Links</h3>
                <ul class="space-y-2">
                    @if(function_exists('vp_get_menu'))
                        @php
                            $footerMenu = vp_get_menu('footer');
                        @endphp
                        @if($footerMenu && count($footerMenu) > 0)
                            @foreach($footerMenu as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" class="text-gray-400 hover:text-villain-500 transition">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-villain-500 transition">Home</a></li>
                            <li><a href="{{ url('/pages') }}" class="text-gray-400 hover:text-villain-500 transition">Pages</a></li>
                            <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-villain-500 transition">About</a></li>
                            <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-villain-500 transition">Contact</a></li>
                        @endif
                    @else
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-villain-500 transition">Home</a></li>
                        <li><a href="{{ url('/pages') }}" class="text-gray-400 hover:text-villain-500 transition">Pages</a></li>
                        <li><a href="{{ url('/about') }}" class="text-gray-400 hover:text-villain-500 transition">About</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-gray-400 hover:text-villain-500 transition">Contact</a></li>
                    @endif
                </ul>
            </div>
            
            {{-- Resources --}}
            <div>
                <h3 class="text-lg font-bold font-orbitron text-villain-500 mb-4 uppercase tracking-wider">Resources</h3>
                <ul class="space-y-2">
                    <li><a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" class="text-gray-400 hover:text-villain-500 transition">Documentation</a></li>
                    <li><a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" class="text-gray-400 hover:text-villain-500 transition">Download</a></li>
                    <li><a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="text-gray-400 hover:text-villain-500 transition">GitHub Repository</a></li>
                    <li><a href="{{ url('/admin') }}" class="text-gray-400 hover:text-villain-500 transition">Admin Panel</a></li>
                </ul>
            </div>
        </div>
        
        {{-- Copyright Bar --}}
        <div class="mt-12 pt-8 border-t border-villain-600/30 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'VantaPress') }}. All rights reserved. Powered by <span class="text-villain-500 font-bold">VantaPress</span>.</p>
            <p class="mt-2 text-xs">
                Theme by <strong class="text-villain-500">Sepiroth X Villainous</strong> | Open Source | 
                <a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="hover:text-villain-500 transition">GitHub</a> | 
                <a href="https://www.facebook.com/sepirothx/" target="_blank" class="hover:text-villain-500 transition">Facebook</a> | 
                <a href="https://x.com/sepirothx000" target="_blank" class="hover:text-villain-500 transition">Twitter</a> | 
                <a href="mailto:chardy.tsadiq02@gmail.com" class="hover:text-villain-500 transition">Email</a>
            </p>
        </div>
    </div>
</footer>
