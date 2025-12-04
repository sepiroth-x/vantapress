<?php
/**
 * VantaPress - Quick Diagnostic Tool
 * Upload this file to your server root to diagnose 500 errors
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>VantaPress Diagnostics</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui; background: #f0f2f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .check { padding: 12px; margin: 8px 0; border-radius: 6px; display: flex; align-items: center; }
        .check.ok { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .check.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .check.warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .icon { font-weight: bold; margin-right: 10px; font-size: 18px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 4px; overflow-x: auto; margin: 10px 0; font-size: 12px; }
        .section { margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 6px; }
        .section h2 { margin-bottom: 15px; color: #495057; font-size: 18px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin: 5px; }
        button:hover { background: #0056b3; }
        .code { background: #2d2d2d; color: #f8f8f2; padding: 3px 6px; border-radius: 3px; font-family: monospace; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 VantaPress Diagnostic Tool</h1>
        <p style="color: #666; margin-bottom: 20px;">Checking your server configuration and VantaPress installation...</p>

        <?php
        $errors = [];
        $warnings = [];
        $success = [];

        // Check 1: PHP Version
        echo '<div class="section"><h2>1. PHP Environment</h2>';
        
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '8.2.0', '>=')) {
            echo "<div class='check ok'><span class='icon'>✓</span> PHP Version: <span class='code'>$phpVersion</span> (OK)</div>";
            $success[] = "PHP version compatible";
        } else {
            echo "<div class='check error'><span class='icon'>✗</span> PHP Version: <span class='code'>$phpVersion</span> (Requires PHP 8.2+)</div>";
            $errors[] = "PHP version too old";
        }
        
        // Required extensions
        $requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
        $missingExtensions = [];
        
        foreach ($requiredExtensions as $ext) {
            if (extension_loaded($ext)) {
                echo "<div class='check ok'><span class='icon'>✓</span> Extension: <span class='code'>$ext</span></div>";
            } else {
                echo "<div class='check error'><span class='icon'>✗</span> Missing extension: <span class='code'>$ext</span></div>";
                $missingExtensions[] = $ext;
                $errors[] = "Missing $ext extension";
            }
        }
        
        echo '</div>';

        // Check 2: Directory Structure
        echo '<div class="section"><h2>2. Directory Structure</h2>';
        
        $requiredDirs = [
            'vendor' => 'Composer dependencies',
            'bootstrap' => 'Bootstrap files',
            'bootstrap/cache' => 'Bootstrap cache',
            'storage' => 'Storage directory',
            'storage/framework' => 'Framework storage',
            'storage/framework/cache' => 'Cache directory',
            'storage/framework/sessions' => 'Sessions directory',
            'storage/framework/views' => 'Compiled views',
            'storage/logs' => 'Log files',
            'app' => 'Application code',
            'config' => 'Configuration files',
            'public' => 'Public directory',
        ];
        
        foreach ($requiredDirs as $dir => $desc) {
            $path = __DIR__ . '/' . $dir;
            if (is_dir($path)) {
                $writable = is_writable($path);
                if ($writable || !in_array($dir, ['bootstrap/cache', 'storage', 'storage/framework', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs'])) {
                    echo "<div class='check ok'><span class='icon'>✓</span> <span class='code'>$dir/</span> exists" . ($writable ? " (writable)" : "") . "</div>";
                } else {
                    echo "<div class='check error'><span class='icon'>✗</span> <span class='code'>$dir/</span> exists but not writable</div>";
                    $errors[] = "$dir not writable";
                }
            } else {
                echo "<div class='check error'><span class='icon'>✗</span> <span class='code'>$dir/</span> missing</div>";
                $errors[] = "$dir missing";
            }
        }
        
        echo '</div>';

        // Check 3: Critical Files
        echo '<div class="section"><h2>3. Critical Files</h2>';
        
        $requiredFiles = [
            '.env' => 'Environment configuration',
            'bootstrap/app.php' => 'Application bootstrap',
            'public/index.php' => 'Entry point',
            'vendor/autoload.php' => 'Composer autoloader',
            'artisan' => 'Artisan CLI',
        ];
        
        foreach ($requiredFiles as $file => $desc) {
            $path = __DIR__ . '/' . $file;
            if (file_exists($path)) {
                $readable = is_readable($path);
                echo "<div class='check ok'><span class='icon'>✓</span> <span class='code'>$file</span> exists" . ($readable ? "" : " (NOT readable)") . "</div>";
                if (!$readable) {
                    $errors[] = "$file not readable";
                }
            } else {
                echo "<div class='check error'><span class='icon'>✗</span> <span class='code'>$file</span> missing</div>";
                $errors[] = "$file missing";
            }
        }
        
        echo '</div>';

        // Check 4: .env Configuration
        echo '<div class="section"><h2>4. Environment Configuration</h2>';
        
        $envPath = __DIR__ . '/.env';
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            
            // Check APP_KEY
            if (preg_match('/APP_KEY=base64:/', $envContent)) {
                echo "<div class='check ok'><span class='icon'>✓</span> APP_KEY is set</div>";
            } elseif (preg_match('/APP_KEY=\s*$/', $envContent) || preg_match('/APP_KEY=$/', $envContent)) {
                echo "<div class='check error'><span class='icon'>✗</span> APP_KEY is empty (CRITICAL)</div>";
                $errors[] = "APP_KEY not set";
                echo "<div class='check warning'><span class='icon'>⚠</span> Run install.php to generate APP_KEY or add manually</div>";
            }
            
            // Check Database
            $hasDbHost = preg_match('/DB_HOST=.+/', $envContent);
            $hasDbName = preg_match('/DB_DATABASE=.+/', $envContent);
            $hasDbUser = preg_match('/DB_USERNAME=.+/', $envContent);
            
            if ($hasDbHost && $hasDbName && $hasDbUser) {
                echo "<div class='check ok'><span class='icon'>✓</span> Database credentials configured</div>";
            } else {
                echo "<div class='check warning'><span class='icon'>⚠</span> Database credentials may be incomplete</div>";
                $warnings[] = "Database configuration incomplete";
            }
            
            // Check APP_DEBUG
            if (preg_match('/APP_DEBUG=true/', $envContent)) {
                echo "<div class='check warning'><span class='icon'>⚠</span> APP_DEBUG=true (Disable in production!)</div>";
                $warnings[] = "Debug mode enabled";
            } else {
                echo "<div class='check ok'><span class='icon'>✓</span> APP_DEBUG is false or disabled</div>";
            }
            
            // Show sample .env excerpt (safe parts)
            echo "<p style='margin-top:15px; color:#666;'>Current .env configuration:</p>";
            echo "<pre>";
            $lines = explode("\n", $envContent);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || $line[0] === '#') continue;
                if (strpos($line, '=') === false) continue;
                
                list($key, $value) = explode('=', $line, 2);
                
                // Mask sensitive values
                if (in_array($key, ['DB_PASSWORD', 'APP_KEY', 'MAIL_PASSWORD'])) {
                    $value = '***HIDDEN***';
                }
                
                echo htmlspecialchars($key) . '=' . htmlspecialchars($value) . "\n";
            }
            echo "</pre>";
        } else {
            echo "<div class='check error'><span class='icon'>✗</span> .env file not found</div>";
            $errors[] = ".env file missing";
            echo "<div class='check warning'><span class='icon'>⚠</span> Copy .env.example to .env or run install.php</div>";
        }
        
        echo '</div>';

        // Check 5: Try to Bootstrap Laravel
        echo '<div class="section"><h2>5. Laravel Bootstrap Test</h2>';
        
        try {
            if (file_exists(__DIR__ . '/vendor/autoload.php')) {
                require __DIR__ . '/vendor/autoload.php';
                echo "<div class='check ok'><span class='icon'>✓</span> Composer autoloader loaded</div>";
                
                if (file_exists(__DIR__ . '/bootstrap/app.php')) {
                    try {
                        $app = require_once __DIR__ . '/bootstrap/app.php';
                        echo "<div class='check ok'><span class='icon'>✓</span> Laravel application bootstrapped</div>";
                        
                        // Try to get kernel
                        try {
                            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
                            echo "<div class='check ok'><span class='icon'>✓</span> HTTP Kernel loaded</div>";
                            $success[] = "Laravel boots successfully";
                        } catch (Exception $e) {
                            echo "<div class='check error'><span class='icon'>✗</span> Kernel error: " . htmlspecialchars($e->getMessage()) . "</div>";
                            $errors[] = "Kernel initialization failed";
                        }
                        
                    } catch (Exception $e) {
                        echo "<div class='check error'><span class='icon'>✗</span> Bootstrap error: " . htmlspecialchars($e->getMessage()) . "</div>";
                        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                        $errors[] = "Laravel bootstrap failed";
                    }
                }
            }
        } catch (Exception $e) {
            echo "<div class='check error'><span class='icon'>✗</span> Fatal error: " . htmlspecialchars($e->getMessage()) . "</div>";
            $errors[] = "Critical initialization error";
        }
        
        echo '</div>';

        // Check 6: Server Info
        echo '<div class="section"><h2>6. Server Information</h2>';
        echo "<div class='check ok'><span class='icon'>ℹ</span> Server Software: <span class='code'>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</span></div>";
        echo "<div class='check ok'><span class='icon'>ℹ</span> Document Root: <span class='code'>" . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</span></div>";
        echo "<div class='check ok'><span class='icon'>ℹ</span> Script Path: <span class='code'>" . __DIR__ . "</span></div>";
        echo "<div class='check ok'><span class='icon'>ℹ</span> PHP SAPI: <span class='code'>" . php_sapi_name() . "</span></div>";
        
        $memoryLimit = ini_get('memory_limit');
        echo "<div class='check ok'><span class='icon'>ℹ</span> Memory Limit: <span class='code'>$memoryLimit</span></div>";
        
        $maxExec = ini_get('max_execution_time');
        echo "<div class='check ok'><span class='icon'>ℹ</span> Max Execution Time: <span class='code'>{$maxExec}s</span></div>";
        
        echo '</div>';

        // Summary
        echo '<div class="section">';
        echo '<h2>📊 Summary</h2>';
        
        $totalChecks = count($success) + count($errors) + count($warnings);
        
        if (count($errors) === 0) {
            echo "<div class='check ok'><span class='icon'>✓</span> <strong>All critical checks passed!</strong></div>";
            echo "<p style='margin-top:15px; color:#666;'>Your server appears to be configured correctly. If you're still getting 500 errors:</p>";
            echo "<ul style='margin:10px 0 10px 30px; color:#666;'>";
            echo "<li>Check server error logs for detailed error messages</li>";
            echo "<li>Verify .htaccess rules in public/ directory</li>";
            echo "<li>Ensure database connection is working</li>";
            echo "<li>Check file permissions (755 for directories, 644 for files)</li>";
            echo "</ul>";
        } else {
            echo "<div class='check error'><span class='icon'>✗</span> <strong>" . count($errors) . " critical issue(s) found</strong></div>";
            echo "<p style='margin-top:15px; color:#721c24;'>Please fix these issues:</p>";
            echo "<ul style='margin:10px 0 10px 30px; color:#721c24;'>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        }
        
        if (count($warnings) > 0) {
            echo "<div class='check warning' style='margin-top:15px;'><span class='icon'>⚠</span> <strong>" . count($warnings) . " warning(s)</strong></div>";
            echo "<ul style='margin:10px 0 10px 30px; color:#856404;'>";
            foreach ($warnings as $warning) {
                echo "<li>$warning</li>";
            }
            echo "</ul>";
        }
        
        echo '</div>';

        // Action Buttons
        echo '<div style="margin-top: 30px; text-align: center;">';
        echo '<button onclick="location.href=\'install.php\'">Run Installer</button>';
        echo '<button onclick="location.reload()" style="background:#6c757d;">Refresh Diagnostic</button>';
        echo '<button onclick="location.href=\'/\'" style="background:#28a745;">Test Homepage</button>';
        echo '<button onclick="location.href=\'/admin\'" style="background:#17a2b8;">Test Admin Panel</button>';
        echo '</div>';
        
        ?>
    </div>
</body>
</html>
