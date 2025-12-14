@extends('theme.layouts::app')

@section('title', 'Contact Us')

@section('content')
{{-- Hero Section --}}
<div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 60px 20px; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">Contact Us</h1>
        <p style="font-size: 1.3rem; opacity: 0.95;">Let's Build Something Amazing Together</p>
    </div>
</div>

{{-- Contact Options --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: #dc2626;">📬 Get in Touch</h2>
            <p style="font-size: 1.2rem; color: #666;">
                Whether you're a developer looking to contribute, a business interested in VantaPress,<br>
                or just have questions—we'd love to hear from you!
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
            {{-- Email Card --}}
            <div style="background: #fef2f2; padding: 2rem; border-radius: 12px; border-left: 4px solid #dc2626; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📧</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #dc2626;">Email</h3>
                <p style="color: #666; margin-bottom: 1rem;">For business inquiries, support, and collaboration</p>
                <a href="mailto:chardy.tsadiq02@gmail.com" style="color: #dc2626; font-weight: 700; font-size: 1.1rem; text-decoration: none;">
                    chardy.tsadiq02@gmail.com
                </a>
            </div>

            {{-- Phone Card --}}
            <div style="background: #fef2f2; padding: 2rem; border-radius: 12px; border-left: 4px solid #dc2626; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📱</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #dc2626;">Mobile</h3>
                <p style="color: #666; margin-bottom: 1rem;">Direct line for urgent matters</p>
                <a href="tel:+639150388448" style="color: #dc2626; font-weight: 700; font-size: 1.1rem; text-decoration: none;">
                    +63 915 0388 448
                </a>
            </div>

            {{-- GitHub Card --}}
            <div style="background: #fef2f2; padding: 2rem; border-radius: 12px; border-left: 4px solid #dc2626; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💻</div>
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #dc2626;">GitHub</h3>
                <p style="color: #666; margin-bottom: 1rem;">For code contributions and issues</p>
                <a href="https://github.com/sepiroth-x/vantapress" target="_blank" style="color: #dc2626; font-weight: 700; font-size: 1.1rem; text-decoration: none;">
                    @sepiroth-x/vantapress
                </a>
            </div>
        </div>

        {{-- Social Media --}}
        <div style="background: #f9fafb; padding: 3rem; border-radius: 12px; text-align: center;">
            <h3 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: #1f2937;">🌐 Connect on Social Media</h3>
            <p style="color: #666; margin-bottom: 2rem;">Follow us for updates, tips, and behind-the-scenes content</p>
            
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                <a href="https://www.facebook.com/sepirothx/" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; padding: 12px 24px; background: white; border: 2px solid #1877f2; border-radius: 8px; text-decoration: none; color: #1877f2; font-weight: 700;">
                    <span style="font-size: 1.5rem;">📘</span> Facebook
                </a>
                
                <a href="https://x.com/sepirothx000" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; padding: 12px 24px; background: white; border: 2px solid #1da1f2; border-radius: 8px; text-decoration: none; color: #1da1f2; font-weight: 700;">
                    <span style="font-size: 1.5rem;">🐦</span> Twitter/X
                </a>
                
                <a href="https://github.com/sepiroth-x" target="_blank" style="display: flex; align-items: center; gap: 0.5rem; padding: 12px 24px; background: white; border: 2px solid #333; border-radius: 8px; text-decoration: none; color: #333; font-weight: 700;">
                    <span style="font-size: 1.5rem;">⚡</span> GitHub
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Developer Section --}}
<div style="padding: 60px 20px; background: #1f2937; color: white;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem; text-align: center;">👨‍💻 For Developers</h2>
        
        <div style="line-height: 1.8; font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.9;">
            <p style="margin-bottom: 1.5rem; text-align: center;">
                Want to contribute to VantaPress? We welcome developers of all skill levels!
            </p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🐛</div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Report Bugs</h4>
                    <p style="opacity: 0.8; margin-bottom: 1rem;">Found an issue? Let us know on GitHub</p>
                    <a href="https://github.com/sepiroth-x/vantapress/issues" target="_blank" style="color: #fca5a5; text-decoration: none; font-weight: 700;">Submit Issue →</a>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔧</div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Contribute Code</h4>
                    <p style="opacity: 0.8; margin-bottom: 1rem;">Submit pull requests and improvements</p>
                    <a href="https://github.com/sepiroth-x/vantapress/pulls" target="_blank" style="color: #fca5a5; text-decoration: none; font-weight: 700;">View PRs →</a>
                </div>
                
                <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📚</div>
                    <h4 style="font-size: 1.3rem; margin-bottom: 0.5rem;">Documentation</h4>
                    <p style="opacity: 0.8; margin-bottom: 1rem;">Read the full developer guide</p>
                    <a href="https://github.com/sepiroth-x/vantapress#readme" target="_blank" style="color: #fca5a5; text-decoration: none; font-weight: 700;">Read Docs →</a>
                </div>
            </div>
        </div>
        
        <div style="background: rgba(220, 38, 38, 0.2); padding: 2rem; border-radius: 12px; border: 2px solid rgba(220, 38, 38, 0.5); margin-top: 3rem;">
            <h4 style="font-size: 1.5rem; margin-bottom: 1rem; text-align: center;">💡 Have an Idea?</h4>
            <p style="text-align: center; opacity: 0.9; margin-bottom: 1.5rem;">
                We're always looking for feedback and feature requests. Reach out directly:
            </p>
            <div style="text-align: center;">
                <a href="mailto:chardy.tsadiq02@gmail.com?subject=VantaPress Feature Request" style="background: #dc2626; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-block;">
                    ✉️ Email Your Ideas
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Location & Availability --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h2 style="font-size: 2rem; margin-bottom: 1.5rem; color: #dc2626;">🌏 Based in the Philippines</h2>
        <p style="font-size: 1.1rem; color: #666; line-height: 1.8;">
            VantaPress is developed from the Philippines, but we're available for collaboration worldwide.<br>
            <strong>Response Time:</strong> Usually within 24-48 hours (Philippine Time, GMT+8)
        </p>
    </div>
</div>
@endsection
