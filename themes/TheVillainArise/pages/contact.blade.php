{{--
/**
 * The Villain Arise - Contact Page
 * 
 * Contact page with social links and contact information.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 */
--}}
@extends('theme.layouts::main')

@section('title', 'Contact Us')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-gray-950 via-villain-950 to-black py-20 md:py-32 border-b-4 border-villain-600 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(220,38,38,0.2)_0%,transparent_70%)]"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-black font-orbitron text-villain-500 mb-6 animate-pulse drop-shadow-[0_0_30px_rgba(239,68,68,0.6)]">
                📧 CONTACT US
            </h1>
            <p class="text-xl md:text-2xl text-gray-300">
                Let's connect. Reach out through any channel below.
            </p>
        </div>
    </div>
</section>

{{-- Contact Cards Section --}}
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            
            {{-- Email Card --}}
            <div class="bg-gradient-to-br from-gray-950 to-villain-950/30 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 text-center">
                <div class="text-6xl mb-6">📧</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Email</h3>
                <p class="text-gray-400 mb-4">Send us a message anytime</p>
                <a href="mailto:chardy.tsadiq02@gmail.com" class="text-villain-400 hover:text-villain-500 transition break-all">
                    chardy.tsadiq02@gmail.com
                </a>
            </div>
            
            {{-- Phone Card --}}
            <div class="bg-gradient-to-br from-gray-950 to-villain-950/30 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 text-center">
                <div class="text-6xl mb-6">📱</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Phone</h3>
                <p class="text-gray-400 mb-4">Call or text us directly</p>
                <p class="text-villain-400 text-xl">
                    +63 915 0388 448
                </p>
            </div>
            
            {{-- Location Card --}}
            <div class="bg-gradient-to-br from-gray-950 to-villain-950/30 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 text-center md:col-span-2 lg:col-span-1">
                <div class="text-6xl mb-6">📍</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Location</h3>
                <p class="text-gray-400 mb-4">Based in the Philippines</p>
                <p class="text-villain-400">
                    Philippines
                </p>
            </div>
            
        </div>
    </div>
</section>

{{-- Social Links Section --}}
<section class="py-20 bg-gray-950">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black font-orbitron text-center text-villain-500 mb-16 drop-shadow-[0_0_20px_rgba(239,68,68,0.4)]">
            Connect With Us
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            
            {{-- GitHub --}}
            <a href="https://github.com/sepiroth-x" target="_blank" class="group bg-gradient-to-r from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 flex items-center gap-4">
                <div class="text-5xl">💻</div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-1">GitHub</h3>
                    <p class="text-gray-400 group-hover:text-villain-400 transition">@sepiroth-x</p>
                </div>
                <svg class="w-6 h-6 text-villain-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            {{-- Facebook --}}
            <a href="https://www.facebook.com/sepirothx/" target="_blank" class="group bg-gradient-to-r from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 flex items-center gap-4">
                <div class="text-5xl">📘</div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-1">Facebook</h3>
                    <p class="text-gray-400 group-hover:text-villain-400 transition">@sepirothx</p>
                </div>
                <svg class="w-6 h-6 text-villain-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            {{-- Twitter / X --}}
            <a href="https://x.com/sepirothx000" target="_blank" class="group bg-gradient-to-r from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 flex items-center gap-4">
                <div class="text-5xl">🐦</div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-1">Twitter / X</h3>
                    <p class="text-gray-400 group-hover:text-villain-400 transition">@sepirothx000</p>
                </div>
                <svg class="w-6 h-6 text-villain-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
            {{-- LinkedIn --}}
            <a href="https://www.linkedin.com/in/richard-cebel-cupal" target="_blank" class="group bg-gradient-to-r from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-6 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20 flex items-center gap-4">
                <div class="text-5xl">💼</div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold font-orbitron text-villain-500 mb-1">LinkedIn</h3>
                    <p class="text-gray-400 group-hover:text-villain-400 transition">Richard Cebel Cupal</p>
                </div>
                <svg class="w-6 h-6 text-villain-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            
        </div>
    </div>
</section>

{{-- Call to Action Section --}}
<section class="py-20 bg-gradient-to-b from-gray-900 to-villain-950">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-black font-orbitron text-villain-500 mb-6">
            Ready to Get Started?
        </h2>
        <p class="text-xl text-gray-400 mb-12 max-w-2xl mx-auto">
            Whether you have a question, feedback, or just want to say hi—we'd love to hear from you!
        </p>
        
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="bg-villain-600 hover:bg-villain-700 text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 shadow-lg shadow-villain-500/30 hover:shadow-xl hover:shadow-villain-500/50 hover:-translate-y-1">
                ⭐ Star on GitHub
            </a>
            <a href="mailto:chardy.tsadiq02@gmail.com" class="bg-villain-600/20 border-2 border-villain-600 hover:bg-villain-600/30 text-villain-500 font-bold py-4 px-8 rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-villain-500/30">
                📧 Send Email
            </a>
        </div>
    </div>
</section>

@endsection
