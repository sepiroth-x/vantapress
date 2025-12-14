<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About VantaPress - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0a0a0a;
            color: #e0e0e0;
            line-height: 1.6;
        }
        
        .hero {
            background: linear-gradient(135deg, #1a0000 0%, #330000 50%, #000000 100%);
            padding: 80px 20px;
            text-align: center;
            border-bottom: 3px solid #cc0000;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(204, 0, 0, 0.1) 0%, transparent 70%);
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 900;
            color: #ff0000;
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.5), 0 0 40px rgba(255, 0, 0, 0.3);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .hero p {
            font-size: 1.5rem;
            color: #ccc;
            position: relative;
            z-index: 1;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .section {
            padding: 80px 20px;
        }
        
        .section-dark {
            background: #111;
        }
        
        .section-darker {
            background: #0a0a0a;
        }
        
        .section-title {
            font-size: 3rem;
            color: #ff0000;
            text-align: center;
            margin-bottom: 3rem;
            text-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }
        
        .origin-story {
            max-width: 900px;
            margin: 0 auto;
            font-size: 1.2rem;
            line-height: 1.9;
            color: #ccc;
        }
        
        .origin-story p {
            margin-bottom: 2rem;
        }
        
        .origin-story strong {
            color: #ff0000;
        }
        
        .mission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }
        
        .mission-card {
            background: linear-gradient(135deg, #1a0000 0%, #0a0a0a 100%);
            padding: 3rem;
            border-radius: 16px;
            border: 2px solid #330000;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .mission-card:hover {
            border-color: #ff0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
            transform: translateY(-5px);
        }
        
        .mission-card .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.4));
        }
        
        .mission-card h3 {
            font-size: 2rem;
            color: #ff0000;
            margin-bottom: 1rem;
        }
        
        .mission-card p {
            color: #aaa;
            line-height: 1.8;
        }
        
        .creator-section {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }
        
        .creator-avatar {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff0000, #cc0000);
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            border: 4px solid #330000;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.4);
        }
        
        .creator-section h3 {
            font-size: 2.5rem;
            color: #ff0000;
            margin-bottom: 0.5rem;
        }
        
        .creator-section .name {
            font-size: 1.5rem;
            color: #ccc;
            margin-bottom: 0.5rem;
        }
        
        .creator-section .title {
            color: #888;
            margin-bottom: 2rem;
        }
        
        .creator-bio {
            text-align: left;
            max-width: 800px;
            margin: 0 auto 3rem;
            line-height: 1.9;
            color: #bbb;
            font-size: 1.1rem;
        }
        
        .creator-bio p {
            margin-bottom: 1.5rem;
        }
        
        .contact-box {
            background: linear-gradient(135deg, #1a0000, #0a0a0a);
            padding: 3rem;
            border-radius: 16px;
            border: 2px solid #ff0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
        }
        
        .contact-box h4 {
            font-size: 2rem;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
        
        .contact-box p {
            margin-bottom: 0.8rem;
            font-size: 1.1rem;
        }
        
        .contact-box a {
            color: #ff6666;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .contact-box a:hover {
            color: #ff0000;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        .roadmap {
            background: linear-gradient(180deg, #0a0a0a 0%, #1a0000 100%);
            padding: 60px 20px;
        }
        
        .roadmap-list {
            list-style: none;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .roadmap-list li {
            background: rgba(255, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #ff0000;
            transition: all 0.3s;
        }
        
        .roadmap-list li:hover {
            background: rgba(255, 0, 0, 0.2);
            transform: translateX(10px);
        }
        
        .roadmap-list strong {
            color: #ff0000;
            font-size: 1.2rem;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }
        
        .btn {
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-primary {
            background: #ff0000;
            color: #fff;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
        }
        
        .btn-primary:hover {
            background: #cc0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.6);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: rgba(255, 0, 0, 0.2);
            color: #ff0000;
            border: 2px solid #ff0000;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 0, 0, 0.3);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .mission-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('theme.partials::header')

    <div class="hero">
        <h1>⚡ ABOUT VANTAPRESS</h1>
        <p>The Story Behind the Darkness</p>
    </div>

    <div class="section section-dark">
        <div class="container">
            <h2 class="section-title">🎯 The Origin Story</h2>
            <div class="origin-story">
                <p>
                    VantaPress began as a <strong>school management system</strong> in 2024, built out of necessity when existing solutions were either too expensive or too complex for educational institutions in the Philippines.
                </p>
                
                <p>
                    What started as a simple project to manage students, teachers, and class schedules evolved into something much bigger. While building the admin panel, I realized I was creating the foundation for a powerful content management system.
                </p>
                
                <p>
                    The name <strong>"VantaPress"</strong> reflects the project's ambitious nature—combining "Vanta" (from Vantablack, the darkest substance known) with "Press" (inspired by WordPress). It represents the idea of starting from nothing and building something powerful.
                </p>
                
                <p>
                    By December 2025, VantaPress had transformed into a production-ready CMS with FilamentPHP's beautiful admin panel, Laravel's robust architecture, and WordPress's ease of use—all while maintaining compatibility with cheap shared hosting.
                </p>
            </div>
        </div>
    </div>

    <div class="section section-darker">
        <div class="container">
            <h2 class="section-title">Mission & Vision</h2>
            <div class="mission-grid">
                <div class="mission-card">
                    <div class="icon">🚀</div>
                    <h3>Our Mission</h3>
                    <p>
                        To provide developers with a modern CMS that combines WordPress's simplicity with Laravel's power, making professional web development accessible without sacrificing code quality.
                    </p>
                </div>
                
                <div class="mission-card">
                    <div class="icon">👁️</div>
                    <h3>Our Vision</h3>
                    <p>
                        To become the go-to CMS for developers who want the best of both worlds—rapid development with WordPress-style ease and enterprise-grade Laravel architecture.
                    </p>
                </div>
                
                <div class="mission-card">
                    <div class="icon">💡</div>
                    <h3>Our Values</h3>
                    <p>
                        Open source, community-driven, accessible to all. We believe powerful tools should be free and available to developers everywhere, regardless of budget.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-dark">
        <div class="container">
            <h2 class="section-title">👨‍💻 Meet the Creator</h2>
            <div class="creator-section">
                <div class="creator-avatar">⚡</div>
                <h3>Sepiroth X Villainous</h3>
                <p class="name"><strong>Richard Cebel Cupal, LPT</strong></p>
                <p class="title">Full-Stack Developer | Educator | Open Source Advocate</p>
                
                <div class="creator-bio">
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
                
                <div class="contact-box">
                    <h4>📧 Get in Touch</h4>
                    <p><strong>Email:</strong> <a href="mailto:chardy.tsadiq02@gmail.com">chardy.tsadiq02@gmail.com</a></p>
                    <p><strong>Mobile:</strong> +63 915 0388 448</p>
                    <p><strong>GitHub:</strong> <a href="https://github.com/sepiroth-x" target="_blank">@sepiroth-x</a></p>
                    <p><strong>Facebook:</strong> <a href="https://www.facebook.com/sepirothx/" target="_blank">@sepirothx</a></p>
                    <p><strong>Twitter:</strong> <a href="https://x.com/sepirothx000" target="_blank">@sepirothx000</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="roadmap">
        <div class="container">
            <h2 class="section-title">🎯 Where We're Going</h2>
            <p style="text-align: center; font-size: 1.2rem; color: #aaa; margin-bottom: 3rem; max-width: 800px; margin-left: auto; margin-right: auto;">
                VantaPress is evolving from a simple CMS into a complete web application framework. Our roadmap includes:
            </p>
            
            <ul class="roadmap-list">
                <li>
                    <strong>🔌 Plugin Marketplace:</strong> A curated collection of .vpm modules for extending functionality
                </li>
                <li>
                    <strong>🎨 Theme Store:</strong> Professional .vpt themes for every industry and use case
                </li>
                <li>
                    <strong>🤝 Community Hub:</strong> Forums, documentation, and tutorials for the VantaPress ecosystem
                </li>
                <li>
                    <strong>☁️ Cloud Hosting:</strong> One-click VantaPress deployments with managed hosting
                </li>
                <li>
                    <strong>🛠️ CLI Tools:</strong> Command-line utilities for faster development workflows
                </li>
            </ul>
            
            <div style="text-align: center; margin-top: 4rem;">
                <h3 style="font-size: 2rem; color: #ff0000; margin-bottom: 1rem;">Want to Contribute?</h3>
                <p style="font-size: 1.2rem; color: #aaa; margin-bottom: 2rem;">
                    VantaPress is open source and community-driven. We welcome contributors!
                </p>
                <div class="cta-buttons">
                    <a href="https://github.com/sepiroth-x/vantapress" target="_blank" class="btn btn-primary">⭐ Star on GitHub</a>
                    <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank" class="btn btn-secondary">🐛 Report Issues</a>
                    <a href="mailto:chardy.tsadiq02@gmail.com" class="btn btn-secondary">💬 Contact Us</a>
                </div>
            </div>
        </div>
    </div>

    @include('theme.partials::footer')
</body>
</html>
