{{--
/**
 * The Villain Arise - Home Page
 * 
 * Landing page template with hero section and feature cards.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 */
--}}
@extends('theme.layouts::main')

@section('title', 'Home')

@section('content')

{{-- Hero Section --}}
@include('theme.components::hero')

{{-- Features Section --}}
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black font-orbitron text-transparent bg-clip-text bg-gradient-to-r from-villain-500 to-villain-300 mb-4">
                VILLAIN FEATURES
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Unleash the full potential of your content with these powerful features
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Feature Card 1 --}}
            <div class="villain-card group bg-gray-950 border border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="w-12 h-12 bg-villain-600/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-villain-600 transition">
                    <svg class="w-6 h-6 text-villain-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-2 uppercase">Modular</h3>
                <p class="text-gray-400">Install modules (.vpm) to extend functionality without touching core code.</p>
            </div>
            
            {{-- Feature Card 2 --}}
            <div class="villain-card group bg-gray-950 border border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="w-12 h-12 bg-villain-600/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-villain-600 transition">
                    <svg class="w-6 h-6 text-villain-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-2 uppercase">Themeable</h3>
                <p class="text-gray-400">Customize your site with beautiful themes (.vpt) that override default views.</p>
            </div>
            
            {{-- Feature Card 3 --}}
            <div class="villain-card group bg-gray-950 border border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="w-12 h-12 bg-villain-600/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-villain-600 transition">
                    <svg class="w-6 h-6 text-villain-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-2 uppercase">Fast</h3>
                <p class="text-gray-400">Built on Laravel 11 with optimized caching for blazing-fast performance.</p>
            </div>
            
            {{-- Feature Card 4 --}}
            <div class="villain-card group bg-gray-950 border border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="w-12 h-12 bg-villain-600/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-villain-600 transition">
                    <svg class="w-6 h-6 text-villain-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-2 uppercase">Secure</h3>
                <p class="text-gray-400">Enterprise-grade security with path traversal protection and file validation.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-br from-gray-950 via-villain-900/10 to-gray-950 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="grid-pattern"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-black font-orbitron mb-6">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-villain-500 to-villain-300">
                    READY TO RISE?
                </span>
            </h2>
            <p class="text-xl text-gray-400 mb-8">
                Join the villain side and build something extraordinary with VantaPress CMS
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://github.com/sepiroth-x/vantapress" 
                   target="_blank"
                   class="px-8 py-4 bg-villain-600 hover:bg-villain-700 text-white font-bold rounded-lg transition-all transform hover:scale-105 hover:shadow-xl hover:shadow-villain-500/50 uppercase tracking-wider">
                    Get Started
                </a>
                <a href="https://github.com/sepiroth-x/vantapress#readme" 
                   target="_blank"
                   class="px-8 py-4 border-2 border-villain-600 hover:border-villain-500 text-villain-500 hover:text-villain-400 font-bold rounded-lg transition-all uppercase tracking-wider">
                    View Documentation
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
