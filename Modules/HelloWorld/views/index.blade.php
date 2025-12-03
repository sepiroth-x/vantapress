<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #333;
            margin: 0 0 1rem;
        }
        p {
            color: #666;
            font-size: 1.2rem;
            margin: 0 0 2rem;
        }
        .badge {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            display: inline-block;
            font-size: 0.9rem;
        }
        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <div class="badge">VantaPress Module</div>
        <div style="margin-top: 2rem;">
            <a href="{{ route('hello.welcome') }}">Visit Welcome Page →</a>
        </div>
    </div>
</body>
</html>
