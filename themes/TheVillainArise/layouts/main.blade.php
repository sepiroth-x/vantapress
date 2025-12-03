{{--
/**
 * The Villain Arise - Main Layout
 * 
 * This is the master layout template for The Villain Arise theme.
 * It defines the overall HTML structure and includes all theme components.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 * 
 * DEVELOPER NOTES:
 * - Uses Tailwind CSS via CDN (no build process required)
 * - Integrates with VP Essential 1 module for customization
 * - Supports widget areas: header, footer, sidebar
 * - Supports menu locations: primary, footer
 * - Dark villain aesthetic with red accent colors
 * 
 * BLADE SECTIONS:
 * @section('title') - Page title
 * @section('meta') - Additional meta tags
 * @section('styles') - Additional CSS
 * @section('content') - Main page content
 * @section('scripts') - Additional JavaScript
 */
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'VantaPress')) - The Villain Arise</title>
    
    {{-- Additional Meta Tags --}}
    @yield('meta')
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Custom Tailwind Config --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        villain: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    
    {{-- Theme Styles --}}
    <link rel="stylesheet" href="{{ asset('themes/TheVillainArise/assets/css/theme.css') }}">
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    
    {{-- Additional Styles --}}
    @stack('styles')
</head>
<body class="bg-gray-900 text-gray-100 font-mono">
    
    {{-- Header Component --}}
    @include('theme.partials::header')
    
    {{-- Header Widget Area --}}
    @if(function_exists('vp_get_widget_area'))
        {!! vp_get_widget_area('header') !!}
    @endif
    
    {{-- Main Content Area --}}
    <main class="min-h-screen">
        @yield('content')
    </main>
    
    {{-- Footer Component --}}
    @include('theme.partials::footer')
    
    {{-- Footer Widget Area --}}
    @if(function_exists('vp_get_widget_area'))
        {!! vp_get_widget_area('footer') !!}
    @endif
    
    {{-- Theme JavaScript --}}
    <script src="{{ asset('themes/TheVillainArise/assets/js/theme.js') }}"></script>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>
