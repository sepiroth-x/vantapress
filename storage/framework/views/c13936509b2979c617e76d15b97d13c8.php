<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - WordPress-Inspired CMS Built with Laravel</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('images/favicon.svg')); ?>">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        :root {
            --vanta-black: #050505;
            --deep-obsidian: #0A0A0A;
            --crimson-villain: #D40026;
            --dark-violet: #6A0F91;
            --steel-gray: #888A8F;
            --pure-white: #FFFFFF;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--vanta-black);
            color: var(--pure-white);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Animated gradient background */
        .bg-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(212, 0, 38, 0.15), transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(106, 15, 145, 0.15), transparent 50%),
                var(--vanta-black);
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            padding: 30px 0;
            border-bottom: 1px solid rgba(136, 138, 143, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
            font-weight: 800;
            color: var(--pure-white);
            text-decoration: none;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--crimson-villain), var(--dark-violet));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: var(--steel-gray);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 15px;
        }

        .nav-links a:hover {
            color: var(--pure-white);
        }

        /* Hero Section */
        .hero {
            padding: 120px 0 80px;
            text-align: center;
        }

        .hero h1 {
            font-size: clamp(48px, 8vw, 80px);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--steel-gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero .subtitle {
            font-size: clamp(20px, 3vw, 28px);
            color: var(--steel-gray);
            margin-bottom: 16px;
            font-weight: 400;
        }

        .hero .tagline {
            font-size: 18px;
            color: var(--crimson-villain);
            font-weight: 600;
            margin-bottom: 48px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* CTA Buttons */
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 60px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: var(--crimson-villain);
            color: var(--pure-white);
            box-shadow: 0 8px 32px rgba(212, 0, 38, 0.3);
        }

        .btn-primary:hover {
            background: #b0001f;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(212, 0, 38, 0.5);
        }

        .btn-secondary {
            background: var(--deep-obsidian);
            color: var(--pure-white);
            border: 2px solid rgba(136, 138, 143, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(10, 10, 10, 0.8);
            border-color: var(--dark-violet);
            transform: translateY(-2px);
        }

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 80px;
        }

        .stat-card {
            background: var(--deep-obsidian);
            padding: 24px;
            border-radius: 16px;
            border: 1px solid rgba(136, 138, 143, 0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--crimson-villain);
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--steel-gray);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Features Grid */
        .features {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--pure-white);
        }

        .section-subtitle {
            text-align: center;
            font-size: 18px;
            color: var(--steel-gray);
            margin-bottom: 60px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--deep-obsidian);
            padding: 32px;
            border-radius: 16px;
            border: 1px solid rgba(136, 138, 143, 0.1);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--crimson-villain), var(--dark-violet));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            border-color: var(--dark-violet);
            transform: translateY(-4px);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 16px;
            display: inline-block;
        }

        .feature-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--pure-white);
        }

        .feature-card p {
            color: var(--steel-gray);
            font-size: 15px;
            line-height: 1.7;
        }

        /* Use Cases */
        .use-cases {
            padding: 80px 0;
            background: var(--deep-obsidian);
            margin: 80px -20px;
        }

        .use-cases-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 40px;
        }

        .use-case {
            padding: 24px;
            border-radius: 12px;
            background: var(--vanta-black);
            border: 1px solid rgba(136, 138, 143, 0.1);
        }

        .use-case-emoji {
            font-size: 32px;
            margin-bottom: 12px;
            display: block;
        }

        .use-case h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--pure-white);
        }

        .use-case p {
            color: var(--steel-gray);
            font-size: 14px;
        }

        /* Comparison Table */
        .comparison {
            padding: 80px 0;
        }

        .comparison-table {
            max-width: 900px;
            margin: 0 auto;
            background: var(--deep-obsidian);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(136, 138, 143, 0.1);
        }

        .comparison-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            border-bottom: 1px solid rgba(136, 138, 143, 0.1);
        }

        .comparison-row:last-child {
            border-bottom: none;
        }

        .comparison-header {
            background: var(--vanta-black);
            font-weight: 700;
            color: var(--pure-white);
        }

        .comparison-cell {
            padding: 20px;
            text-align: center;
            color: var(--steel-gray);
            font-size: 14px;
        }

        .comparison-cell:first-child {
            text-align: left;
            font-weight: 600;
            color: var(--pure-white);
        }

        .comparison-highlight {
            background: rgba(212, 0, 38, 0.1);
            color: var(--crimson-villain);
            font-weight: 600;
        }

        /* Footer */
        footer {
            padding: 60px 0 40px;
            border-top: 1px solid rgba(136, 138, 143, 0.1);
            margin-top: 80px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--pure-white);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 12px;
        }

        .footer-section a {
            color: var(--steel-gray);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--crimson-villain);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(136, 138, 143, 0.1);
            color: var(--steel-gray);
            font-size: 14px;
        }

        .footer-bottom strong {
            color: var(--pure-white);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero {
                padding: 60px 0 40px;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 15px;
            }

            .comparison-row {
                grid-template-columns: 1fr;
            }

            .comparison-cell {
                text-align: left;
                border-bottom: 1px solid rgba(136, 138, 143, 0.05);
            }

            .comparison-cell:last-child {
                border-bottom: none;
            }
        }

        /* Scroll animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
<body>
    <div class="bg-gradient"></div>
    
    <div class="container">
        <header>
            <div class="header-content">
                <a href="/" class="logo">
                    <img src="<?php echo e(asset('images/vantapress-icon.svg')); ?>" alt="VantaPress" style="width: 40px; height: 40px;">
                    <span>VantaPress</span>
                </a>
                <nav class="nav-links">
                    <a href="#features">Features</a>
                    <a href="#use-cases">Use Cases</a>
                    <a href="#comparison">Comparison</a>
                    <a href="https://github.com/sepirothx/vantapress" target="_blank">GitHub</a>
                    <a href="/admin" class="btn btn-secondary" style="padding: 10px 24px; font-size: 14px;">Admin Panel</a>
                </nav>
            </div>
        </header>

        <section class="hero animate-in">
            <p class="tagline">Open Source · MIT Licensed</p>
            <h1>VantaPress</h1>
            <p class="subtitle">WordPress Philosophy, Laravel Power</p>
            
            <div class="cta-buttons">
                <a href="/install.php" class="btn btn-primary">
                    🚀 Quick Install
                </a>
                <a href="https://github.com/sepirothx/vantapress" class="btn btn-secondary" target="_blank">
                    📚 Documentation
                </a>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-value">21</div>
                    <div class="stat-label">Database Tables</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Build Required</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">6</div>
                    <div class="stat-label">Step Installer</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">100%</div>
                    <div class="stat-label">Open Source</div>
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <h2 class="section-title">Why Choose VantaPress?</h2>
            <p class="section-subtitle">Modern CMS architecture with developer-friendly tools</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>WordPress Simplicity</h3>
                    <p>Upload via FTP, run web installer, and start building. No terminal, no build tools, no complexity.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Laravel Performance</h3>
                    <p>Built on Laravel 11 with Eloquent ORM, modern PHP 8.2+, and PSR coding standards.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💎</div>
                    <h3>FilamentPHP Admin</h3>
                    <p>Beautiful, responsive admin panel out-of-the-box. No custom dashboard development needed.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>Shared Hosting Ready</h3>
                    <p>Works on cheap shared hosting (iFastNet, HostGator, Bluehost). No VPS or special server requirements.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>Modular Architecture</h3>
                    <p>MVC pattern, dependency injection, testable code. Build features the right way.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Secure by Default</h3>
                    <p>Laravel security features, CSRF protection, bcrypt password hashing, and SQL injection prevention.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Mobile Responsive</h3>
                    <p>Admin panel and frontend work seamlessly on desktop, tablet, and mobile devices.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🚀</div>
                    <h3>Zero Build Tools</h3>
                    <p>No Node.js, npm, Vite, or webpack required. Assets load directly from FilamentPHP.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Relational Data</h3>
                    <p>Complex relationships handled elegantly with Eloquent. No messy SQL or data structure issues.</p>
                </div>
            </div>
        </section>

        <section class="use-cases" id="use-cases">
            <div class="container">
                <h2 class="section-title">Endless Possibilities</h2>
                <p class="section-subtitle">VantaPress adapts to your needs</p>
                
                <div class="use-cases-grid">
                    <div class="use-case">
                        <span class="use-case-emoji">🏫</span>
                        <h4>School Management</h4>
                        <p>Students, teachers, courses, enrollments, grades, schedules</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🏢</span>
                        <h4>Business Portal</h4>
                        <p>Employees, departments, projects, tasks, time tracking</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🛒</span>
                        <h4>E-Commerce</h4>
                        <p>Products, categories, orders, customers, inventory</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">📚</span>
                        <h4>Content Platform</h4>
                        <p>Articles, authors, categories, comments, SEO</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🏥</span>
                        <h4>Healthcare System</h4>
                        <p>Patients, doctors, appointments, records, billing</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🎨</span>
                        <h4>Portfolio/Agency</h4>
                        <p>Projects, clients, testimonials, team members</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🏠</span>
                        <h4>Real Estate</h4>
                        <p>Properties, agents, listings, inquiries, bookings</p>
                    </div>
                    <div class="use-case">
                        <span class="use-case-emoji">🎓</span>
                        <h4>Online Learning</h4>
                        <p>Courses, lessons, quizzes, certificates, progress</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="comparison" id="comparison">
            <h2 class="section-title">How VantaPress Compares</h2>
            <p class="section-subtitle">The best of WordPress and Laravel combined</p>
            
            <div class="comparison-table">
                <div class="comparison-row comparison-header">
                    <div class="comparison-cell">Feature</div>
                    <div class="comparison-cell">WordPress</div>
                    <div class="comparison-cell comparison-highlight">VantaPress</div>
                    <div class="comparison-cell">Laravel</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Easy Setup</div>
                    <div class="comparison-cell">✅ Yes</div>
                    <div class="comparison-cell comparison-highlight">✅ Yes</div>
                    <div class="comparison-cell">❌ Complex</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Modern PHP</div>
                    <div class="comparison-cell">❌ Legacy</div>
                    <div class="comparison-cell comparison-highlight">✅ 8.2+</div>
                    <div class="comparison-cell">✅ 8.2+</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Admin Panel</div>
                    <div class="comparison-cell">✅ Built-in</div>
                    <div class="comparison-cell comparison-highlight">✅ FilamentPHP</div>
                    <div class="comparison-cell">❌ Build it</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Database ORM</div>
                    <div class="comparison-cell">⚠️ wpdb</div>
                    <div class="comparison-cell comparison-highlight">✅ Eloquent</div>
                    <div class="comparison-cell">✅ Eloquent</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Shared Hosting</div>
                    <div class="comparison-cell">✅ Works</div>
                    <div class="comparison-cell comparison-highlight">✅ Optimized</div>
                    <div class="comparison-cell">⚠️ Limited</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Code Quality</div>
                    <div class="comparison-cell">⚠️ Mixed</div>
                    <div class="comparison-cell comparison-highlight">✅ PSR</div>
                    <div class="comparison-cell">✅ PSR</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-cell">Build Tools</div>
                    <div class="comparison-cell">❌ Plugins</div>
                    <div class="comparison-cell comparison-highlight">✅ None needed</div>
                    <div class="comparison-cell">⚠️ Vite/npm</div>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h4>VantaPress</h4>
                    <p style="color: var(--steel-gray); font-size: 14px; line-height: 1.7;">
                        A WordPress-inspired CMS built with Laravel. Open source, MIT licensed, and built for developers who want simplicity with power.
                    </p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/install.php">Install VantaPress</a></li>
                        <li><a href="/admin">Admin Panel</a></li>
                        <li><a href="https://github.com/sepirothx/vantapress" target="_blank">GitHub Repository</a></li>
                        <li><a href="https://github.com/sepirothx/vantapress/blob/main/README.md" target="_blank">Documentation</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="https://laravel.com" target="_blank">Laravel Framework</a></li>
                        <li><a href="https://filamentphp.com" target="_blank">FilamentPHP</a></li>
                        <li><a href="https://github.com/sepirothx/vantapress/blob/main/DEPLOYMENT_GUIDE.md" target="_blank">Deployment Guide</a></li>
                        <li><a href="https://github.com/sepirothx/vantapress/blob/main/LICENSE" target="_blank">MIT License</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect</h4>
                    <ul>
                        <li><a href="mailto:chardy.tsadiq02@gmail.com">Email Support</a></li>
                        <li><a href="tel:+639150388448">+63 915 0388 448</a></li>
                        <li><a href="https://github.com/sepirothx" target="_blank">GitHub Profile</a></li>
                        <li><a href="https://github.com/sepirothx/vantapress/issues" target="_blank">Report Issues</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <strong>VantaPress</strong> - WordPress Philosophy, Laravel Power<br>
                Created by <strong>Sepirothx</strong> (Richard Cebel Cupal, LPT)<br>
                Powered by Laravel <?php echo e(app()->version()); ?> & FilamentPHP 3.3<br>
                Copyright © 2025 · MIT Licensed · Open Source
            </div>
        </footer>
    </div>
</body>
</html>
<?php /**PATH D:\0. Web Development\4. Laravel Development\vantapress-1.0.13-complete\resources\views/welcome.blade.php ENDPATH**/ ?>