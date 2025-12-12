@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
{{-- Hero Section --}}
<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-60 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 right-1/3 w-72 h-72 bg-pink-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    {{-- Navigation --}}
    @auth
    <nav class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">{{ vp_get_theme_setting('site_title', 'VP Social') }}</span>
            </div>
            <div class="hidden md:flex items-center space-x-4">
                <a href="#features" class="text-white/90 hover:text-white transition px-4 py-2 rounded-lg hover:bg-white/10">Features</a>
                <a href="#about" class="text-white/90 hover:text-white transition px-4 py-2 rounded-lg hover:bg-white/10">About</a>
                <a href="#login" class="text-white/90 hover:text-white transition px-4 py-2 rounded-lg hover:bg-white/10">Sign In</a>
                <a href="{{ url('/register') }}" class="bg-white text-purple-600 px-6 py-2 rounded-lg hover:bg-gray-100 transition font-bold">Sign Up</a>
            </div>
        </div>
    </nav>
    @endauth

    {{-- Hero Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left: Hero Text --}}
            <div class="text-white space-y-8">
                <div class="inline-block">
                    <span class="px-4 py-2 bg-white/20 backdrop-blur-lg rounded-full text-sm font-semibold">
                        🚀 The Next Generation Social Platform
                    </span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold leading-tight">
                    Connect,
                    <span class="block bg-gradient-to-r from-yellow-200 via-pink-200 to-purple-200 bg-clip-text text-transparent">
                        Share, Inspire
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl text-white/90 leading-relaxed">
                    Join millions of people sharing moments, building communities, and creating meaningful connections in a privacy-first environment.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#login" 
                       class="group px-8 py-4 bg-white text-purple-600 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-2xl hover:shadow-white/20 hover:scale-105 transform duration-300 flex items-center justify-center">
                        Get Started Free
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#features" 
                       class="px-8 py-4 bg-white/10 backdrop-blur-lg text-white rounded-xl font-bold text-lg hover:bg-white/20 transition border-2 border-white/30 flex items-center justify-center">
                        Learn More
                    </a>
                </div>

                {{-- Social Proof --}}
                <div class="flex items-center space-x-8 pt-8">
                    <div>
                        <div class="text-3xl font-bold">10K+</div>
                        <div class="text-sm text-white/70">Active Users</div>
                    </div>
                    <div class="w-px h-12 bg-white/30"></div>
                    <div>
                        <div class="text-3xl font-bold">50K+</div>
                        <div class="text-sm text-white/70">Posts Shared</div>
                    </div>
                    <div class="w-px h-12 bg-white/30"></div>
                    <div>
                        <div class="text-3xl font-bold">99.9%</div>
                        <div class="text-sm text-white/70">Uptime</div>
                    </div>
                </div>
            </div>

            {{-- Right: Login Card --}}
            <div id="login" class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Welcome Back
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Sign in to continue your journey
                    </p>
                </div>

                {{-- Error/Success Messages --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 p-4 border border-red-100 dark:border-red-900">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-red-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <div class="text-sm text-red-800 dark:text-red-200">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 rounded-xl bg-green-50 dark:bg-green-900/20 p-4 border border-green-100 dark:border-green-900">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Email or Username
                        </label>
                        <input type="text" 
                               name="email" 
                               id="email" 
                               required 
                               autofocus
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-800 dark:text-white transition"
                               placeholder="username or email@example.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               class="w-full px-4 py-3.5 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-800 dark:text-white transition"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-gray-600 dark:text-gray-400 font-medium">Remember me</span>
                        </label>
                        
                        <a href="{{ url('/forgot-password') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg hover:shadow-xl transform hover:scale-[1.02] duration-200">
                        Sign In
                    </button>
                </form>



                {{-- Test Accounts --}}
                @if(config('app.env') === 'local')
                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-900">
                    <p class="text-xs text-yellow-800 dark:text-yellow-200 font-bold mb-2">🧪 Test Accounts:</p>
                    <div class="space-y-1 text-xs text-yellow-700 dark:text-yellow-300 font-mono">
                        <p>john@test.com / password</p>
                        <p>jane@test.com / password</p>
                        <p>alex@test.com / password</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Features Section --}}
<div id="features" class="py-24 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Everything You Need to
                <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Connect</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Powerful features designed to help you build meaningful relationships and share your story.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Feature 1 --}}
            <div class="group p-8 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Connect with Friends</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Build your network and stay in touch with people who matter most. Send friend requests and grow your community.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="group p-8 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Share Your Moments</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Post photos, videos, and updates. Share what's happening in your life with your friends and followers.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="group p-8 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Private Messaging</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Have private conversations with your friends. Send messages, share content, and stay connected.
                </p>
            </div>

            {{-- Feature 4 --}}
            <div class="group p-8 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Reactions & Comments</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Express yourself with likes, reactions, and comments. Engage with content that matters to you.
                </p>
            </div>

            {{-- Feature 5 --}}
            <div class="group p-8 bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Privacy Controls</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    You control who sees your content. Choose between public, friends-only, or private visibility.
                </p>
            </div>

            {{-- Feature 6 --}}
            <div class="group p-8 bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Lightning Fast</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    Optimized for speed and performance. Enjoy a smooth, responsive experience on any device.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
<div id="about" class="py-24 bg-gradient-to-br from-purple-600 via-pink-600 to-indigo-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Ready to Get Started?
        </h2>
        <p class="text-xl text-white/90 mb-10 leading-relaxed">
            Join thousands of users already enjoying a better social experience. Create your free account in seconds.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/register') }}" 
               class="px-10 py-5 bg-white text-purple-600 rounded-xl font-bold text-lg hover:bg-gray-100 transition shadow-2xl hover:shadow-white/30 hover:scale-105 transform duration-300">
                Create Free Account
            </a>
            <a href="#features" 
               class="px-10 py-5 bg-white/10 backdrop-blur-lg text-white rounded-xl font-bold text-lg hover:bg-white/20 transition border-2 border-white/30">
                Learn More
            </a>
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8">
            <div class="col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg"></div>
                    <span class="text-xl font-bold">{{ vp_get_theme_setting('site_title', 'VP Social') }}</span>
                </div>
                <p class="text-gray-400 mb-4">
                    The next generation social platform. Connect, share, and inspire with people around the world.
                </p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Platform</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Features</a></li>
                    <li><a href="#" class="hover:text-white transition">Privacy</a></li>
                    <li><a href="#" class="hover:text-white transition">Security</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Support</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition">Terms</a></li>
                    <li><a href="#" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} {{ vp_get_theme_setting('site_title', 'VP Social') }}. All rights reserved.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.group').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.6s ease-out';
    observer.observe(el);
});
</script>
@endpush
@endsection
