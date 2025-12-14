@extends('theme.layouts::app')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<div class="hero">
    <div class="container">
        <h1 class="glow-text">⚡ VantaPress</h1>
        <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">A WordPress-Inspired CMS Built with Laravel</p>
        <p style="font-size: 1.1rem; margin-bottom: 2rem;">The Best of Both Worlds: WordPress Simplicity + Laravel Power</p>
        <div class="hero-actions">
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" class="btn btn-primary">📥 Download Now</a>
            <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" class="btn btn-secondary">📚 Documentation</a>
        </div>
        <div style="margin-top: 2rem; padding: 1rem; background: rgba(212, 0, 38, 0.2); border-radius: 8px; display: inline-block; border: 1px solid rgba(212, 0, 38, 0.3);">
            <strong>Current Version:</strong> v1.2.1-social-advanced | <strong>License:</strong> MIT (Open Source)
        </div>
    </div>
</div>

{{-- Comparison Table --}}
<section class="section section-dark" style="padding: 60px 20px; position: relative;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 1;">
        <h2 class="glow-text" style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem;">🌟 Why VantaPress?</h2>
        
        <div style="overflow-x: auto; background: linear-gradient(135deg, rgba(212, 0, 38, 0.1) 0%, rgba(10, 10, 10, 0.8) 100%); border-radius: 12px; box-shadow: 0 8px 30px rgba(212, 0, 38, 0.3); border: 1px solid rgba(212, 0, 38, 0.3);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);">
                        <th style="padding: 16px; text-align: left; font-size: 1.1rem; color: white;">Feature</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem; color: white;">WordPress</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem; color: white;">VantaPress</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem; color: white;">Laravel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Ease of Use</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ Beginner-friendly</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ Simple setup</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">❌ Complex setup</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Modern PHP</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">❌ Legacy code</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ Laravel 11</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ Modern code</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Admin Panel</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ Built-in</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ FilamentPHP</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">❌ Build yourself</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Database ORM</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">❌ wpdb</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ Eloquent</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ Eloquent</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Asset Management</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">⚠️ Plugins needed</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ Built-in</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">⚠️ Vite required</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(212, 0, 38, 0.2);">
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Shared Hosting</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ Works anywhere</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ Optimized</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">❌ Often restricted</td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; font-weight: 600; color: var(--text-color);">Code Quality</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">⚠️ Mixed</td>
                        <td style="padding: 16px; text-align: center; background: rgba(212, 0, 38, 0.2); font-weight: 700; color: var(--color-primary);">✅ PSR standards</td>
                        <td style="padding: 16px; text-align: center; color: var(--text-color);">✅ PSR standards</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Key Features --}}
<section class="features">
    <div class="container">
        <h2 class="glow-text">💎 What Makes VantaPress Different?</h2>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>WordPress Philosophy, Laravel Power</h3>
                <p>Instant setup with web-based installer, no terminal required</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>No Build Tools Required</h3>
                <p>Deploy via FTP/cPanel, FilamentPHP handles all assets internally</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">💎</div>
                <h3>Beautiful Admin Panel</h3>
                <p>FilamentPHP provides a stunning dashboard with zero compilation needed</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🏗️</div>
                <h3>Proper Architecture</h3>
                <p>MVC pattern, Eloquent ORM, dependency injection, testable code</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🌐</div>
                <h3>Shared Hosting Ready</h3>
                <p>Works on cheap shared hosting like iFastNet, HostGator, Bluehost</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔓</div>
                <h3>Open Source & Free</h3>
                <p>MIT licensed, modify and use however you want</p>
            </div>
        </div>
    </div>
</section>

{{-- Quick Installation --}}
<section class="section section-darker" style="padding: 60px 20px; color: white;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h2 class="glow-text" style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem;">🚀 WordPress-Style Installation</h2>
        <p style="text-align: center; font-size: 1.2rem; margin-bottom: 3rem; opacity: 0.9;">Get started in minutes, not hours!</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            <div class="card-dark" style="text-align: center; padding: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem; filter: drop-shadow(0 0 10px rgba(212, 0, 38, 0.5));">📦</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: var(--color-primary);">1. Download</h3>
                <p style="opacity: 0.8;">Get the latest release</p>
            </div>
            
            <div class="card-dark" style="text-align: center; padding: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem; filter: drop-shadow(0 0 10px rgba(212, 0, 38, 0.5));">☁️</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: var(--color-primary);">2. Upload</h3>
                <p style="opacity: 0.8;">Use FTP or File Manager</p>
            </div>
            
            <div class="card-dark" style="text-align: center; padding: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem; filter: drop-shadow(0 0 10px rgba(212, 0, 38, 0.5));">⚙️</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: var(--color-primary);">3. Configure</h3>
                <p style="opacity: 0.8;">Rename .env.example to .env</p>
            </div>
            
            <div class="card-dark" style="text-align: center; padding: 1.5rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem; filter: drop-shadow(0 0 10px rgba(212, 0, 38, 0.5));">🌐</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem; color: var(--color-primary);">4. Install</h3>
                <p style="opacity: 0.8;">Visit /install.php</p>
            </div>
        </div>
        
        <div style="text-align: center;">
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">✅ No Terminal. No Composer. No npm. Just Upload & Install!</h3>
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" class="btn btn-primary" style="display: inline-block; padding: 16px 48px; font-size: 1.2rem; margin-top: 1rem;">Download VantaPress Now →</a>
        </div>
    </div>
</section>

{{-- Technology Stack --}}
<section class="section section-light" style="padding: 60px 20px; position: relative;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; position: relative; z-index: 1;">
        <h2 class="glow-text" style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem;">🛠️ Technology Stack</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; text-align: center;">
            <div class="card" style="padding: 1.5rem;">
                <strong style="color: var(--color-primary); font-size: 1.2rem;">Laravel</strong><br>
                <span style="color: var(--text-color); opacity: 0.8;">11.47.0</span>
            </div>
            <div class="card" style="padding: 1.5rem;">
                <strong style="color: var(--color-primary); font-size: 1.2rem;">PHP</strong><br>
                <span style="color: var(--text-color); opacity: 0.8;">8.2.29+</span>
            </div>
            <div class="card" style="padding: 1.5rem;">
                <strong style="color: var(--color-primary); font-size: 1.2rem;">FilamentPHP</strong><br>
                <span style="color: var(--text-color); opacity: 0.8;">3.3.45</span>
            </div>
            <div class="card" style="padding: 1.5rem;">
                <strong style="color: var(--color-primary); font-size: 1.2rem;">MySQL</strong><br>
                <span style="color: var(--text-color); opacity: 0.8;">5.7+ / MariaDB</span>
            </div>
        </div>
    </div>
</section>

{{-- Footer CTA --}}
<section style="padding: 60px 20px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: white; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 70%); animation: pulse 4s ease-in-out infinite;"></div>
    <div class="container" style="max-width: 800px; margin: 0 auto; position: relative; z-index: 1;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); color: white;">Ready to Get Started?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95;">Join developers who choose WordPress simplicity with Laravel power</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" style="background: white; color: var(--color-primary); padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 30px rgba(0, 0, 0, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0, 0, 0, 0.3)';">📥 Download VantaPress</a>
            <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" style="background: white; color: var(--color-primary); padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 30px rgba(0, 0, 0, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0, 0, 0, 0.3)';">📚 Read Documentation</a>
            <a href="https://github.com/sepiroth-x/vantapress" target="_blank" style="background: white; color: var(--color-primary); padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 30px rgba(0, 0, 0, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0, 0, 0, 0.3)';">⭐ Star on GitHub</a>
        </div>
        <p style="margin-top: 2rem; opacity: 0.8;">Created by <strong>Sepiroth X Villainous</strong> | MIT License | Open Source</p>
    </div>
</section>
@endsection
