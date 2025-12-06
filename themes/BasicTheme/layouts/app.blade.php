<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'VantaPress') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Theme Styles -->
    <link rel="stylesheet" href="{{ asset('themes/BasicTheme/assets/css/theme.css') }}">
    
    <!-- Dynamic Theme Variables -->
    <style id="vp-theme-vars">
        :root {
            @php
                $cssVars = [
                    'color-primary' => vp_get_theme_setting('primary_color', '#3b82f6'),
                    'color-secondary' => vp_get_theme_setting('secondary_color', '#8b5cf6'),
                    'color-accent' => vp_get_theme_setting('accent_color', '#10b981'),
                    'header-bg-color' => vp_get_theme_setting('header_bg_color', '#ffffff'),
                    'header-text-color' => vp_get_theme_setting('header_text_color', '#1e293b'),
                    'footer-bg-color' => vp_get_theme_setting('footer_bg_color', '#1e293b'),
                    'footer-text-color' => vp_get_theme_setting('footer_text_color', '#ffffff'),
                ];
            @endphp
            @foreach($cssVars as $varName => $varValue)
            --{{ $varName }}: {{ $varValue }};
            @endforeach
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('theme.components::header')
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('theme.components::footer')
    
    @stack('scripts')
</body>
</html>
