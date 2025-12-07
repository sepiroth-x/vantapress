<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About {{ $moduleInfo['name'] }} - VantaPress Module</title>
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
            padding: 2rem;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .content {
            padding: 3rem 2rem;
        }
        .section {
            margin-bottom: 3rem;
        }
        .section h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        .info-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid #667eea;
        }
        .info-item label {
            display: block;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .info-item .value {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .feature-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            border-radius: 0.5rem;
            margin-top: 1.5rem;
        }
        .feature-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
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
            flex-shrink: 0;
        }
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .tech-badge {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .code-example {
            background: #2d3748;
            color: #e2e8f0;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-top: 1.5rem;
            overflow-x: auto;
        }
        .code-example code {
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
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
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            .content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 About {{ $moduleInfo['name'] }}</h1>
            <p>Developer Documentation & Module Information</p>
        </div>

        <div class="content">
            <!-- Module Information -->
            <section class="section">
                <h2>ℹ️ Module Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Module Name</label>
                        <div class="value">{{ $moduleInfo['name'] }}</div>
                    </div>
                    <div class="info-item">
                        <label>Version</label>
                        <div class="value">{{ $moduleInfo['version'] }}</div>
                    </div>
                    <div class="info-item">
                        <label>Author</label>
                        <div class="value">{{ $moduleInfo['author'] }}</div>
                    </div>
                    <div class="info-item">
                        <label>License</label>
                        <div class="value">{{ $moduleInfo['license'] }}</div>
                    </div>
                </div>
                
                <div class="feature-box">
                    <p style="color: #666; line-height: 1.6;">
                        {{ $moduleInfo['description'] }}
                    </p>
                </div>
            </section>

            <!-- Features -->
            <section class="section">
                <h2>✨ What This Module Demonstrates</h2>
                <div class="feature-box">
                    <ul class="feature-list">
                        <li>Clean MVC Architecture</li>
                        <li>Laravel Blade Templates</li>
                        <li>RESTful Routing</li>
                        <li>JSON API Endpoints</li>
                        <li>Form Validation</li>
                        <li>Responsive Design</li>
                        <li>Module Metadata</li>
                        <li>Code Documentation</li>
                        <li>Best Practices</li>
                        <li>Production Ready</li>
                    </ul>
                </div>
            </section>

            <!-- Technology Stack -->
            <section class="section">
                <h2>🛠️ Technology Stack</h2>
                <div class="tech-stack">
                    <span class="tech-badge">PHP 8.2+</span>
                    <span class="tech-badge">Laravel 11</span>
                    <span class="tech-badge">Blade Templates</span>
                    <span class="tech-badge">FilamentPHP 3.3</span>
                    <span class="tech-badge">HTML5</span>
                    <span class="tech-badge">CSS3</span>
                    <span class="tech-badge">No Build Tools</span>
                </div>
            </section>

            <!-- Code Example -->
            <section class="section">
                <h2>💻 Quick Example</h2>
                <p style="color: #666; margin-bottom: 1rem;">
                    Creating a new route in your module is as simple as:
                </p>
                <div class="code-example">
                    <code>
// routes.php<br>
Route::get('/my-page', [MyController::class, 'index'])<br>
&nbsp;&nbsp;&nbsp;&nbsp;->name('mymodule.page');<br>
<br>
// Controller<br>
public function index()<br>
{<br>
&nbsp;&nbsp;&nbsp;&nbsp;return view('MyModule::page', [<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'title' => 'My Page',<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'data' => $this->getData(),<br>
&nbsp;&nbsp;&nbsp;&nbsp;]);<br>
}
                    </code>
                </div>
            </section>

            <!-- Getting Started -->
            <section class="section">
                <h2>🚀 Getting Started</h2>
                <p style="color: #666; margin-bottom: 1rem;">
                    Ready to create your own module? Follow these steps:
                </p>
                <ol style="color: #666; line-height: 2; padding-left: 2rem;">
                    <li>Copy the <strong>HelloWorld</strong> module folder</li>
                    <li>Rename it to your module name (e.g., <strong>MyPlugin</strong>)</li>
                    <li>Update <code>module.json</code> with your details</li>
                    <li>Create your routes in <code>routes.php</code></li>
                    <li>Add controllers and views as needed</li>
                    <li>ZIP the module folder</li>
                    <li>Install via admin panel: <strong>Extensions → Modules</strong></li>
                </ol>
                
                <div class="feature-box" style="margin-top: 1.5rem;">
                    <strong style="color: #333;">📖 Full Documentation</strong><br>
                    <span style="color: #666;">
                        Check the <code>README.md</code> file in this module for complete 
                        development guide, code examples, and best practices.
                    </span>
                </div>
            </section>

            <!-- Navigation -->
            <section class="section">
                <h2>🔗 Explore More</h2>
                <div class="links">
                    <a href="{{ route('hello.index') }}" class="btn btn-primary">
                        ← Module Home
                    </a>
                    <a href="{{ route('hello.welcome') }}" class="btn btn-secondary">
                        Welcome Page
                    </a>
                    <a href="{{ route('hello.api') }}" class="btn btn-secondary">
                        API Example
                    </a>
                    <a href="/admin/modules" class="btn btn-secondary">
                        Manage Modules
                    </a>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
