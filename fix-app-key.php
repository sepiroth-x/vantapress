<?php
/**
 * VantaPress - Generate APP_KEY
 * Quick fix for missing application encryption key
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix APP_KEY - VantaPress</title>
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
        h1 { color: #333; margin-bottom: 10px; font-size: 28px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 16px; }
        .status {
            padding: 15px;
            margin: 15px 0;
            border-radius: 6px;
            display: flex;
            align-items: flex-start;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .icon {
            font-size: 20px;
            margin-right: 10px;
            font-weight: bold;
            flex-shrink: 0;
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
            margin: 10px 5px 0 0;
        }
        button:hover { background: #5568d3; }
        button.secondary {
            background: #6c757d;
        }
        button.secondary:hover {
            background: #5a6268;
        }
        code {
            background: #f4f4f4;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-break: break-all;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 15px 0;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔑 Generate APP_KEY</h1>
        <p class="subtitle">Fix the "No application encryption key" error</p>

        <?php
        $envPath = __DIR__ . '/.env';
        
        // Check if .env exists
        if (!file_exists($envPath)) {
            echo "<div class='status error'>";
            echo "<span class='icon'>✗</span>";
            echo "<div>";
            echo "<strong>Error: .env file not found</strong><br><br>";
            echo "The .env file is missing from your VantaPress installation.<br>";
            echo "Please copy .env.example to .env first.";
            echo "</div>";
            echo "</div>";
            
            echo "<button onclick='location.href=\"install.php\"'>Run Full Installer</button>";
            exit;
        }
        
        $action = $_GET['action'] ?? '';
        
        if ($action === 'generate') {
            // Generate new APP_KEY
            echo "<div class='status warning'>";
            echo "<span class='icon'>⚙</span>";
            echo "<div><strong>Generating new encryption key...</strong></div>";
            echo "</div>";
            
            // Read current .env
            $envContent = file_get_contents($envPath);
            
            // Generate secure random key
            $key = 'base64:' . base64_encode(random_bytes(32));
            
            echo "<div class='status success'>";
            echo "<span class='icon'>✓</span>";
            echo "<div><strong>New key generated:</strong><br><code>$key</code></div>";
            echo "</div>";
            
            // Check if APP_KEY already exists in .env
            if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
                // Replace existing APP_KEY
                $newEnvContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$key", $envContent);
                
                echo "<div class='status warning'>";
                echo "<span class='icon'>⚠</span>";
                echo "<div><strong>Updating existing APP_KEY...</strong><br>The old key will be replaced.</div>";
                echo "</div>";
            } else {
                // Add APP_KEY if not present
                $newEnvContent = $envContent . "\nAPP_KEY=$key\n";
                
                echo "<div class='status warning'>";
                echo "<span class='icon'>➕</span>";
                echo "<div><strong>Adding APP_KEY to .env...</strong></div>";
                echo "</div>";
            }
            
            // Write updated .env
            $writeSuccess = file_put_contents($envPath, $newEnvContent);
            
            if ($writeSuccess !== false) {
                echo "<div class='status success'>";
                echo "<span class='icon'>✅</span>";
                echo "<div>";
                echo "<strong>APP_KEY successfully saved to .env!</strong><br><br>";
                echo "Your VantaPress installation should now work correctly.<br>";
                echo "The encryption key is used to secure:<br>";
                echo "• User sessions<br>";
                echo "• Encrypted cookies<br>";
                echo "• Password reset tokens<br>";
                echo "• Any encrypted data in the database";
                echo "</div>";
                echo "</div>";
                
                echo "<div class='status warning'>";
                echo "<span class='icon'>⚠</span>";
                echo "<div>";
                echo "<strong>Important Security Notes:</strong><br><br>";
                echo "1. <strong>Never share your APP_KEY</strong> - Keep it private!<br>";
                echo "2. <strong>Never commit .env to version control</strong><br>";
                echo "3. <strong>Backup your APP_KEY</strong> - You'll need it to decrypt data<br>";
                echo "4. <strong>Delete this fix-app-key.php file</strong> after use";
                echo "</div>";
                echo "</div>";
                
                // Show snippet of updated .env (hide sensitive data)
                echo "<p style='margin-top:20px; color:#666; font-size:14px;'>Updated .env preview (sensitive values hidden):</p>";
                echo "<pre>";
                $lines = explode("\n", $newEnvContent);
                $preview = [];
                foreach (array_slice($lines, 0, 15) as $line) {
                    $line = trim($line);
                    if (empty($line) || $line[0] === '#') {
                        $preview[] = $line;
                        continue;
                    }
                    if (strpos($line, '=') !== false) {
                        list($key, $value) = explode('=', $line, 2);
                        // Show APP_KEY fully, hide others
                        if ($key === 'APP_KEY') {
                            $preview[] = htmlspecialchars($line);
                        } elseif (in_array($key, ['DB_PASSWORD', 'MAIL_PASSWORD'])) {
                            $preview[] = htmlspecialchars($key) . '=***HIDDEN***';
                        } else {
                            $preview[] = htmlspecialchars($line);
                        }
                    }
                }
                echo implode("\n", $preview);
                if (count($lines) > 15) {
                    echo "\n... (" . (count($lines) - 15) . " more lines)";
                }
                echo "</pre>";
                
                echo "<button onclick='location.href=\"/\"'>Go to Homepage</button>";
                echo "<button onclick='location.href=\"/admin\"'>Go to Admin Panel</button>";
                echo "<button class='secondary' onclick='location.href=\"diagnose.php\"'>Run Diagnostics</button>";
                
            } else {
                echo "<div class='status error'>";
                echo "<span class='icon'>✗</span>";
                echo "<div>";
                echo "<strong>Error: Could not write to .env file</strong><br><br>";
                echo "Possible causes:<br>";
                echo "• File permissions are too restrictive<br>";
                echo "• .env file is read-only<br>";
                echo "• Server doesn't have write permissions<br><br>";
                echo "Manual fix:<br>";
                echo "1. Open .env file in a text editor<br>";
                echo "2. Find the line starting with APP_KEY=<br>";
                echo "3. Replace it with: <code>$key</code><br>";
                echo "4. Save the file";
                echo "</div>";
                echo "</div>";
                
                echo "<button class='secondary' onclick='location.href=\"?\"'>Try Again</button>";
            }
            
        } else {
            // Show current status and generate button
            $envContent = file_get_contents($envPath);
            
            echo "<div class='status warning'>";
            echo "<span class='icon'>🔍</span>";
            echo "<div><strong>Checking current .env configuration...</strong></div>";
            echo "</div>";
            
            // Check current APP_KEY
            if (preg_match('/^APP_KEY=(.*)$/m', $envContent, $matches)) {
                $currentKey = trim($matches[1]);
                
                if (empty($currentKey)) {
                    echo "<div class='status error'>";
                    echo "<span class='icon'>✗</span>";
                    echo "<div>";
                    echo "<strong>Problem Found: APP_KEY is empty</strong><br><br>";
                    echo "Current line in .env: <code>APP_KEY=</code><br><br>";
                    echo "This is causing the \"No application encryption key\" error.";
                    echo "</div>";
                    echo "</div>";
                } elseif (!preg_match('/^base64:.+/', $currentKey)) {
                    echo "<div class='status warning'>";
                    echo "<span class='icon'>⚠</span>";
                    echo "<div>";
                    echo "<strong>Warning: APP_KEY format looks incorrect</strong><br><br>";
                    echo "Current value: <code>" . htmlspecialchars(substr($currentKey, 0, 30)) . "...</code><br><br>";
                    echo "It should start with 'base64:' followed by encoded key.";
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<div class='status success'>";
                    echo "<span class='icon'>✓</span>";
                    echo "<div>";
                    echo "<strong>APP_KEY is currently set</strong><br><br>";
                    echo "Value: <code>" . htmlspecialchars(substr($currentKey, 0, 40)) . "...</code><br><br>";
                    echo "If you're still getting errors, you may want to regenerate it.";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='status error'>";
                echo "<span class='icon'>✗</span>";
                echo "<div>";
                echo "<strong>Problem Found: APP_KEY line not found in .env</strong><br><br>";
                echo "The .env file is missing the APP_KEY configuration entirely.";
                echo "</div>";
                echo "</div>";
            }
            
            echo "<div class='status warning'>";
            echo "<span class='icon'>ℹ</span>";
            echo "<div>";
            echo "<strong>What will happen when you generate a new key?</strong><br><br>";
            echo "1. A secure 32-byte random key will be generated<br>";
            echo "2. It will be base64-encoded (Laravel standard)<br>";
            echo "3. Your .env file will be updated with: <code>APP_KEY=base64:xxxxx</code><br>";
            echo "4. Your application will be able to encrypt/decrypt data<br><br>";
            echo "<strong>⚠ Warning:</strong> If you already have encrypted data in the database, ";
            echo "generating a new key will make that data unreadable. Only do this on fresh installations.";
            echo "</div>";
            echo "</div>";
            
            echo "<button onclick='if(confirm(\"Generate new APP_KEY? This will update your .env file.\")) location.href=\"?action=generate\"'>Generate New APP_KEY</button>";
            echo "<button class='secondary' onclick='location.href=\"install.php\"'>Run Full Installer</button>";
            echo "<button class='secondary' onclick='location.href=\"diagnose.php\"'>Run Diagnostics</button>";
        }
        ?>
    </div>
</body>
</html>
