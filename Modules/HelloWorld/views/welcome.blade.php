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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .emoji {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: wave 2s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(20deg); }
            75% { transform: rotate(-20deg); }
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }
        .message {
            color: #666;
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .timestamp {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .info-box {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .info-box h2 {
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        .info-box p {
            font-size: 1rem;
            opacity: 0.9;
        }
        .links {
            display: flex;
            gap: 1rem;
            justify-content: center;
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .btn-secondary {
            background: white;
            color: #f5576c;
            border: 2px solid #f5576c;
        }
        @media (max-width: 600px) {
            .container {
                padding: 2rem 1.5rem;
            }
            h1 {
                font-size: 2rem;
            }
            .emoji {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">👋</div>
        <h1>{{ $title }}</h1>
        <p class="message">{{ $message }}</p>
        
        <div class="timestamp">
            <strong>Page loaded:</strong> {{ $timestamp }}
        </div>

        <div class="info-box">
            <h2>💡 Pro Tip</h2>
            <p>
                This page demonstrates how easy it is to create beautiful, 
                responsive pages within VantaPress modules. No build tools required!
            </p>
        </div>

        <div class="links">
            <a href="{{ route('hello.index') }}" class="btn btn-primary">
                ← Back to Module Home
            </a>
            <a href="/admin/modules" class="btn btn-secondary">
                Manage Modules
            </a>
        </div>
    </div>
</body>
</html>
