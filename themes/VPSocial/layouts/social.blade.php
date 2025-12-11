@extends('VPSocial::layouts.app')

@section('content')
<div class="social-layout">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Sidebar -->
            @if(vp_get_theme_setting('show_sidebar', true))
            <aside class="hidden lg:block lg:col-span-3">
                @include('VPSocial::components.sidebar-left')
            </aside>
            @endif
            
            <!-- Main Content Area -->
            <div class="{{ vp_get_theme_setting('show_sidebar', true) ? 'lg:col-span-6' : 'lg:col-span-9' }}">
                @yield('social-content')
            </div>
            
            <!-- Right Sidebar -->
            @if(vp_get_theme_setting('show_sidebar', true))
            <aside class="hidden lg:block lg:col-span-3">
                @include('VPSocial::components.sidebar-right')
            </aside>
            @endif
        </div>
    </div>
</div>
@endsection
