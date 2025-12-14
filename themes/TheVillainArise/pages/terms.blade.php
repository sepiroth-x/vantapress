<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - {{ config('app.name') }}</title>
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
            max-width: 1000px;
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
        
        .license-notice {
            background: linear-gradient(135deg, #1a0000, #0a0a0a);
            padding: 3rem;
            border-radius: 16px;
            border: 2px solid #ff0000;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.3);
            margin-bottom: 4rem;
        }
        
        .license-notice h2 {
            font-size: 2.5rem;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
        
        .license-notice p {
            font-size: 1.2rem;
            color: #ccc;
            line-height: 1.9;
        }
        
        .section-title {
            font-size: 3rem;
            color: #ff0000;
            margin-bottom: 3rem;
            text-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }
        
        .permission-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 4rem;
        }
        
        .permission-card {
            background: rgba(255, 0, 0, 0.05);
            padding: 2rem;
            border-radius: 12px;
            border-left: 4px solid #10b981;
            transition: all 0.3s;
        }
        
        .permission-card:hover {
            background: rgba(255, 0, 0, 0.1);
            transform: translateX(10px);
        }
        
        .permission-card h3 {
            font-size: 1.8rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
        
        .permission-card p {
            color: #aaa;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .restriction-card {
            background: rgba(255, 0, 0, 0.1);
            padding: 2rem;
            border-radius: 12px;
            border-left: 4px solid #ff0000;
            transition: all 0.3s;
        }
        
        .restriction-card:hover {
            background: rgba(255, 0, 0, 0.15);
            transform: translateX(10px);
        }
        
        .restriction-card h3 {
            font-size: 1.8rem;
            color: #ff0000;
            margin-bottom: 1rem;
        }
        
        .restriction-card p {
            color: #aaa;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        .guidelines h3 {
            font-size: 2rem;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
        
        .guidelines ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 3rem;
        }
        
        .guidelines ul li {
            padding: 0.8rem 0;
            padding-left: 2rem;
            position: relative;
            color: #bbb;
            font-size: 1.1rem;
            line-height: 1.7;
        }
        
        .guidelines ul li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: #ff0000;
            font-weight: 900;
        }
        
        .disclaimer {
            background: linear-gradient(135deg, #1a0000, #0a0a0a);
            padding: 3rem;
            border-radius: 16px;
            border: 2px solid #cc0000;
            margin-bottom: 4rem;
        }
        
        .disclaimer h2 {
            font-size: 2.5rem;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
        
        .disclaimer p {
            color: #aaa;
            font-size: 1.1rem;
            line-height: 1.9;
            margin-bottom: 1.5rem;
        }
        
        .components-table {
            background: rgba(255, 0, 0, 0.05);
            border: 1px solid #330000;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 4rem;
        }
        
        .components-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .components-table th {
            background: rgba(255, 0, 0, 0.1);
            padding: 1.5rem;
            text-align: left;
            color: #ff0000;
            font-weight: 700;
            font-size: 1.2rem;
            border-bottom: 2px solid #330000;
        }
        
        .components-table td {
            padding: 1.5rem;
            border-bottom: 1px solid #1a0000;
            color: #ccc;
            font-size: 1.1rem;
        }
        
        .components-table tr:nth-child(even) {
            background: rgba(255, 0, 0, 0.03);
        }
        
        .cta-box {
            background: linear-gradient(135deg, #ff0000, #cc0000);
            padding: 3rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.4);
        }
        
        .cta-box h3 {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        
        .cta-box p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 2rem;
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
        
        .footer-note {
            text-align: center;
            padding: 40px 20px;
            background: rgba(255, 0, 0, 0.05);
            color: #aaa;
            border-top: 1px solid #330000;
        }
        
        .footer-note a {
            color: #ff6666;
            text-decoration: none;
            font-weight: 700;
        }
        
        .footer-note a:hover {
            color: #ff0000;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .components-table {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    @include('theme.partials::header')

    <div class="hero">
        <h1>📜 TERMS OF USE</h1>
        <p>VantaPress License & Usage Guidelines</p>
    </div>

    <div class="section section-dark">
        <div class="container">
            <div class="license-notice">
                <h2>📜 MIT License</h2>
                <p>
                    VantaPress is open-source software licensed under the <strong style="color: #ff0000;">MIT License</strong>. This means you are free to use, modify, and distribute VantaPress for both personal and commercial projects.
                </p>
            </div>

            <h2 class="section-title">✅ What You CAN Do</h2>
            
            <div class="permission-grid">
                <div class="permission-card">
                    <h3>✓ Commercial Use</h3>
                    <p>
                        Use VantaPress for commercial projects, client work, or business websites without paying fees or royalties.
                    </p>
                </div>

                <div class="permission-card">
                    <h3>✓ Modification</h3>
                    <p>
                        Modify the source code to fit your needs. Customize, extend, and adapt VantaPress however you like.
                    </p>
                </div>

                <div class="permission-card">
                    <h3>✓ Distribution</h3>
                    <p>
                        Distribute modified or unmodified copies of VantaPress, as long as you include the original MIT license.
                    </p>
                </div>

                <div class="permission-card">
                    <h3>✓ Private Use</h3>
                    <p>
                        Use VantaPress internally within your organization without any obligations to share your modifications.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-darker">
        <div class="container">
            <h2 class="section-title">❌ What You CANNOT Do</h2>
            
            <div class="permission-grid">
                <div class="restriction-card">
                    <h3>✗ Hold Us Liable</h3>
                    <p>
                        VantaPress is provided "as-is" without warranty. We are not responsible for any damages or losses resulting from using this software.
                    </p>
                </div>

                <div class="restriction-card">
                    <h3>✗ Use Our Trademark Without Permission</h3>
                    <p>
                        While you can use the software, you cannot use the "VantaPress" name or logo to endorse your products without written permission.
                    </p>
                </div>

                <div class="restriction-card">
                    <h3>✗ Remove License Notices</h3>
                    <p>
                        You must include the original MIT license and copyright notices in any distribution of VantaPress.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="section section-dark">
        <div class="container">
            <h2 class="section-title">📋 Usage Guidelines</h2>
            
            <div class="guidelines">
                <h3>For Website Owners</h3>
                <ul>
                    <li>VantaPress is free to use for any type of website (personal, business, e-commerce, etc.)</li>
                    <li>You own all content you create and publish using VantaPress</li>
                    <li>No usage limits, no hidden fees, no premium tiers—it's completely free</li>
                    <li>You're encouraged to keep VantaPress updated for security and features</li>
                </ul>

                <h3>For Developers</h3>
                <ul>
                    <li>You can create and sell themes (.vpt) and modules (.vpm) for VantaPress</li>
                    <li>You can offer VantaPress installation and customization services commercially</li>
                    <li>Contributing back to the project is appreciated but not required</li>
                    <li>If you find bugs or security issues, please report them responsibly</li>
                </ul>

                <h3>For Hosting Providers</h3>
                <ul>
                    <li>You may offer VantaPress as a one-click install option</li>
                    <li>You may bundle VantaPress with hosting plans</li>
                    <li>You must include the original MIT license in your distribution</li>
                    <li>You cannot claim ownership or sole distribution rights to VantaPress</li>
                </ul>
            </div>

            <div class="disclaimer">
                <h2>⚠️ Disclaimer</h2>
                <p>
                    VantaPress is provided "as-is" without any warranty of any kind, express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, and non-infringement.
                </p>
                <p>
                    In no event shall the authors or copyright holders be liable for any claim, damages, or other liability arising from the use of this software.
                </p>
            </div>
        </div>
    </div>

    <div class="section section-darker">
        <div class="container">
            <h2 class="section-title">📦 Third-Party Components</h2>
            <p style="font-size: 1.2rem; color: #aaa; margin-bottom: 2rem; line-height: 1.8;">
                VantaPress is built on top of several open-source projects, each with their own licenses:
            </p>
            
            <div class="components-table">
                <table>
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>License</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Laravel Framework</td>
                            <td>MIT License</td>
                        </tr>
                        <tr>
                            <td>FilamentPHP</td>
                            <td>MIT License</td>
                        </tr>
                        <tr>
                            <td>Laravel Modules (nWidart)</td>
                            <td>MIT License</td>
                        </tr>
                        <tr>
                            <td>Spatie Laravel Permission</td>
                            <td>MIT License</td>
                        </tr>
                        <tr>
                            <td>TailwindCSS</td>
                            <td>MIT License</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="cta-box">
                <h3>Questions About Licensing?</h3>
                <p>
                    If you have questions about how you can use VantaPress, feel free to reach out.
                </p>
                <a href="mailto:chardy.tsadiq02@gmail.com?subject=VantaPress Licensing Question" class="btn">
                    📧 Contact Us
                </a>
            </div>
        </div>
    </div>

    <div class="footer-note">
        <p>
            <strong>Last Updated:</strong> December 2025 | 
            <a href="https://github.com/sepiroth-x/vantapress/blob/main/LICENSE" target="_blank">View Full MIT License →</a>
        </p>
    </div>

    @include('theme.partials::footer')
</body>
</html>
