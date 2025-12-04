{{--
/**
 * VantaPress Admin Panel Footer
 * 
 * Custom footer with developer attribution and social links.
 * Displayed at the bottom of all admin pages.
 * 
 * @package VantaPress
 * @version 1.0.17
 */
--}}
<div class="fi-footer border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 py-6 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Left: Attribution --}}
            <div class="text-center md:text-left">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Proudly created by 
                    <span class="font-bold text-primary-600 dark:text-primary-400">
                        Sepiroth X Villainous (Richard Cebel Cupal, LPT)
                    </span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    VantaPress v{{ config('version.version', '1.0.17-complete') }} • 
                    WordPress Philosophy, Laravel Power
                </p>
            </div>

            {{-- Right: Social Links --}}
            <div class="flex items-center gap-4">
                {{-- Email --}}
                <a href="mailto:chardy.tsadiq02@gmail.com" 
                   class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition"
                   title="Email: chardy.tsadiq02@gmail.com"
                   target="_blank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                </a>

                {{-- GitHub --}}
                <a href="https://github.com/sepiroth-x" 
                   class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition"
                   title="GitHub: @sepiroth-x"
                   target="_blank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path>
                    </svg>
                </a>

                {{-- Facebook --}}
                <a href="https://www.facebook.com/sepirothx/" 
                   class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition"
                   title="Facebook: @sepirothx"
                   target="_blank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>
                    </svg>
                </a>

                {{-- Twitter/X --}}
                <a href="https://x.com/sepirothx000" 
                   class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition"
                   title="Twitter/X: @sepirothx000"
                   target="_blank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                    </svg>
                </a>

                {{-- Mobile --}}
                <a href="tel:+639150388448" 
                   class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition"
                   title="Mobile: +63 915 0388 448"
                   target="_blank">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Bottom: Copyright --}}
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-500">
                Copyright © {{ date('Y') }} Sepiroth X Villainous. Licensed under MIT. 
                <a href="https://github.com/sepiroth-x/vantapress" 
                   class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 hover:underline transition"
                   target="_blank">
                    View on GitHub
                </a>
            </p>
        </div>
    </div>
</div>
