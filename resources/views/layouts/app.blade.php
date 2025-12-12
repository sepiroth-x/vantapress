<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ vp_get_theme_setting('dark_mode', true) ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ vp_get_theme_setting('site_title', 'VP Social') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('themes/VPSocial/assets/css/social.css') }}">
    
    <!-- Tailwind CSS (from main app) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <!-- Dynamic Theme Variables -->
    <style id="vp-theme-vars">
        :root {
            --color-primary: {{ vp_get_theme_setting('primary_color', '#1877f2') }};
            --color-secondary: {{ vp_get_theme_setting('secondary_color', '#42b72a') }};
            --color-accent: {{ vp_get_theme_setting('accent_color', '#1da1f2') }};
            --header-bg-color: {{ vp_get_theme_setting('header_bg_color', '#ffffff') }};
            --header-text-color: {{ vp_get_theme_setting('header_text_color', '#1c1e21') }};
            --footer-bg-color: {{ vp_get_theme_setting('footer_bg_color', '#242526') }};
            --footer-text-color: {{ vp_get_theme_setting('footer_text_color', '#b0b3b8') }};
        }
        
        .dark {
            --header-bg-color: #242526;
            --header-text-color: #e4e6eb;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Header Navigation -->
        @include('vpessential1::components.header')
        
        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
        
        <!-- Footer -->
        @include('vpessential1::components.footer')
    </div>
    
    @stack('scripts')
    
    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'));
                });
            }
            
            // Load saved dark mode preference
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
</body>
</html>
