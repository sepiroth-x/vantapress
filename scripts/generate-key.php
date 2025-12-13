<?php
/**
 * VantaPress - App Key Generator
 * Generates Laravel APP_KEY without requiring artisan or database
 */

function generateRandomKey() {
    return 'base64:'.base64_encode(random_bytes(32));
}

function updateEnvFile($key) {
    $envPath = dirname(__DIR__) . '/.env';
    
    if (!file_exists($envPath)) {
        // Create .env from .env.example
        $examplePath = dirname(__DIR__) . '/.env.example';
        if (file_exists($examplePath)) {
            copy($examplePath, $envPath);
            $message = ".env file created from .env.example<br>";
        } else {
            return ['success' => false, 'message' => '.env.example file not found'];
        }
    } else {
        $message = ".env file exists<br>";
    }
    
    $envContent = file_get_contents($envPath);
    
    // Update or add APP_KEY
    if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
        $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$key", $envContent);
        $message .= "APP_KEY updated";
    } else {
        $envContent .= "\nAPP_KEY=$key\n";
        $message .= "APP_KEY added";
    }
    
    file_put_contents($envPath, $envContent);
    
    return ['success' => true, 'message' => $message, 'key' => $key];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - Generate App Key</title>
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
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .status {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 2px solid #bee5eb;
        }
        .key-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
            margin: 15px 0;
            border: 1px solid #dee2e6;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 14px 32px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 600;
            width: 100%;
        }
        button:hover {
            background: #5568d3;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .note {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .next-steps {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .next-steps h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .next-steps ol {
            margin-left: 20px;
        }
        .next-steps li {
            margin: 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Generate App Key</h1>
        <p class="subtitle">VantaPress Application Encryption Key Generator</p>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $key = generateRandomKey();
    $result = updateEnvFile($key);
    
    if ($result['success']) {
        echo '<div class="status success">';
        echo '<span class="icon">✓</span>';
        echo '<strong>Success!</strong><br>';
        echo $result['message'];
        echo '</div>';
        
        echo '<div class="key-display">';
        echo '<strong>Generated Key:</strong><br>';
        echo htmlspecialchars($result['key']);
        echo '</div>';
        
        echo '<div class="next-steps">';
        echo '<h3>Next Steps:</h3>';
        echo '<ol>';
        echo '<li><strong>Verify .env file</strong> - Check that your .env file has been updated with the new APP_KEY</li>';
        echo '<li><strong>Configure Database</strong> - Go to <a href="install.php">install.php</a> to configure your database</li>';
        echo '<li><strong>Delete this file</strong> - For security, delete generate-key.php after installation</li>';
        echo '</ol>';
        echo '</div>';
        
        echo '<div style="margin-top: 20px;">';
        echo '<button onclick="location.href=\'install.php\'">Continue to Installation →</button>';
        echo '</div>';
    } else {
        echo '<div class="status error">';
        echo '<span class="icon">✗</span>';
        echo '<strong>Error:</strong><br>';
        echo $result['message'];
        echo '</div>';
        
        echo '<div class="note">';
        echo '<strong>Troubleshooting:</strong><br>';
        echo '• Ensure .env.example exists in the root directory<br>';
        echo '• Check file permissions (scripts folder needs write access)<br>';
        echo '• Verify the web server can write to the root directory';
        echo '</div>';
    }
} else {
    // Check current status
    $envPath = dirname(__DIR__) . '/.env';
    $hasEnv = file_exists($envPath);
    $hasKey = false;
    $currentKey = null;
    
    if ($hasEnv) {
        $envContent = file_get_contents($envPath);
        if (preg_match('/^APP_KEY=(.+)$/m', $envContent, $matches)) {
            $hasKey = !empty(trim($matches[1]));
            $currentKey = trim($matches[1]);
        }
    }
    
    echo '<div class="status info">';
    echo '<span class="icon">ℹ</span>';
    echo '<strong>Current Status:</strong><br>';
    echo '.env file: ' . ($hasEnv ? '✓ Found' : '✗ Not found') . '<br>';
    echo 'APP_KEY: ' . ($hasKey ? '✓ Set' : '✗ Not set');
    echo '</div>';
    
    if ($hasKey && $currentKey) {
        echo '<div class="key-display">';
        echo '<strong>Current Key:</strong><br>';
        echo htmlspecialchars($currentKey);
        echo '</div>';
        
        echo '<div class="note">';
        echo '<strong>⚠ Warning:</strong> An APP_KEY already exists. ';
        echo 'Generating a new key will invalidate all existing encrypted data (sessions, cookies, etc.). ';
        echo 'Only proceed if you are setting up a fresh installation.';
        echo '</div>';
    }
    
    echo '<form method="POST" style="margin-top: 20px;">';
    echo '<button type="submit" name="generate" value="1">';
    echo $hasKey ? '🔄 Regenerate App Key' : '🔑 Generate App Key';
    echo '</button>';
    echo '</form>';
    
    if (!$hasEnv) {
        echo '<div class="note" style="margin-top: 20px;">';
        echo '<strong>Note:</strong> This will create a new .env file from .env.example and set the APP_KEY.';
        echo '</div>';
    }
}
?>

        <div class="note" style="margin-top: 30px;">
            <strong>🔒 Security Notice:</strong><br>
            Delete this file (generate-key.php) after generating your key. This script should only be used during initial setup.
        </div>
    </div>
</body>
</html>
