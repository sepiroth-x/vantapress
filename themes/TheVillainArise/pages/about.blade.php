{{--
/**
 * The Villain Arise - About Page
 * 
 * About page showcasing VantaPress CMS origin story and mission.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 */
--}}
@extends('theme.layouts::main')

@section('title', 'About VantaPress')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-gray-950 via-villain-950 to-black py-20 md:py-32 border-b-4 border-villain-600 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(220,38,38,0.2)_0%,transparent_70%)]"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-black font-orbitron text-villain-500 mb-6 animate-pulse drop-shadow-[0_0_30px_rgba(239,68,68,0.6)]">
                ⚡ ABOUT VANTAPRESS
            </h1>
            <p class="text-xl md:text-2xl text-gray-300">
                The Story Behind the Darkness
            </p>
        </div>
    </div>
</section>

{{-- Origin Story Section --}}
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black font-orbitron text-center text-villain-500 mb-12 drop-shadow-[0_0_20px_rgba(239,68,68,0.4)]">
            🎯 The Origin Story
        </h2>
        <div class="max-w-4xl mx-auto space-y-6 text-lg text-gray-300 leading-relaxed">
            <p>
                VantaPress began as a <strong class="text-villain-500">school management system</strong> in 2024, built out of necessity when existing solutions were either too expensive or too complex for educational institutions in the Philippines.
            </p>
            
            <p>
                What started as a simple project to manage students, teachers, and class schedules evolved into something much bigger. While building the admin panel, I realized I was creating the foundation for a powerful content management system.
            </p>
            
            <p>
                The name <strong class="text-villain-500">"VantaPress"</strong> reflects the project's ambitious nature—combining "Vanta" (from Vantablack, the darkest substance known) with "Press" (inspired by WordPress). It represents the idea of starting from nothing and building something powerful.
            </p>
            
            <p>
                By December 2025, VantaPress had transformed into a production-ready CMS with FilamentPHP's beautiful admin panel, Laravel's robust architecture, and WordPress's ease of use—all while maintaining compatibility with cheap shared hosting.
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision Section --}}
<section class="py-20 bg-gray-950">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black font-orbitron text-center text-villain-500 mb-16 drop-shadow-[0_0_20px_rgba(239,68,68,0.4)]">
            Mission & Vision
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {{-- Mission Card --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="text-5xl mb-4">🚀</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Our Mission</h3>
                <p class="text-gray-400">
                    To provide developers with a modern CMS that combines WordPress's simplicity with Laravel's power, making professional web development accessible without sacrificing code quality.
                </p>
            </div>
            
            {{-- Vision Card --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="text-5xl mb-4">👁️</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Our Vision</h3>
                <p class="text-gray-400">
                    To become the go-to CMS for developers who want the best of both worlds—rapid development with WordPress-style ease and enterprise-grade Laravel architecture.
                </p>
            </div>
            
            {{-- Values Card --}}
            <div class="bg-gradient-to-br from-gray-900 to-gray-950 border-2 border-villain-600/30 rounded-lg p-8 hover:border-villain-500 transition-all duration-300 hover:shadow-xl hover:shadow-villain-500/20">
                <div class="text-5xl mb-4">💡</div>
                <h3 class="text-2xl font-bold font-orbitron text-villain-500 mb-4">Our Values</h3>
                <p class="text-gray-400">
                    Open source, community-driven, accessible to all. We believe powerful tools should be free and available to developers everywhere, regardless of budget.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Creator Section --}}
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black font-orbitron text-center text-villain-500 mb-16 drop-shadow-[0_0_20px_rgba(239,68,68,0.4)]">
            👨‍💻 Meet the Creator
        </h2>
        <div class="max-w-4xl mx-auto text-center">
            <div class="text-7xl mb-6">⚡</div>
            <h3 class="text-3xl font-bold font-orbitron text-villain-500 mb-2">Sepiroth X Villainous</h3>
            <p class="text-xl text-gray-300 mb-2"><strong>Richard Cebel Cupal, LPT</strong></p>
            <p class="text-gray-500 mb-8">Full-Stack Developer | Educator | Open Source Advocate</p>
            
            <div class="text-left space-y-6 text-lg text-gray-300 mb-12">
                <p>
                    A licensed professional teacher turned full-stack developer from the Philippines, I started VantaPress to bridge the gap between WordPress's accessibility and Laravel's sophistication.
                </p>
                
                <p>
                    With a background in education, I understand the importance of making complex technology accessible. VantaPress reflects this philosophy—powerful enough for professionals, simple enough for anyone to deploy.
                </p>
                
                <p>
                    When not coding, I'm teaching, mentoring aspiring developers, and contributing to the open-source community.
                </p>
            </div>
            
            {{-- Contact Box --}}
            <div class="bg-gradient-to-br from-villain-950 to-gray-950 border-2 border-villain-600 rounded-lg p-8 shadow-xl shadow-villain-500/30">
                <h4 class="text-2xl font-bold font-orbitron text-villain-500 mb-6">📧 Get in Touch</h4>
                <div class="space-y-3 text-left">
                    <p class="text-gray-300"><strong>Email:</strong> <a href="mailto:chardy.tsadiq02@gmail.com" class="text-villain-400 hover:text-villain-500 transition">chardy.tsadiq02@gmail.com</a></p>
                    <p class="text-gray-300"><strong>Mobile:</strong> <span class="text-villain-400">+63 915 0388 448</span></p>
                    <p class="text-gray-300"><strong>GitHub:</strong> <a href="https://github.com/sepiroth-x" target="_blank" class="text-villain-400 hover:text-villain-500 transition">@sepiroth-x</a></p>
                    <p class="text-gray-300"><strong>Facebook:</strong> <a href="https://www.facebook.com/sepirothx/" target="_blank" class="text-villain-400 hover:text-villain-500 transition">@sepirothx</a></p>
                    <p class="text-gray-300"><strong>Twitter:</strong> <a href="https://x.com/sepirothx000" target="_blank" class="text-villain-400 hover:text-villain-500 transition">@sepirothx000</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Roadmap Section --}}
<section class="py-20 bg-gradient-to-b from-gray-950 to-villain-950">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black font-orbitron text-center text-villain-500 mb-8 drop-shadow-[0_0_20px_rgba(239,68,68,0.4)]">
            🎯 Where We're Going
        </h2>
        <p class="text-center text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
            VantaPress is evolving from a simple CMS into a complete web application framework. Our roadmap includes:
        </p>
        
        <ul class="space-y-6 max-w-4xl mx-auto mb-16">
            <li class="bg-villain-600/10 border-l-4 border-villain-600 p-6 rounded-r-lg hover:bg-villain-600/20 transition-all duration-300 hover:translate-x-2">
                <strong class="text-villain-500 text-xl">🔌 Plugin Marketplace:</strong>
                <span class="text-gray-300"> A curated collection of .vpm modules for extending functionality</span>
            </li>
            <li class="bg-villain-600/10 border-l-4 border-villain-600 p-6 rounded-r-lg hover:bg-villain-600/20 transition-all duration-300 hover:translate-x-2">
                <strong class="text-villain-500 text-xl">🎨 Theme Store:</strong>
                <span class="text-gray-300"> Professional .vpt themes for every industry and use case</span>
            </li>
            <li class="bg-villain-600/10 border-l-4 border-villain-600 p-6 rounded-r-lg hover:bg-villain-600/20 transition-all duration-300 hover:translate-x-2">
                <strong class="text-villain-500 text-xl">🤝 Community Hub:</strong>
                <span class="text-gray-300"> Forums, documentation, and tutorials for the VantaPress ecosystem</span>
            </li>
            <li class="bg-villain-600/10 border-l-4 border-villain-600 p-6 rounded-r-lg hover:bg-villain-600/20 transition-all duration-300 hover:translate-x-2">
                <strong class="text-villain-500 text-xl">☁️ Cloud Hosting:</strong>
                <span class="text-gray-300"> One-click VantaPress deployments with managed hosting</span>
            </li>
            <li class="bg-villain-600/10 border-l-4 border-villain-600 p-6 rounded-r-lg hover:bg-villain-600/20 transition-all duration-300 hover:translate-x-2">
                <strong class="text-villain-500 text-xl">🛠️ CLI Tools:</strong>
                <span class="text-gray-300"> Command-line utilities for faster development workflows</span>
            </li>
        </ul>
        
        <div class="text-center">
            <h3 class="text-3xl font-bold font-orbitron text-villain-500 mb-4">Want to Contribute?</h3>
            <p class="text-xl text-gray-400 mb-8">
                VantaPress is open source and community-driven. We welcome contributors!
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="bg-villain-600 hover:bg-villain-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 shadow-lg shadow-villain-500/30 hover:shadow-xl hover:shadow-villain-500/50 hover:-translate-y-1">
                    ⭐ Star on GitHub
                </a>
                <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank" class="bg-villain-600/20 border-2 border-villain-600 hover:bg-villain-600/30 text-villain-500 font-bold py-3 px-8 rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-villain-500/30">
                    🐛 Report Issues
                </a>
                <a href="mailto:chardy.tsadiq02@gmail.com" class="bg-villain-600/20 border-2 border-villain-600 hover:bg-villain-600/30 text-villain-500 font-bold py-3 px-8 rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-villain-500/30">
                    💬 Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
