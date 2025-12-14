@extends('theme.layouts::app')

@section('title', 'About VantaPress')

@section('content')
{{-- Hero Section --}}
<div class="hero">
    <div class="container">
        <h1 class="glow-text">About VantaPress</h1>
        <p style="font-size: 1.3rem;">The Story Behind the CMS</p>
    </div>
</div>

{{-- Origin Story --}}
<section class="features">
    <div class="container" style="max-width: 900px;">
        <h2 class="text-center glow-text">🎯 The Origin Story</h2>
        
        <div style="line-height: 1.8; font-size: 1.1rem; color: var(--text-color);">
            <p style="margin-bottom: 1.5rem;">
                VantaPress began as a <strong style="color: var(--color-primary);">school management system</strong> in 2024, built out of necessity when existing solutions were either too expensive or too complex for educational institutions in the Philippines.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                What started as a simple project to manage students, teachers, and class schedules evolved into something much bigger. While building the admin panel, I realized I was creating the foundation for a powerful content management system.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                The name <strong style="color: var(--color-primary);">"VantaPress"</strong> reflects the project's ambitious nature—combining "Vanta" (from Vantablack, the darkest substance known) with "Press" (inspired by WordPress). It represents the idea of starting from nothing and building something powerful.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                By December 2025, VantaPress had transformed into a production-ready CMS with FilamentPHP's beautiful admin panel, Laravel's robust architecture, and WordPress's ease of use—all while maintaining compatibility with cheap shared hosting.
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="section section-light">
    <div class="container">
        <div class="grid-2">
            <div class="card text-center">
                <div class="feature-icon">🚀</div>
                <h3 class="text-primary mb-2">Our Mission</h3>
                <p style="line-height: 1.8;">
                    To provide developers with a modern CMS that combines WordPress's simplicity with Laravel's power, making professional web development accessible without sacrificing code quality.
                </p>
            </div>
            
            
            <div class="card text-center">
                <div class="feature-icon">👁️</div>
                <h3 class="text-primary mb-2">Our Vision</h3>
                <p style="line-height: 1.8;">
                    To become the go-to CMS for developers who want the best of both worlds—rapid development with WordPress-style ease and enterprise-grade Laravel architecture.
                </p>
            </div>
            
            <div class="card text-center">
                <div class="feature-icon">💡</div>
                <h3 class="text-primary mb-2">Our Values</h3>
                <p style="line-height: 1.8;">
                    Open source, community-driven, accessible to all. We believe powerful tools should be free and available to developers everywhere, regardless of budget.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Creator Section --}}
<section class="features">
    <div class="container" style="max-width: 900px;">
        <h2 class="text-center glow-text">👨‍💻 Meet the Creator</h2>
        
        <div class="text-center mb-4">
            <div style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 4rem; box-shadow: 0 0 30px rgba(212, 0, 38, 0.5);">
                ⚡
            </div>
            <h3 style="font-size: 2rem; margin-bottom: 0.5rem; color: var(--text-color);">Sepiroth X Villainous</h3>
            <p style="font-size: 1.2rem; color: var(--color-primary); margin-bottom: 0.5rem;"><strong>Richard Cebel Cupal, LPT</strong></p>
            <p style="color: #9ca3af;">Full-Stack Developer | Educator | Open Source Advocate</p>
        </div>
        
        <div style="line-height: 1.8; font-size: 1.1rem; color: var(--text-color); margin-bottom: 2rem;">
            <p style="margin-bottom: 1.5rem;">
                A licensed professional teacher turned full-stack developer from the Philippines, I started VantaPress to bridge the gap between WordPress' accessibility and Laravel's sophistication.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                With a background in education, I understand the importance of making complex technology accessible. VantaPress reflects this philosophy—powerful enough for professionals, simple enough for anyone to deploy.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                When not coding, I'm teaching, mentoring aspiring developers, and contributing to the open-source community.
            </p>
        </div>
        
        <div class="card text-center">
            <h4 style="font-size: 1.5rem; margin-bottom: 1rem;" class="text-primary">📧 Get in Touch</h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                <p><strong>Email:</strong> <a href="mailto:chardy.tsadiq02@gmail.com" class="text-primary">chardy.tsadiq02@gmail.com</a></p>
                <p><strong>Mobile:</strong> +63 915 0388 448</p>
                <p><strong>GitHub:</strong> <a href="https://github.com/sepiroth-x" target="_blank" class="text-primary">@sepiroth-x</a></p>
                <p><strong>Facebook:</strong> <a href="https://www.facebook.com/sepirothx/" target="_blank" class="text-primary">@sepirothx</a></p>
                <p><strong>Twitter:</strong> <a href="https://x.com/sepirothx000" target="_blank" class="text-primary">@sepirothx000</a></p>
            </div>
        </div>
    </div>
</section>

{{-- Where We're Going --}}
<section class="section section-darker" style="color: white;">
    <div class="container" style="max-width: 900px;">
        <h2 class="text-center glow-text">🎯 Where We're Going</h2>
        
        <div style="line-height: 1.8; font-size: 1.1rem; margin-bottom: 2rem;">
            <p style="margin-bottom: 1.5rem; opacity: 0.9;">
                VantaPress is evolving from a simple CMS into a complete web application framework. Our roadmap includes:
            </p>
            
            <div class="grid-2" style="margin-top: 2rem;">
                <div class="card-dark">
                    <strong class="text-primary">🔌 Plugin Marketplace:</strong>
                    <p style="margin-top: 0.5rem;">A curated collection of .vpm modules for extending functionality</p>
                </div>
                <div class="card-dark">
                    <strong class="text-primary">🎨 Theme Store:</strong>
                    <p style="margin-top: 0.5rem;">Professional .vpt themes for every industry and use case</p>
                </div>
                <div class="card-dark">
                    <strong class="text-primary">🤝 Community Hub:</strong>
                    <p style="margin-top: 0.5rem;">Forums, documentation, and tutorials for the VantaPress ecosystem</p>
                </div>
                <div class="card-dark">
                    <strong class="text-primary">☁️ Cloud Hosting:</strong>
                    <p style="margin-top: 0.5rem;">One-click VantaPress deployments with managed hosting</p>
                </div>
                <div class="card-dark">
                    <strong class="text-primary">🛠️ CLI Tools:</strong>
                    <p style="margin-top: 0.5rem;">Command-line utilities for faster development workflows</p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">Want to Contribute?</h3>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem;">
                VantaPress is open source and community-driven. We welcome contributors!
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="btn btn-primary">⭐ Star on GitHub</a>
                <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank" class="btn btn-secondary">🐛 Report Issues</a>
                <a href="mailto:chardy.tsadiq02@gmail.com" class="btn btn-secondary">💬 Contact Us</a>
            </div>
        </div>
    </div>
</section>
@endsection
