@extends('theme.layouts::app')

@section('title', 'Terms of Use')

@section('content')
{{-- Hero Section --}}
<div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 60px 20px; text-align: center;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">Terms of Use</h1>
        <p style="font-size: 1.3rem; opacity: 0.95;">VantaPress License & Usage Guidelines</p>
    </div>
</div>

{{-- License Notice --}}
<div style="padding: 60px 20px; background: white;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <div style="background: #fef2f2; padding: 2rem; border-radius: 12px; border-left: 4px solid #dc2626; margin-bottom: 3rem;">
            <h2 style="font-size: 1.8rem; margin-bottom: 1rem; color: #dc2626;">📜 MIT License</h2>
            <p style="line-height: 1.8; color: #666;">
                VantaPress is open-source software licensed under the <strong>MIT License</strong>. This means you are free to use, modify, and distribute VantaPress for both personal and commercial projects.
            </p>
        </div>

        {{-- What You Can Do --}}
        <div style="margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626;">✅ What You CAN Do</h2>
            
            <div style="display: grid; gap: 1.5rem;">
                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #059669;">✓ Commercial Use</h3>
                    <p style="color: #666; line-height: 1.8;">
                        Use VantaPress for commercial projects, client work, or business websites without paying fees or royalties.
                    </p>
                </div>

                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #059669;">✓ Modification</h3>
                    <p style="color: #666; line-height: 1.8;">
                        Modify the source code to fit your needs. Customize, extend, and adapt VantaPress however you like.
                    </p>
                </div>

                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #059669;">✓ Distribution</h3>
                    <p style="color: #666; line-height: 1.8;">
                        Distribute modified or unmodified copies of VantaPress, as long as you include the original MIT license.
                    </p>
                </div>

                <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #059669;">✓ Private Use</h3>
                    <p style="color: #666; line-height: 1.8;">
                        Use VantaPress internally within your organization without any obligations to share your modifications.
                    </p>
                </div>
            </div>
        </div>

        {{-- What You Can't Do --}}
        <div style="margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626;">❌ What You CANNOT Do</h2>
            
            <div style="display: grid; gap: 1.5rem;">
                <div style="background: #fef2f2; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #dc2626;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #dc2626;">✗ Hold Us Liable</h3>
                    <p style="color: #666; line-height: 1.8;">
                        VantaPress is provided "as-is" without warranty. We are not responsible for any damages or losses resulting from using this software.
                    </p>
                </div>

                <div style="background: #fef2f2; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #dc2626;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #dc2626;">✗ Use Our Trademark Without Permission</h3>
                    <p style="color: #666; line-height: 1.8;">
                        While you can use the software, you cannot use the "VantaPress" name or logo to endorse your products without written permission.
                    </p>
                </div>

                <div style="background: #fef2f2; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #dc2626;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #dc2626;">✗ Remove License Notices</h3>
                    <p style="color: #666; line-height: 1.8;">
                        You must include the original MIT license and copyright notices in any distribution of VantaPress.
                    </p>
                </div>
            </div>
        </div>

        {{-- Usage Guidelines --}}
        <div style="margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626;">📋 Usage Guidelines</h2>
            
            <div style="line-height: 1.8; color: #374151;">
                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1f2937;">For Website Owners</h3>
                <ul style="margin-bottom: 2rem; padding-left: 1.5rem;">
                    <li style="margin-bottom: 0.5rem;">VantaPress is free to use for any type of website (personal, business, e-commerce, etc.)</li>
                    <li style="margin-bottom: 0.5rem;">You own all content you create and publish using VantaPress</li>
                    <li style="margin-bottom: 0.5rem;">No usage limits, no hidden fees, no premium tiers—it's completely free</li>
                    <li style="margin-bottom: 0.5rem;">You're encouraged to keep VantaPress updated for security and features</li>
                </ul>

                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1f2937;">For Developers</h3>
                <ul style="margin-bottom: 2rem; padding-left: 1.5rem;">
                    <li style="margin-bottom: 0.5rem;">You can create and sell themes (.vpt) and modules (.vpm) for VantaPress</li>
                    <li style="margin-bottom: 0.5rem;">You can offer VantaPress installation and customization services commercially</li>
                    <li style="margin-bottom: 0.5rem;">Contributing back to the project is appreciated but not required</li>
                    <li style="margin-bottom: 0.5rem;">If you find bugs or security issues, please report them responsibly</li>
                </ul>

                <h3 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1f2937;">For Hosting Providers</h3>
                <ul style="padding-left: 1.5rem;">
                    <li style="margin-bottom: 0.5rem;">You may offer VantaPress as a one-click install option</li>
                    <li style="margin-bottom: 0.5rem;">You may bundle VantaPress with hosting plans</li>
                    <li style="margin-bottom: 0.5rem;">You must include the original MIT license in your distribution</li>
                    <li style="margin-bottom: 0.5rem;">You cannot claim ownership or sole distribution rights to VantaPress</li>
                </ul>
            </div>
        </div>

        {{-- Disclaimer --}}
        <div style="background: #f9fafb; padding: 2rem; border-radius: 12px; margin-bottom: 3rem;">
            <h2 style="font-size: 2rem; margin-bottom: 1rem; color: #1f2937;">⚠️ Disclaimer</h2>
            <p style="line-height: 1.8; color: #666; margin-bottom: 1rem;">
                VantaPress is provided "as-is" without any warranty of any kind, express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, and non-infringement.
            </p>
            <p style="line-height: 1.8; color: #666;">
                In no event shall the authors or copyright holders be liable for any claim, damages, or other liability arising from the use of this software.
            </p>
        </div>

        {{-- Third-Party Dependencies --}}
        <div style="margin-bottom: 3rem;">
            <h2 style="font-size: 2.5rem; margin-bottom: 2rem; color: #dc2626;">📦 Third-Party Components</h2>
            <p style="line-height: 1.8; color: #666; margin-bottom: 1.5rem;">
                VantaPress is built on top of several open-source projects, each with their own licenses:
            </p>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f9fafb;">
                        <tr>
                            <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e5e7eb; color: #1f2937; font-weight: 700;">Component</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #e5e7eb; color: #1f2937; font-weight: 700;">License</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">Laravel Framework</td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">MIT License</td>
                        </tr>
                        <tr style="background: #f9fafb;">
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">FilamentPHP</td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">MIT License</td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">Laravel Modules (nWidart)</td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">MIT License</td>
                        </tr>
                        <tr style="background: #f9fafb;">
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">Spatie Laravel Permission</td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">MIT License</td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem;">TailwindCSS</td>
                            <td style="padding: 1rem;">MIT License</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Contact Section --}}
        <div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: white; padding: 2rem; border-radius: 12px; text-align: center;">
            <h3 style="font-size: 1.8rem; margin-bottom: 1rem;">Questions About Licensing?</h3>
            <p style="opacity: 0.95; margin-bottom: 1.5rem;">
                If you have questions about how you can use VantaPress, feel free to reach out.
            </p>
            <a href="mailto:chardy.tsadiq02@gmail.com?subject=VantaPress Licensing Question" style="background: white; color: #dc2626; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-block;">
                📧 Contact Us
            </a>
        </div>
    </div>
</div>

{{-- Footer Note --}}
<div style="padding: 40px 20px; background: #f9fafb; text-align: center;">
    <p style="color: #666;">
        <strong>Last Updated:</strong> December 2025 | 
        <a href="https://github.com/sepiroth-x/vantapress/blob/main/LICENSE" target="_blank" style="color: #dc2626; text-decoration: none;">View Full MIT License →</a>
    </p>
</div>
@endsection
