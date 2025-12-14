<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - {{ config('app.name') }}</title>
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
            margin-bottom: 2rem;
            text-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }
        
        .section-subtitle {
            text-align: center;
            font-size: 1.3rem;
            color: #aaa;
            max-width: 800px;
            margin: 0 auto 4rem;
            line-height: 1.8;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        
        .contact-card {
            background: linear-gradient(135deg, #1a0000 0%, #0a0a0a 100%);
            padding: 3rem;
            border-radius: 16px;
            border: 2px solid #330000;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            border-color: #ff0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
            transform: translateY(-5px);
        }
        
        .contact-card .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 0 10px rgba(255, 0, 0, 0.4));
        }
        
        .contact-card h3 {
            font-size: 2rem;
            color: #ff0000;
            margin-bottom: 1rem;
        }
        
        .contact-card p {
            color: #aaa;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        
        .contact-card a {
            color: #ff6666;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s;
        }
        
        .contact-card a:hover {
            color: #ff0000;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        .social-section {
            background: linear-gradient(135deg, #1a0000, #0a0a0a);
            padding: 4rem;
            border-radius: 16px;
            border: 2px solid #ff0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
            text-align: center;
        }
        
        .social-section h3 {
            font-size: 2.5rem;
            color: #ff0000;
            margin-bottom: 1rem;
        }
        
        .social-section p {
            font-size: 1.2rem;
            color: #aaa;
            margin-bottom: 3rem;
        }
        
        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .social-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 16px 32px;
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid #ff0000;
            border-radius: 8px;
            text-decoration: none;
            color: #ff6666;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background: rgba(255, 0, 0, 0.2);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.4);
            transform: translateY(-2px);
            color: #ff0000;
        }
        
        .social-btn span {
            font-size: 1.8rem;
        }
        
        .developer-section {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .dev-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .dev-card {
            background: rgba(255, 0, 0, 0.1);
            padding: 2.5rem;
            border-radius: 12px;
            border-left: 4px solid #ff0000;
            text-align: center;
            transition: all 0.3s;
        }
        
        .dev-card:hover {
            background: rgba(255, 0, 0, 0.15);
            transform: translateX(10px);
        }
        
        .dev-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .dev-card h4 {
            font-size: 1.5rem;
            color: #ff0000;
            margin-bottom: 0.5rem;
        }
        
        .dev-card p {
            color: #aaa;
            margin-bottom: 1rem;
            line-height: 1.7;
        }
        
        .dev-card a {
            color: #ff6666;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s;
        }
        
        .dev-card a:hover {
            color: #ff0000;
        }
        
        .cta-box {
            background: linear-gradient(135deg, #ff0000, #cc0000);
            padding: 3rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.4);
        }
        
        .cta-box h4 {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        
        .cta-box p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 16px 40px;
            background: #fff;
            color: #ff0000;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #f0f0f0;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .location-section {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .location-section h2 {
            font-size: 2.5rem;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
        
        .location-section p {
            font-size: 1.2rem;
            color: #aaa;
            line-height: 1.9;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .contact-grid, .dev-grid {
                grid-template-columns: 1fr;
            }
            
            .social-buttons {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    @include('theme.partials::header')

    <div class="hero">
        <h1>📬 CONTACT US</h1>
        <p>Let's Build Something Amazing Together</p>
    </div>

    <div class="section section-dark">
        <div class="container">
            <h2 class="section-title">Get in Touch</h2>
            <p class="section-subtitle">
                Whether you're a developer looking to contribute, a business interested in VantaPress,<br>
                or just have questions—we'd love to hear from you!
            </p>

            <div class="contact-grid">
                <div class="contact-card">
                    <div class="icon">📧</div>
                    <h3>Email</h3>
                    <p>For business inquiries, support, and collaboration</p>
                    <a href="mailto:chardy.tsadiq02@gmail.com">chardy.tsadiq02@gmail.com</a>
                </div>

                <div class="contact-card">
                    <div class="icon">📱</div>
                    <h3>Mobile</h3>
                    <p>Direct line for urgent matters</p>
                    <a href="tel:+639150388448">+63 915 0388 448</a>
                </div>

                <div class="contact-card">
                    <div class="icon">💻</div>
                    <h3>GitHub</h3>
                    <p>For code contributions and issues</p>
                    <a href="https://github.com/sepiroth-x/vantapress" target="_blank">@sepiroth-x/vantapress</a>
                </div>
            </div>

            <div class="social-section">
                <h3>🌐 Connect on Social Media</h3>
                <p>Follow us for updates, tips, and behind-the-scenes content</p>
                
                <div class="social-buttons">
                    <a href="https://www.facebook.com/sepirothx/" target="_blank" class="social-btn">
                        <span>📘</span> Facebook
                    </a>
                    
                    <a href="https://x.com/sepirothx000" target="_blank" class="social-btn">
                        <span>🐦</span> Twitter/X
                    </a>
                    
                    <a href="https://github.com/sepiroth-x" target="_blank" class="social-btn">
                        <span>⚡</span> GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-darker">
        <div class="container">
            <h2 class="section-title">👨‍💻 For Developers</h2>
            <p class="section-subtitle">
                Want to contribute to VantaPress? We welcome developers of all skill levels!
            </p>

            <div class="developer-section">
                <div class="dev-grid">
                    <div class="dev-card">
                        <div class="icon">🐛</div>
                        <h4>Report Bugs</h4>
                        <p>Found an issue? Let us know on GitHub</p>
                        <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank">Submit Issue →</a>
                    </div>
                    
                    <div class="dev-card">
                        <div class="icon">🔧</div>
                        <h4>Contribute Code</h4>
                        <p>Submit pull requests and improvements</p>
                        <a href="https://github.com/sepiroth-x/vantapress/pulls" target="_blank">View PRs →</a>
                    </div>
                    
                    <div class="dev-card">
                        <div class="icon">📚</div>
                        <h4>Documentation</h4>
                        <p>Read the full developer guide</p>
                        <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank">Read Docs →</a>
                    </div>
                </div>
                
                <div class="cta-box">
                    <h4>💡 Have an Idea?</h4>
                    <p>
                        We're always looking for feedback and feature requests. Reach out directly:
                    </p>
                    <a href="mailto:chardy.tsadiq02@gmail.com?subject=VantaPress Feature Request" class="btn">
                        ✉️ Email Your Ideas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-dark">
        <div class="container">
            <div class="location-section">
                <h2>🌏 Based in the Philippines</h2>
                <p>
                    VantaPress is developed from the Philippines, but we're available for collaboration worldwide.<br><br>
                    <strong style="color: #ff0000;">Response Time:</strong> Usually within 24-48 hours (Philippine Time, GMT+8)
                </p>
            </div>
        </div>
    </div>

    @include('theme.partials::footer')
</body>
</html>
