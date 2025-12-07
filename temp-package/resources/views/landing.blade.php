<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - WordPress-Inspired CMS Built with Laravel</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --vanta-black: #050505;
            --deep-obsidian: #0A0A0A;
            --crimson-villain: #D40026;
            --dark-violet: #6A0F91;
            --steel-gray: #888A8F;
            --pure-white: #FFFFFF;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: var(--vanta-black);
            color: var(--pure-white);
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, var(--deep-obsidian) 0%, var(--vanta-black) 100%);
            color: var(--pure-white);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(212, 0, 38, 0.2);
            border-bottom: 1px solid rgba(136, 138, 143, 0.1);
        }
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--crimson-villain), var(--dark-violet));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: var(--pure-white);
        }   color: #8B4513;
        }
        .school-name h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .school-name p {
            font-size: 12px;
            opacity: 0.9;
        }
        /* Navigation */
        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }
        nav a {
            color: var(--steel-gray);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav a:hover {
            color: var(--crimson-villain);
        }   opacity: 0.8;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(5, 5, 5, 0.95), rgba(10, 10, 10, 0.95)),
                        radial-gradient(ellipse at 50% 50%, rgba(212, 0, 38, 0.2), transparent 70%);
            background-size: cover;
            color: var(--pure-white);
            padding: 100px 20px;
            text-align: center;
        }
        .hero h2 {
            font-size: 48px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--steel-gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            color: var(--steel-gray);
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: var(--crimson-villain);
            color: var(--pure-white);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 32px rgba(212, 0, 38, 0.3);
        /* Features */
        .features {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        .feature-card {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            background: var(--deep-obsidian);
            border: 1px solid rgba(136, 138, 143, 0.1);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            transition: all 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--dark-violet);
            box-shadow: 0 8px 32px rgba(212, 0, 38, 0.2);
        }
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .feature-card h3 {
        /* Quick Links */
        .quick-links {
            background: var(--deep-obsidian);
            padding: 60px 20px;
            text-align: center;
        }
        .quick-links h2 {
            color: var(--pure-white);
            margin-bottom: 40px;
        }
        .links-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .link-card {
            background: var(--vanta-black);
            padding: 30px;
            border-radius: 10px;
            border: 1px solid rgba(136, 138, 143, 0.1);
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
            text-decoration: none;
            color: var(--steel-gray);
            transition: all 0.3s;
        }
        .link-card h3 {
            color: var(--pure-white);
        }
        .link-card:hover {
            box-shadow: 0 5px 20px rgba(212, 0, 38, 0.3);
        /* Footer */
        footer {
            background: var(--deep-obsidian);
            color: var(--steel-gray);
            text-align: center;
            padding: 40px 20px;
            border-top: 1px solid rgba(136, 138, 143, 0.1);
        }
        footer strong {
            color: var(--pure-white);
        }
        footer a {
            color: var(--crimson-villain);
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            <div class="logo-section">
                <img src="{{ asset('images/vantapress-icon.svg') }}" alt="VantaPress" style="width: 60px; height: 60px;">
                <div class="school-name">
                    <h1>VantaPress</h1>
                    <p>WordPress Philosophy, Laravel Power</p>
                </div>
            </div>adow: 0 5px 20px rgba(0,0,0,0.2);
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="https://github.com/sepirothx/vantapress" target="_blank">GitHub</a></li>
                    <li><a href="/admin">Admin Panel</a></li>
                </ul>
            </nav>lign: center;
            padding: 40px 20px;
        }
        footer a {
            color: #eeee22;
    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to VantaPress</h2>
        <p>A WordPress-inspired CMS built with Laravel. Simplicity meets power.</p>
        <a href="/admin" class="btn">Get Started</a>
    </section>r -->
    <header class="header">
    <!-- Features -->
    <section class="features" id="features">
        <div class="feature-card">
            <div class="feature-icon">🎯</div>
            <h3>WordPress Simplicity</h3>
            <p>Upload via FTP, run web installer, and start building. No terminal or build tools needed.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚡</div>
            <h3>Laravel Performance</h3>
            <p>Built on Laravel 11 with Eloquent ORM, modern PHP 8.2+, and clean architecture.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💎</div>
            <h3>FilamentPHP Admin</h3>
            <p>Beautiful, responsive admin panel out-of-the-box with zero custom code.</p>
        </div>
    </section>
    </header>
    <!-- Quick Links -->
    <section class="quick-links">
        <h2>Quick Access</h2>
        <div class="links-grid">
            <a href="/admin" class="link-card">
                <h3>🔐 Admin Panel</h3>
                <p>Manage your content</p>
            </a>
            <a href="/install.php" class="link-card">
                <h3>⚙️ Installation</h3>
                <p>Set up VantaPress</p>
            </a>
            <a href="https://github.com/sepirothx/vantapress" class="link-card" target="_blank">
                <h3>📚 Documentation</h3>
                <p>Learn more</p>
            </a>
            <a href="https://github.com/sepirothx/vantapress/issues" class="link-card" target="_blank">
                <h3>💬 Support</h3>
                <p>Get help</p>
            </a>
        </div>
    </section>iv class="feature-icon">🏢</div>
            <h3>Modern Facilities</h3>
    <!-- Footer -->
    <footer>
        <p><strong>VantaPress</strong></p>
        <p>WordPress Philosophy, Laravel Power</p>
        <p>© {{ date('Y') }} VantaPress. Open Source · MIT Licensed</p>
        <p>Created by <a href="https://github.com/sepirothx" target="_blank">Sepirothx</a> (Richard Cebel Cupal, LPT)</p>
    </footer>uick Access</h2>
        <div class="links-grid">
            <a href="/admin" class="link-card">
                <h3>🔐 Admin Portal</h3>
                <p>Faculty & Staff Login</p>
            </a>
            <a href="/student-portal" class="link-card">
                <h3>👨‍🎓 Student Portal</h3>
                <p>Access your account</p>
            </a>
            <a href="/enrollment" class="link-card">
                <h3>📝 Enrollment</h3>
                <p>Register for courses</p>
            </a>
            <a href="/library" class="link-card">
                <h3>📚 Library</h3>
                <p>Browse resources</p>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p><strong>Talisay City College</strong></p>
        <p>Talisay City, Philippines</p>
        <p>© {{ date('Y') }} Talisay City College. All Rights Reserved.</p>
        <p>Powered by <a href="/admin">TCC School CMS</a></p>
    </footer>
</body>
</html>
