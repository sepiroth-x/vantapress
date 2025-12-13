<?php
/**
 * VantaPress - Emergency App Key Generator
 * PLACE IN ROOT DIRECTORY - Bypasses Laravel routing
 * DELETE AFTER USE
 */

// Prevent Laravel from intercepting this script
if (php_sapi_name() !== 'cli') {
    // Force direct execution
}

function generateKey() {
    return 'base64:' . base64_encode(random_bytes(32));
}

function updateEnv($key) {
    $envPath = __DIR__ . '/.env';
    $examplePath = __DIR__ . '/.env.example';
    
    // Create .env if it doesn't exist
    if (!file_exists($envPath)) {
        if (file_exists($examplePath)) {
            copy($examplePath, $envPath);
            $msg = "Created .env from .env.example\n";
        } else {
            return ['success' => false, 'error' => '.env.example not found'];
        }
    } else {
        $msg = ".env file exists\n";
    }
    
    $content = file_get_contents($envPath);
    
    // Update or add APP_KEY
    if (preg_match('/^APP_KEY=.*$/m', $content)) {
        $content = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$key", $content);
        $msg .= "APP_KEY updated";
    } else {
        // Add after APP_NAME if exists, or at the beginning
        if (preg_match('/^APP_NAME=/m', $content)) {
            $content = preg_replace('/(^APP_NAME=.*$)/m', "$1\nAPP_KEY=$key", $content);
        } else {
            $content = "APP_KEY=$key\n" . $content;
        }
        $msg .= "APP_KEY added";
    }
    
    file_put_contents($envPath, $content);
    
    return ['success' => true, 'message' => $msg, 'key' => $key];
}

// Process
$key = generateKey();
$result = updateEnv($key);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - Key Generated</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 700px;
            width: 100%;
            padding: 40px;
        }
        h1 { color: #333; margin-bottom: 20px; font-size: 32px; }
        .status {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .key-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin: 15px 0;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 14px 32px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        button:hover, a:hover { background: #5568d3; }
        a { 
            background: #667eea;
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
        }
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .steps {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }
        .steps h3 { color: #667eea; margin-bottom: 15px; }
        .steps ol { margin-left: 20px; }
        .steps li { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 App Key Generator</h1>
        
        <?php if ($result['success']): ?>
            <div class="status success">
                <strong>✓ Success!</strong><br>
                <?= nl2br(htmlspecialchars($result['message'])) ?>
            </div>
            
            <div class="key-box">
                <strong>Generated Key:</strong><br>
                <?= htmlspecialchars($result['key']) ?>
            </div>
            
            <div class="steps">
                <h3>Next Steps:</h3>
                <ol>
                    <li><strong>Delete this file</strong> - Remove <code>generate-key.php</code> from root for security</li>
                    <li><strong>Run installer</strong> - Go to <code>/scripts/install.php</code> to configure database</li>
                    <li><strong>Verify .env</strong> - Check that APP_KEY is set in your .env file</li>
                </ol>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="/scripts/install.php">Continue to Installation →</a>
            </div>
            
        <?php else: ?>
            <div class="status error">
                <strong>✗ Error:</strong><br>
                <?= htmlspecialchars($result['error']) ?>
            </div>
            
            <div class="warning">
                <strong>Troubleshooting:</strong><br>
                • Ensure .env.example exists in root directory<br>
                • Check file permissions (web server needs write access)<br>
                • Verify PHP can write to the root directory<br>
                • Contact your hosting provider if permission issues persist
            </div>
        <?php endif; ?>
        
        <div class="warning" style="margin-top: 30px;">
            <strong>🔒 Security Warning:</strong><br>
            DELETE this file (generate-key.php) immediately after use! This script should only run once during initial setup.
        </div>
    </div>
</body>
</html>
