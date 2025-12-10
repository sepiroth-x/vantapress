<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - VantaPress Module</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
        }
        h1 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        .subtitle {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            display: inline-block;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .content {
            margin: 2rem 0;
        }
        .message {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid #667eea;
            margin-bottom: 2rem;
        }
        .features {
            margin: 2rem 0;
        }
        .features h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        .feature-list {
            list-style: none;
        }
        .feature-list li {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list li::before {
            content: "✓";
            background: #667eea;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
        }
        .links {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        .info-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }
        .info-card strong {
            display: block;
            color: #667eea;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .info-card span {
            color: #666;
            font-size: 0.9rem;
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
            color: #666;
            font-size: 0.9rem;
        }
        @media (max-width: 600px) {
            .container {
                padding: 2rem 1.5rem;
            }
            h1 {
                font-size: 2rem;
            }
            .links {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧩 {{ $title }}</h1>
            <p class="subtitle">{{ $message }}</p>
            <div class="badge">VantaPress v{{ $version }}</div>
        </div>

        <div class="content">
            <div class="message">
                <strong>🎉 Congratulations!</strong><br>
                You've successfully loaded a VantaPress module. This module demonstrates 
                the structure and capabilities of the VantaPress module system.
            </div>

            <div class="features">
                <h2>✨ Module Features</h2>
                <ul class="feature-list">
                    @foreach($features as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <strong>MVC</strong>
                    <span>Architecture</span>
                </div>
                <div class="info-card">
                    <strong>Blade</strong>
                    <span>Templates</span>
                </div>
                <div class="info-card">
                    <strong>Laravel</strong>
                    <span>Powered</span>
                </div>
            </div>

            <div class="links">
                <a href="{{ route('hello.welcome') }}" class="btn btn-primary">
                    Visit Welcome Page →
                </a>
                <a href="{{ route('hello.api') }}" class="btn btn-secondary">
                    View API Example
                </a>
                <a href="/admin/modules" class="btn btn-secondary">
                    Manage Modules
                </a>
            </div>
        </div>

        <div class="footer">
            <p>
                <strong>For Developers:</strong> Check the 
                <a href="https://github.com/sepiroth-x/vantapress" style="color: #667eea;">README.md</a> 
                file in this module for a complete development guide.
            </p>
            <p style="margin-top: 1rem;">
                Created with ❤️ by VantaPress | Open Source
            </p>
        </div>
    </div>
</body>
</html>
