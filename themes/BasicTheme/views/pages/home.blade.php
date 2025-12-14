@extends('theme.layouts::app')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<div class="hero" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 80px 20px; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem;">⚡ VantaPress</h1>
        <p style="font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.95;">A WordPress-Inspired CMS Built with Laravel</p>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.85;">The Best of Both Worlds: WordPress Simplicity + Laravel Power</p>
        <div class="hero-actions" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" class="btn btn-primary" style="background: white; color: #dc2626; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700;">📥 Download Now</a>
            <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" class="btn btn-secondary" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; border: 2px solid white;">📚 Documentation</a>
        </div>
        <div style="margin-top: 2rem; padding: 1rem; background: rgba(0,0,0,0.2); border-radius: 8px; display: inline-block;">
            <strong>Current Version:</strong> v1.2.1-social-advanced | <strong>License:</strong> MIT (Open Source)
        </div>
    </div>
</div>

{{-- Comparison Table --}}
<div style="padding: 60px 20px; background: #f9fafb;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: #dc2626;">🌟 Why VantaPress?</h2>
        
        <div style="overflow-x: auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #dc2626; color: white;">
                        <th style="padding: 16px; text-align: left; font-size: 1.1rem;">Feature</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem;">WordPress</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem;">VantaPress</th>
                        <th style="padding: 16px; text-align: center; font-size: 1.1rem;">Laravel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Ease of Use</td>
                        <td style="padding: 16px; text-align: center;">✅ Beginner-friendly</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ Simple setup</td>
                        <td style="padding: 16px; text-align: center;">❌ Complex setup</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Modern PHP</td>
                        <td style="padding: 16px; text-align: center;">❌ Legacy code</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ Laravel 11</td>
                        <td style="padding: 16px; text-align: center;">✅ Modern code</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Admin Panel</td>
                        <td style="padding: 16px; text-align: center;">✅ Built-in</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ FilamentPHP</td>
                        <td style="padding: 16px; text-align: center;">❌ Build yourself</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Database ORM</td>
                        <td style="padding: 16px; text-align: center;">❌ wpdb</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ Eloquent</td>
                        <td style="padding: 16px; text-align: center;">✅ Eloquent</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Asset Management</td>
                        <td style="padding: 16px; text-align: center;">⚠️ Plugins needed</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ Built-in</td>
                        <td style="padding: 16px; text-align: center;">⚠️ Vite required</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; font-weight: 600;">Shared Hosting</td>
                        <td style="padding: 16px; text-align: center;">✅ Works anywhere</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ Optimized</td>
                        <td style="padding: 16px; text-align: center;">❌ Often restricted</td>
                    </tr>
                    <tr>
                        <td style="padding: 16px; font-weight: 600;">Code Quality</td>
                        <td style="padding: 16px; text-align: center;">⚠️ Mixed</td>
                        <td style="padding: 16px; text-align: center; background: #fef2f2; font-weight: 700;">✅ PSR standards</td>
                        <td style="padding: 16px; text-align: center;">✅ PSR standards</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Key Features --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: #dc2626;">💎 What Makes VantaPress Different?</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🎯</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">WordPress Philosophy, Laravel Power</h3>
                <p style="color: #666;">Instant setup with web-based installer, no terminal required</p>
            </div>
            
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🚀</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">No Build Tools Required</h3>
                <p style="color: #666;">Deploy via FTP/cPanel, FilamentPHP handles all assets internally</p>
            </div>
            
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">💎</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">Beautiful Admin Panel</h3>
                <p style="color: #666;">FilamentPHP provides a stunning dashboard with zero compilation needed</p>
            </div>
            
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🏗️</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">Proper Architecture</h3>
                <p style="color: #666;">MVC pattern, Eloquent ORM, dependency injection, testable code</p>
            </div>
            
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🌐</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">Shared Hosting Ready</h3>
                <p style="color: #666;">Works on cheap shared hosting like iFastNet, HostGator, Bluehost</p>
            </div>
            
            <div style="padding: 2rem; background: #fef2f2; border-radius: 12px; border-left: 4px solid #dc2626;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔓</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem; color: #991b1b;">Open Source & Free</h3>
                <p style="color: #666;">MIT licensed, modify and use however you want</p>
            </div>
        </div>
    </div>
</div>

{{-- Quick Installation --}}
<div style="padding: 60px 20px; background: #1f2937; color: white;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 1rem;">🚀 WordPress-Style Installation</h2>
        <p style="text-align: center; font-size: 1.2rem; margin-bottom: 3rem; opacity: 0.9;">Get started in minutes, not hours!</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            <div style="text-align: center; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">1. Download</h3>
                <p style="opacity: 0.8;">Get the latest release</p>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">☁️</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">2. Upload</h3>
                <p style="opacity: 0.8;">Use FTP or File Manager</p>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⚙️</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">3. Configure</h3>
                <p style="opacity: 0.8;">Rename .env.example to .env</p>
            </div>
            
            <div style="text-align: center; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🌐</div>
                <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">4. Install</h3>
                <p style="opacity: 0.8;">Visit /install.php</p>
            </div>
        </div>
        
        <div style="text-align: center;">
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">✅ No Terminal. No Composer. No npm. Just Upload & Install!</h3>
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" style="display: inline-block; background: #dc2626; color: white; padding: 16px 48px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.2rem; margin-top: 1rem;">Download VantaPress Now →</a>
        </div>
    </div>
</div>

{{-- Technology Stack --}}
<div style="padding: 60px 20px; background: #f9fafb;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: #dc2626;">🛠️ Technology Stack</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; text-align: center;">
            <div style="padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <strong style="color: #dc2626;">Laravel</strong><br>
                <span style="color: #666;">11.47.0</span>
            </div>
            <div style="padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <strong style="color: #dc2626;">PHP</strong><br>
                <span style="color: #666;">8.2.29+</span>
            </div>
            <div style="padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <strong style="color: #dc2626;">FilamentPHP</strong><br>
                <span style="color: #666;">3.3.45</span>
            </div>
            <div style="padding: 1.5rem; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <strong style="color: #dc2626;">MySQL</strong><br>
                <span style="color: #666;">5.7+ / MariaDB</span>
            </div>
        </div>
    </div>
</div>

{{-- Footer CTA --}}
<div style="padding: 60px 20px; background: #dc2626; color: white; text-align: center;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">Ready to Get Started?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95;">Join developers who choose WordPress simplicity with Laravel power</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="https://github.com/sepiroth-x/vantapress/releases/latest" target="_blank" style="background: white; color: #dc2626; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem;">📥 Download VantaPress</a>
            <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem; border: 2px solid white;">📚 Read Documentation</a>
            <a href="https://github.com/sepiroth-x/vantapress" target="_blank" style="background: rgba(255,255,255,0.2); color: white; padding: 16px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 1.1rem; border: 2px solid white;">⭐ Star on GitHub</a>
        </div>
        <p style="margin-top: 2rem; opacity: 0.8;">Created by <strong>Sepiroth X Villainous</strong> | MIT License | Open Source</p>
    </div>
</div>
@endsection
