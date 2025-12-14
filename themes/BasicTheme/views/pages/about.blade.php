@extends('theme.layouts::app')

@section('title', 'About VantaPress')

@section('content')
{{-- Hero Section --}}
<div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 60px 20px; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">About VantaPress</h1>
        <p style="font-size: 1.3rem; opacity: 0.95;">The Story Behind the CMS</p>
    </div>
</div>

{{-- Origin Story --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626; text-align: center;">🎯 The Origin Story</h2>
        
        <div style="line-height: 1.8; font-size: 1.1rem; color: #374151;">
            <p style="margin-bottom: 1.5rem;">
                VantaPress began as a <strong>school management system</strong> in 2024, built out of necessity when existing solutions were either too expensive or too complex for educational institutions in the Philippines.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                What started as a simple project to manage students, teachers, and class schedules evolved into something much bigger. While building the admin panel, I realized I was creating the foundation for a powerful content management system.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                The name <strong>"VantaPress"</strong> reflects the project's ambitious nature—combining "Vanta" (from Vantablack, the darkest substance known) with "Press" (inspired by WordPress). It represents the idea of starting from nothing and building something powerful.
            </p>
            
            <p style="margin-bottom: 1.5rem;">
                By December 2025, VantaPress had transformed into a production-ready CMS with FilamentPHP's beautiful admin panel, Laravel's robust architecture, and WordPress's ease of use—all while maintaining compatibility with cheap shared hosting.
            </p>
        </div>
    </div>
</div>

{{-- Mission & Vision --}}
<div style="padding: 60px 20px; background: #f9fafb;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🚀</div>
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: #dc2626;">Our Mission</h3>
                <p style="color: #666; line-height: 1.8;">
                    To provide developers with a modern CMS that combines WordPress's simplicity with Laravel's power, making professional web development accessible without sacrificing code quality.
                </p>
            </div>
            
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">👁️</div>
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: #dc2626;">Our Vision</h3>
                <p style="color: #666; line-height: 1.8;">
                    To become the go-to CMS for developers who want the best of both worlds—rapid development with WordPress-style ease and enterprise-grade Laravel architecture.
                </p>
            </div>
            
            <div style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💡</div>
                <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: #dc2626;">Our Values</h3>
                <p style="color: #666; line-height: 1.8;">
                    Open source, community-driven, accessible to all. We believe powerful tools should be free and available to developers everywhere, regardless of budget.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Creator Section --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626; text-align: center;">👨‍💻 Meet the Creator</h2>
        
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #dc2626, #991b1b); margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: white;">
                ⚡
            </div>
            <h3 style="font-size: 2rem; margin-bottom: 0.5rem; color: #1f2937;">Sepiroth X Villainous</h3>
            <p style="font-size: 1.2rem; color: #666; margin-bottom: 0.5rem;"><strong>Richard Cebel Cupal, LPT</strong></p>
            <p style="color: #999;">Full-Stack Developer | Educator | Open Source Advocate</p>
        </div>
        
        <div style="line-height: 1.8; font-size: 1.1rem; color: #374151; margin-bottom: 2rem;">
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
        
        <div style="text-align: center; padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
            <h4 style="font-size: 1.5rem; margin-bottom: 1rem; color: #dc2626;">📧 Get in Touch</h4>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                <p><strong>Email:</strong> <a href="mailto:chardy.tsadiq02@gmail.com" style="color: #dc2626;">chardy.tsadiq02@gmail.com</a></p>
                <p><strong>Mobile:</strong> +63 915 0388 448</p>
                <p><strong>GitHub:</strong> <a href="https://github.com/sepiroth-x" target="_blank" style="color: #dc2626;">@sepiroth-x</a></p>
                <p><strong>Facebook:</strong> <a href="https://www.facebook.com/sepirothx/" target="_blank" style="color: #dc2626;">@sepirothx</a></p>
                <p><strong>Twitter:</strong> <a href="https://x.com/sepirothx000" target="_blank" style="color: #dc2626;">@sepirothx000</a></p>
            </div>
        </div>
    </div>
</div>

{{-- Where We're Going --}}
<div style="padding: 60px 20px; background: #1f2937; color: white;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem; text-align: center;">🎯 Where We're Going</h2>
        
        <div style="line-height: 1.8; font-size: 1.1rem; margin-bottom: 2rem;">
            <p style="margin-bottom: 1.5rem; opacity: 0.9;">
                VantaPress is evolving from a simple CMS into a complete web application framework. Our roadmap includes:
            </p>
            
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 1rem; background: rgba(255,255,255,0.1); margin-bottom: 1rem; border-radius: 8px;">
                    <strong>🔌 Plugin Marketplace:</strong> A curated collection of .vpm modules for extending functionality
                </li>
                <li style="padding: 1rem; background: rgba(255,255,255,0.1); margin-bottom: 1rem; border-radius: 8px;">
                    <strong>🎨 Theme Store:</strong> Professional .vpt themes for every industry and use case
                </li>
                <li style="padding: 1rem; background: rgba(255,255,255,0.1); margin-bottom: 1rem; border-radius: 8px;">
                    <strong>🤝 Community Hub:</strong> Forums, documentation, and tutorials for the VantaPress ecosystem
                </li>
                <li style="padding: 1rem; background: rgba(255,255,255,0.1); margin-bottom: 1rem; border-radius: 8px;">
                    <strong>☁️ Cloud Hosting:</strong> One-click VantaPress deployments with managed hosting
                </li>
                <li style="padding: 1rem; background: rgba(255,255,255,0.1); margin-bottom: 1rem; border-radius: 8px;">
                    <strong>🛠️ CLI Tools:</strong> Command-line utilities for faster development workflows
                </li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 3rem;">
            <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">Want to Contribute?</h3>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem;">
                VantaPress is open source and community-driven. We welcome contributors!
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://github.com/sepiroth-x/vantapress" target="_blank" style="background: white; color: #1f2937; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700;">⭐ Star on GitHub</a>
                <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; border: 2px solid white;">🐛 Report Issues</a>
                <a href="mailto:chardy.tsadiq02@gmail.com" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; border: 2px solid white;">💬 Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
