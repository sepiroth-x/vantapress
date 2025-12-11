<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - WordPress-Inspired CMS Built with Laravel</title>
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

        .install-notice {
            background: linear-gradient(135deg, rgba(212, 0, 38, 0.1), rgba(106, 15, 145, 0.1));
            border: 2px solid var(--crimson-villain);
            border-radius: 16px;
            padding: 32px;
            margin: 40px auto;
            max-width: 800px;
            text-align: center;
        }

        .install-notice h2 {
            font-size: 28px;
            margin-bottom: 16px;
            color: var(--pure-white);
        }

        .install-notice p {
            font-size: 16px;
            color: var(--steel-gray);
            margin-bottom: 24px;
            line-height: 1.8;
        }

        footer {
            padding: 60px 0 40px;
            border-top: 1px solid rgba(136, 138, 143, 0.1);
            margin-top: 80px;
        }

        .footer-bottom {
            text-align: center;
            color: var(--steel-gray);
            font-size: 14px;
        }

        .footer-bottom strong {
            color: var(--pure-white);
        }

        @media (max-width: 768px) {
            .hero {
                padding: 60px 0 40px;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 15px;
            }
        }

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
                <div class="logo">
                    <div class="logo-icon">⚡</div>
                    <span>VantaPress</span>
                </div>
                <nav class="nav-links">
                    <a href="https://github.com/sepirothx/vantapress" target="_blank">GitHub</a>
                    <a href="https://github.com/sepirothx/vantapress/blob/main/README.md" target="_blank">Documentation</a>
                </nav>
            </div>
        </header>

        <section class="hero animate-in">
            <p class="tagline">Open Source · MIT Licensed</p>
            <h1>VantaPress</h1>
            <p class="subtitle">WordPress Philosophy, Laravel Power</p>

            <div class="install-notice">
                <h2>🚀 Ready to Install</h2>
                <p>
                    Welcome! VantaPress is not yet installed on this server.<br>
                    Click the button below to start the quick 6-step installation wizard.<br>
                    <strong>No terminal, no build tools, no complexity.</strong>
                </p>
                <a href="/install.php" class="btn btn-primary" style="font-size: 18px; padding: 20px 40px;">
                    🚀 Start Installation
                </a>
            </div>
            
            <div class="cta-buttons" style="margin-top: 40px;">
                <a href="https://github.com/sepirothx/vantapress" class="btn btn-secondary" target="_blank">
                    📚 View Documentation
                </a>
                <a href="/diagnose.php" class="btn btn-secondary">
                    🔍 Run Diagnostics
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

        <footer>
            <div class="footer-bottom">
                <strong>VantaPress</strong> - WordPress Philosophy, Laravel Power<br>
                Created by <strong>Sepirothx</strong> (Richard Cebel Cupal, LPT)<br>
                Powered by Laravel 11 & FilamentPHP 3.3<br>
                Copyright © 2025 · MIT Licensed · Open Source
            </div>
        </footer>
    </div>
</body>
</html>
