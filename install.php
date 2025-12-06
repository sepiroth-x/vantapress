<?php
/**
 * VantaPress - Web Installer
 * For Shared Hosting without SSH access
 */

// Disable error display for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Villain Arise - VantaPress Installation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --villain-red: #dc2626;
            --villain-dark: #0f172a;
            --villain-darker: #020617;
            --villain-gray: #1e293b;
            --villain-light: #f87171;
            --text-light: #d1d5db;
            --text-dark: #6b7280;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Space Mono', monospace;
            background: var(--villain-darker);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated Grid Background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(rgba(220, 38, 38, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(220, 38, 38, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: grid-flow 20s linear infinite;
            opacity: 0.3;
            z-index: 0;
        }
        
        @keyframes grid-flow {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }
        
        .container {
            background: var(--villain-dark);
            border: 2px solid rgba(220, 38, 38, 0.3);
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(220, 38, 38, 0.3);
            max-width: 900px;
            width: 100%;
            padding: 40px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }
        
        .content-area {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            margin-bottom: 20px;
            padding-right: 10px;
        }
        
        .content-area::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-area::-webkit-scrollbar-track {
            background: var(--villain-darker);
            border-radius: 4px;
        }
        
        .content-area::-webkit-scrollbar-thumb {
            background: var(--villain-red);
            border-radius: 4px;
        }
        
        .content-area::-webkit-scrollbar-thumb:hover {
            background: var(--villain-light);
        }
        
        .fixed-actions {
            border-top: 2px solid rgba(220, 38, 38, 0.3);
            padding-top: 20px;
            margin-top: auto;
            background: var(--villain-dark);
        }
        
        h1 {
            color: var(--villain-red);
            margin-bottom: 10px;
            font-size: 36px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-shadow: 0 0 20px rgba(220, 38, 38, 0.5);
        }
        
        .subtitle {
            color: var(--text-dark);
            margin-bottom: 30px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .step {
            background: var(--villain-darker);
            border-left: 4px solid var(--villain-red);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }
        
        .step h2 {
            color: var(--villain-red);
            font-size: 20px;
            margin-bottom: 10px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 12px 15px;
            border-radius: 4px;
            border: 1px solid;
        }
        
        .status.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border-color: rgba(16, 185, 129, 0.3);
        }
        
        .status.error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        .status.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border-color: rgba(245, 158, 11, 0.3);
        }
        
        .icon {
            font-size: 20px;
            margin-right: 10px;
            font-weight: bold;
        }
        
        button {
            background: var(--villain-red);
            color: white;
            border: 2px solid var(--villain-red);
            padding: 14px 32px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        button:hover {
            background: var(--villain-light);
            border-color: var(--villain-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
        }
        
        button:disabled {
            background: var(--villain-gray);
            border-color: var(--villain-gray);
            cursor: not-allowed;
            transform: none;
        }
        
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        code {
            background: rgba(220, 38, 38, 0.1);
            color: var(--villain-light);
            padding: 3px 8px;
            border-radius: 3px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }
        
        pre {
            background: var(--villain-darker);
            color: var(--text-light);
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 10px 0;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }
        
        .log {
            background: var(--villain-darker);
            border: 1px solid rgba(220, 38, 38, 0.3);
            padding: 15px;
            border-radius: 4px;
            max-height: 300px;
            min-height: 150px;
            overflow-y: auto;
            overflow-x: auto;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            line-height: 1.8;
            color: var(--text-light);
            margin-bottom: 20px;
        }
        
        .log::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .log::-webkit-scrollbar-track {
            background: var(--villain-darker);
            border-radius: 4px;
        }
        
        .log::-webkit-scrollbar-thumb {
            background: var(--villain-red);
            border-radius: 4px;
        }
        
        .log::-webkit-scrollbar-thumb:hover {
            background: var(--villain-light);
        }
        
        .progress {
            background: var(--villain-darker);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 4px;
            height: 35px;
            margin: 20px 0;
            overflow: hidden;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--villain-red), var(--villain-light));
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: width 0.3s;
            font-size: 13px;
        }
        
        label {
            color: var(--text-light);
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        
        label small {
            font-weight: 400;
            color: var(--text-dark);
            font-size: 12px;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            background: var(--villain-darker);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 4px;
            color: var(--text-light);
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: var(--villain-red);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        
        input[type="text"]::placeholder,
        input[type="password"]::placeholder,
        input[type="email"]::placeholder {
            color: var(--text-dark);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }
            
            h1 {
                font-size: 28px;
            }
            
            .actions {
                flex-direction: column;
            }
            
            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ VantaPress</h1>
        <p class="subtitle">The Villain Arise • Installation Wizard</p>

        <div class="content-area">
        <?php
        $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        // Step 1: Requirements Check
        if ($step === 1) {
            ?>
            <div class="step">
                <h2>Step 1: System Requirements</h2>
                <p>Checking your server environment...</p>
            </div>

            <?php
            // Check if .env exists, if not show helpful message
            if (!file_exists(__DIR__ . '/.env')) {
                echo "<div class='status error'><span class='icon'>✗</span> .env file not found</div>";
                echo "<div class='status warning'>";
                echo "<span class='icon'>⚠</span> ";
                echo "<strong>Action Required:</strong> Please rename <code>.env.example</code> to <code>.env</code> before continuing.";
                echo "</div>";
                echo "<p style='margin-top: 20px;'>After renaming the file, refresh this page to continue the installation.</p>";
                echo "</div></div></div></body></html>";
                exit;
            }
            
            $checks = [
                'PHP Version >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'vendor/ directory exists' => is_dir(__DIR__ . '/vendor'),
                'bootstrap/ directory exists' => is_dir(__DIR__ . '/bootstrap'),
                '.env file exists' => file_exists(__DIR__ . '/.env'),
                'storage/ directory writable' => is_writable(__DIR__ . '/storage'),
                'bootstrap/cache/ writable' => is_writable(__DIR__ . '/bootstrap/cache'),
            ];

            $allPassed = true;
            foreach ($checks as $check => $passed) {
                $allPassed = $allPassed && $passed;
                $class = $passed ? 'success' : 'error';
                $icon = $passed ? '✓' : '✗';
                echo "<div class='status $class'><span class='icon'>$icon</span> $check</div>";
            }

            // Auto-generate APP_KEY if .env exists but APP_KEY is missing or empty
            if (file_exists(__DIR__ . '/.env')) {
                $envPath = __DIR__ . '/.env';
                $envContent = file_get_contents($envPath);
                $needsKey = false;
                
                if (preg_match('/^APP_KEY=(.*)$/m', $envContent, $matches)) {
                    $currentKey = trim($matches[1]);
                    if (empty($currentKey)) {
                        $needsKey = true;
                    }
                } else {
                    $needsKey = true;
                }
                
                if ($needsKey) {
                    echo "<div class='status warning'>";
                    echo "<span class='icon'>🔑</span> APP_KEY is missing or empty. Generating encryption key...";
                    echo "</div>";
                    
                    try {
                        // Generate secure random key
                        $appKey = 'base64:' . base64_encode(random_bytes(32));
                        
                        // Update or add APP_KEY in .env
                        if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
                            $newEnvContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=$appKey", $envContent);
                        } else {
                            // Add APP_KEY after APP_URL if it exists, or at the beginning
                            if (preg_match('/^APP_URL=.*$/m', $envContent)) {
                                $newEnvContent = preg_replace('/^(APP_URL=.*)$/m', "$1\nAPP_KEY=$appKey", $envContent);
                            } else {
                                $newEnvContent = "APP_KEY=$appKey\n" . $envContent;
                            }
                        }
                        
                        // Also set APP_VERSION if not present
                        if (!preg_match('/^APP_VERSION=.*$/m', $newEnvContent)) {
                            $newEnvContent = preg_replace('/^(APP_KEY=.*)$/m', "$1\nAPP_VERSION=1.0.35-complete", $newEnvContent);
                        }
                        
                        $writeSuccess = file_put_contents($envPath, $newEnvContent);
                        
                        if ($writeSuccess !== false) {
                            echo "<div class='status success'>";
                            echo "<span class='icon'>✓</span> APP_KEY automatically generated and saved!";
                            echo "</div>";
                            $allPassed = $allPassed && true;
                        } else {
                            echo "<div class='status error'>";
                            echo "<span class='icon'>✗</span> Could not write APP_KEY to .env file. Check file permissions.";
                            echo "</div>";
                            $allPassed = false;
                        }
                    } catch (Exception $e) {
                        echo "<div class='status error'>";
                        echo "<span class='icon'>✗</span> Error generating APP_KEY: " . htmlspecialchars($e->getMessage());
                        echo "</div>";
                        $allPassed = false;
                    }
                } else {
                    echo "<div class='status success'>";
                    echo "<span class='icon'>✓</span> APP_KEY is already configured";
                    echo "</div>";
                }
            }

            if ($allPassed) {
                echo "<div class='status success' style='margin-top:20px;'>";
                echo "<span class='icon'>✓</span> All requirements met! Ready to proceed.";
                echo "</div>";
            } else {
                echo "<div class='status error'>";
                echo "<span class='icon'>⚠</span> Please fix the errors above before continuing.";
                echo "</div>";
            }
            ?>
            </div><!-- end content-area -->
            
            <?php if ($allPassed) { ?>
            <div class='fixed-actions'>
                <div class='actions'>
                    <button onclick='location.href="?step=2"'>Continue to Database Setup →</button>
                </div>
            </div>
            <?php } ?>
            <?php
        }

        // Step 2: Database Configuration
        elseif ($step === 2) {
            echo "<div class='content-area'>";
            // Handle database configuration form submission
            if ($action === 'test_db' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $dbHost = $_POST['db_host'] ?? '';
                $dbName = $_POST['db_name'] ?? '';
                $dbUser = $_POST['db_user'] ?? '';
                $dbPass = $_POST['db_pass'] ?? '';

                echo "<div class='step'>";
                echo "<h2>Step 2: Database Configuration</h2>";
                echo "<p>Testing database connection...</p>";
                echo "</div>";

                try {
                    // Test connection
                    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    echo "<div class='status success'>";
                    echo "<span class='icon'>✓</span> Database connection successful!";
                    echo "</div>";

                    echo "<div class='status warning'>";
                    echo "<span class='icon'>ℹ</span> Database: <code>$dbName</code> on <code>$dbHost</code>";
                    echo "</div>";

                    // Check if migrations already run
                    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (count($tables) > 0) {
                        echo "<div class='status warning'>";
                        echo "<span class='icon'>⚠</span> Database already contains " . count($tables) . " tables. ";
                        echo "Running migrations will create additional tables.";
                        echo "</div>";
                    }

                    // Update .env file
                    $envPath = __DIR__ . '/.env';
                    if (file_exists($envPath)) {
                        $envContent = file_get_contents($envPath);
                        
                        // Update database credentials
                        $envContent = preg_replace('/DB_HOST=.*/', "DB_HOST=$dbHost", $envContent);
                        $envContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE=$dbName", $envContent);
                        $envContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME=$dbUser", $envContent);
                        $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$dbPass", $envContent);
                        
                        file_put_contents($envPath, $envContent);
                        
                    echo "<div class='status success'>";
                    echo "<span class='icon'>✓</span> .env file updated with database credentials";
                    echo "</div>";
                }

                echo "<div class='status success' style='margin-top:20px;'>";
                echo "<span class='icon'>🎉</span> Database connection successful! Ready to create tables.";
                echo "</div>";
                
                echo "</div><!-- end content-area -->";
                echo "<div class='fixed-actions'><div class='actions'>";
                echo "<button onclick='location.href=\"?step=3\"'>Continue to Database Migration →</button>";
                echo "</div></div>";
                
                } catch (PDOException $e) {
                    echo "<div class='status error'>";
                    echo "<span class='icon'>✗</span> Database connection failed: " . htmlspecialchars($e->getMessage());
                    echo "</div>";
                    
                    echo "<div class='status warning'>";
                    echo "<span class='icon'>ℹ</span> Common issues:<br>";
                    echo "• Check hostname (often different from 'localhost' on shared hosting)<br>";
                    echo "• Verify database exists in hosting control panel<br>";
                    echo "• Confirm username and password are correct<br>";
                    echo "• Ensure database user has proper permissions";
                    echo "</div>";

                    echo "</div><!-- end content-area -->";
                    echo "<div class='fixed-actions'><div class='actions'>";
                    echo "<button onclick='location.href=\"?step=2\"'>← Try Again</button>";
                    echo "</div></div>";
                }
            } else {
                // Show database configuration form
                require __DIR__ . '/vendor/autoload.php';
                
                // Try to load existing .env values
                $envPath = __DIR__ . '/.env';
                $dbHost = 'localhost';
                $dbName = '';
                $dbUser = '';
                $dbPass = '';
                
                if (file_exists($envPath)) {
                    try {
                        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
                        $dotenv->load();
                        $dbHost = $_ENV['DB_HOST'] ?? 'localhost';
                        $dbName = $_ENV['DB_DATABASE'] ?? '';
                        $dbUser = $_ENV['DB_USERNAME'] ?? '';
                        $dbPass = $_ENV['DB_PASSWORD'] ?? '';
                    } catch (Exception $e) {
                        // Ignore if .env can't be loaded
                    }
                }
                ?>
                <div class="step">
                    <h2>Step 2: Database Configuration</h2>
                    <p>Enter your MySQL database credentials...</p>
                </div>

                <div class='status warning'>
                    <span class='icon'>ℹ</span> <strong>Where to find these credentials?</strong><br><br>
                    • Log in to your hosting control panel (e.g., cPanel, Plesk)<br>
                    • Navigate to "MySQL Databases" or "Database Manager"<br>
                    • Create a new database if you haven't already<br>
                    • Note the database name, username, password, and hostname
                </div>

                <form method="POST" action="?step=2&action=test_db" style="margin-top: 20px;">
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600; color:#333;">
                            Database Host:
                            <small style="font-weight:400; color:#666;">(e.g., localhost, sv65.ifastnet14.org)</small>
                        </label>
                        <input type="text" name="db_host" value="<?php echo htmlspecialchars($dbHost); ?>" required 
                               placeholder="localhost"
                               style="width:100%; padding:12px; border:2px solid #ddd; border-radius:6px; font-size:15px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600; color:#333;">
                            Database Name:
                            <small style="font-weight:400; color:#666;">(e.g., vantapress_db)</small>
                        </label>
                        <input type="text" name="db_name" value="<?php echo htmlspecialchars($dbName); ?>" required 
                               placeholder="vantapress_db"
                               style="width:100%; padding:12px; border:2px solid #ddd; border-radius:6px; font-size:15px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600; color:#333;">
                            Database Username:
                        </label>
                        <input type="text" name="db_user" value="<?php echo htmlspecialchars($dbUser); ?>" required 
                               placeholder="database_user"
                               style="width:100%; padding:12px; border:2px solid #ddd; border-radius:6px; font-size:15px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600; color:#333;">
                            Database Password:
                        </label>
                        <input type="password" name="db_pass" value="<?php echo htmlspecialchars($dbPass); ?>" required 
                               placeholder="••••••••"
                               style="width:100%; padding:12px; border:2px solid #ddd; border-radius:6px; font-size:15px;">
                        <small style="color:#666; font-size:13px;">
                            💡 Tip: Your password will be saved securely in the .env file
                        </small>
                    </div>
                    
                    <div class='status warning'>
                        <span class='icon'>⚠</span> <strong>For iFastNet users:</strong><br>
                        • Hostname is usually your server (e.g., sv65.ifastnet14.org)<br>
                        • Database name includes your prefix (e.g., username_dbname)<br>
                        • Username also includes your prefix (e.g., username_dbuser)
                    </div>
                </form>
                </div><!-- end content-area -->
                
                <div class='fixed-actions'>
                    <div class='actions'>
                        <button type="button" onclick='location.href="?step=1"' 
                                style="background:#6c757d;">← Back</button>
                        <button type="submit" form="dbForm">Test Connection & Continue →</button>
                    </div>
                </div>
                
                <script>
                // Make form submittable from fixed button
                document.querySelector('form').id = 'dbForm';
                </script>
                <?php
            }
        }

        // Step 3: Run Migrations
        elseif ($step === 3) {
            ?>
            <div class="content-area">
            <div class="step">
                <h2>Step 3: Database Migration</h2>
                <p>Creating database tables and seeding initial data...</p>
            </div>

            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%">0%</div>
            </div>

            <div class="log" id="logOutput">Starting migration process...<br></div>
            </div><!-- end content-area -->
            
            <div class='fixed-actions'>
                <div class='actions' id='nextStep' style='display:none;'>
                    <button onclick='location.href="?step=4"'>Continue to Asset Setup →</button>
                </div>
            </div>

            <script>
                let progress = 0;
                const progressBar = document.getElementById('progressBar');
                const logOutput = document.getElementById('logOutput');

                function updateProgress(percent, message) {
                    progress = percent;
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                    if (message) {
                        logOutput.innerHTML += message + '<br>';
                        logOutput.scrollTop = logOutput.scrollHeight;
                    }
                }

                async function runMigrations() {
                    updateProgress(10, 'Loading Laravel application...');
                    
                    try {
                        const response = await fetch('?step=3&action=migrate');
                        const text = await response.text();
                        
                        updateProgress(100, text);
                        
                        setTimeout(() => {
                            document.getElementById('nextStep').style.display = 'flex';
                        }, 1000);
                    } catch (error) {
                        updateProgress(100, '❌ Error: ' + error.message);
                    }
                }

                // Auto-start migration
                setTimeout(runMigrations, 500);
            </script>

            <?php
            if ($action === 'migrate') {
                // Execute migrations using raw SQL for shared hosting compatibility
                // Bypasses Laravel's migration system which queries information_schema
                ob_start();
                
                try {
                    require __DIR__ . '/vendor/autoload.php';
                    
                    // Load environment directly from .env file to avoid caching issues
                    $envPath = __DIR__ . '/.env';
                    $dbConfig = [];
                    
                    if (!file_exists($envPath)) {
                        throw new Exception(".env file not found. Please complete Step 2 first.");
                    }
                    
                    // Parse .env file manually
                    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
                        list($key, $value) = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        $dbConfig[$key] = $value;
                        // Also set in $_ENV for Laravel to use
                        $_ENV[$key] = $value;
                        putenv("$key=$value");
                    }
                    
                    if (empty($dbConfig['DB_HOST']) || empty($dbConfig['DB_DATABASE'])) {
                        throw new Exception("Database configuration incomplete in .env file. Please complete Step 2 first.");
                    }
                    
                    echo "🔍 Connecting to database...<br>";
                    echo "Host: {$dbConfig['DB_HOST']}<br>";
                    echo "Database: {$dbConfig['DB_DATABASE']}<br>";
                    echo "User: {$dbConfig['DB_USERNAME']}<br><br>";
                    
                    // Ensure storage directories exist before bootstrapping Laravel
                    echo "📁 Checking storage directories...<br>";
                    $storageDirs = [
                        __DIR__ . '/storage/framework/cache/data',
                        __DIR__ . '/storage/framework/sessions',
                        __DIR__ . '/storage/framework/views',
                        __DIR__ . '/storage/logs',
                        __DIR__ . '/bootstrap/cache'
                    ];
                    
                    foreach ($storageDirs as $dir) {
                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                            echo "✓ Created: " . basename(dirname($dir)) . "/" . basename($dir) . "<br>";
                        }
                    }
                    echo "✓ Storage directories ready<br><br>";
                    
                    // Bootstrap Laravel application
                    echo "🚀 Bootstrapping Laravel...<br>";
                    $app = require_once __DIR__ . '/bootstrap/app.php';
                    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
                    echo "✓ Laravel initialized<br><br>";
                    
                    $pdo = new PDO(
                        "mysql:host={$dbConfig['DB_HOST']};dbname={$dbConfig['DB_DATABASE']};charset=utf8mb4",
                        $dbConfig['DB_USERNAME'],
                        $dbConfig['DB_PASSWORD'],
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    
                    echo "✓ Database connected<br><br>";
                    
                    // Create migrations table using direct SQL (shared hosting compatible)
                    echo "📦 Creating migrations table...<br>";
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS migrations (
                            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            migration VARCHAR(255) NOT NULL,
                            batch INT NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                    ");
                    echo "✓ Migrations table ready<br><br>";
                    
                    // Get migration files
                    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
                    sort($migrationFiles);
                    
                    echo "📂 Found " . count($migrationFiles) . " migration files<br><br>";
                    
                    // Get already run migrations
                    $stmt = $pdo->query("SELECT migration FROM migrations");
                    $ranMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (count($ranMigrations) > 0) {
                        echo "⊘ Already ran: " . count($ranMigrations) . " migrations<br><br>";
                    }
                    
                    // Get next batch number
                    $stmt = $pdo->query("SELECT COALESCE(MAX(batch), 0) + 1 as next_batch FROM migrations");
                    $batch = $stmt->fetch(PDO::FETCH_ASSOC)['next_batch'];
                    
                    // Run new migrations using raw SQL
                    echo "🔄 Running migrations...<br><br>";
                    $newMigrations = 0;
                    $failedMigrations = [];
                    
                    foreach ($migrationFiles as $file) {
                        $migrationName = basename($file, '.php');
                        
                        if (in_array($migrationName, $ranMigrations)) {
                            echo "→ Skipped: $migrationName (already ran)<br>";
                            continue;
                        }
                        
                        echo "→ Running: $migrationName<br>";
                        
                        try {
                            // Laravel 11+ uses anonymous classes that return the migration instance
                            // Execute the file and capture the returned migration object
                            $migration = require $file;
                            
                            // Check if we got a valid migration object
                            if (!is_object($migration)) {
                                throw new Exception("Migration file did not return a migration object");
                            }
                            
                            // Check if up() method exists
                            if (!method_exists($migration, 'up')) {
                                throw new Exception("Migration object does not have an up() method");
                            }
                            
                            // Execute the migration
                            $migration->up();
                            
                            // Record migration in database
                            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                            $stmt->execute([$migrationName, $batch]);
                            
                            echo "  ✓ Completed successfully<br>";
                            $newMigrations++;
                            
                        } catch (PDOException $e) {
                            echo "  ❌ Database Error: " . htmlspecialchars($e->getMessage()) . "<br>";
                            $failedMigrations[] = ['file' => $migrationName, 'error' => $e->getMessage()];
                        } catch (Exception $e) {
                            echo "  ❌ Error: " . htmlspecialchars($e->getMessage()) . "<br>";
                            $failedMigrations[] = ['file' => $migrationName, 'error' => $e->getMessage()];
                        }
                    }
                    
                    echo "<br>✅ Migration process complete!<br>";
                    echo "✓ Successful migrations: $newMigrations<br>";
                    
                    if (count($failedMigrations) > 0) {
                        echo "❌ Failed migrations: " . count($failedMigrations) . "<br><br>";
                        echo "Failed migration details:<br>";
                        foreach ($failedMigrations as $failed) {
                            echo "  • {$failed['file']}: {$failed['error']}<br>";
                        }
                        echo "<br>";
                    }
                    
                    echo "<br>";
                    
                    // Sync themes and modules from filesystem to database
                    echo "🔄 Syncing themes and modules...<br><br>";
                    try {
                        // Manually sync modules and themes (avoiding seeder command dependency)
                        $moduleLoader = app(\App\Services\ModuleLoader::class);
                        $modules = $moduleLoader->discoverModules();
                        
                        foreach ($modules as $slug => $metadata) {
                            \App\Models\Module::updateOrCreate(
                                ['slug' => $slug],
                                [
                                    'name' => $metadata['name'] ?? $slug,
                                    'description' => $metadata['description'] ?? '',
                                    'version' => $metadata['version'] ?? '1.0.0',
                                    'author' => $metadata['author'] ?? '',
                                    'is_enabled' => $metadata['active'] ?? false,
                                    'path' => $metadata['path'] ?? '',
                                    'config' => $metadata,
                                ]
                            );
                        }
                        echo "✓ Modules synced from file system<br>";
                        
                        $themeLoader = app(\App\Services\ThemeLoader::class);
                        $themes = $themeLoader->discoverThemes();
                        
                        foreach ($themes as $slug => $metadata) {
                            \App\Models\Theme::updateOrCreate(
                                ['slug' => $slug],
                                [
                                    'name' => $metadata['name'] ?? $slug,
                                    'description' => $metadata['description'] ?? '',
                                    'version' => $metadata['version'] ?? '1.0.0',
                                    'author' => $metadata['author'] ?? '',
                                    'is_active' => $metadata['active'] ?? false,
                                    'path' => $metadata['path'] ?? '',
                                    'config' => $metadata,
                                ]
                            );
                        }
                        echo "✓ Themes synced from file system<br>";
                        
                        // Ensure at least one theme is active
                        \App\Models\Theme::ensureActiveTheme();
                        echo "✓ Default theme activated<br>";
                        
                        echo "<br>✅ Themes and modules synced successfully!<br>";
                    } catch (Exception $e) {
                        echo "⚠️  Warning: Could not sync themes/modules: " . htmlspecialchars($e->getMessage()) . "<br>";
                        echo "   You can manually sync them later by running: php artisan db:seed --class=ModuleThemeSeeder<br>";
                    }
                    
                    echo "<br>";
                    
                    // Show created tables
                    echo "📊 Verifying database tables...<br><br>";
                    $stmt = $pdo->query("SHOW TABLES");
                    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    
                    echo "✓ Total tables: " . count($tables) . "<br>";
                    foreach ($tables as $table) {
                        echo "  • $table<br>";
                    }
                    
                } catch (Exception $e) {
                    echo "❌ Migration error: " . htmlspecialchars($e->getMessage()) . "<br>";
                    echo "<pre style='color:#721c24; font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                }
                
                $output = ob_get_clean();
                echo $output;
                $output = ob_get_clean();
                echo $output;
                exit;
            }
        }

        // Step 4: Publish Filament Assets
        elseif ($step === 4) {
            ?>
            <div class="content-area">
            <div class="step">
                <h2>Step 4: Publishing Filament Assets</h2>
                <p>Copying FilamentPHP assets for admin panel styling...</p>
            </div>

            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%">0%</div>
            </div>

            <div class="log" id="logOutput">Starting asset publishing...<br></div>
            </div><!-- end content-area -->
            
            <div class='fixed-actions'>
                <div class='actions' id='nextStep' style='display:none;'>
                    <button onclick='location.href="?step=5"'>Continue to Admin Setup →</button>
                </div>
            </div>

            <script>
                let progress = 0;
                const progressBar = document.getElementById('progressBar');
                const logOutput = document.getElementById('logOutput');

                function updateProgress(percent, message) {
                    progress = percent;
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                    if (message) {
                        logOutput.innerHTML += message + '<br>';
                        logOutput.scrollTop = logOutput.scrollHeight;
                    }
                }

                async function publishAssets() {
                    updateProgress(10, 'Preparing asset directories...');
                    
                    try {
                        const response = await fetch('?step=4&action=publish_assets');
                        const text = await response.text();
                        
                        updateProgress(100, text);
                        
                        setTimeout(() => {
                            document.getElementById('nextStep').style.display = 'flex';
                        }, 1000);
                    } catch (error) {
                        updateProgress(100, '❌ Error: ' + error.message);
                    }
                }

                // Auto-start publishing
                setTimeout(publishAssets, 500);
            </script>

            <?php
            if ($action === 'publish_assets') {
                ob_start();
                
                try {
                    // Use Laravel's Artisan command to publish assets properly
                    require __DIR__ . '/vendor/autoload.php';
                    
                    echo "🚀 Initializing Laravel application...<br>";
                    $app = require_once __DIR__ . '/bootstrap/app.php';
                    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                    
                    echo "📦 Publishing Filament assets...<br><br>";
                    
                    // Capture artisan output
                    $kernel->call('filament:assets');
                    $artisanOutput = $kernel->output();
                    
                    echo "✓ Filament assets published to public/<br>";
                    echo "✓ CSS files: public/css/filament/<br>";
                    echo "✓ JS files: public/js/filament/<br><br>";
                    
                    // Detect deployment structure and handle asset paths
                    echo "🔍 Detecting deployment structure...<br>";
                    
                    $isSubdirectory = file_exists(__DIR__ . '/laravel/public/index.php');
                    $publicPath = $isSubdirectory ? __DIR__ . '/laravel/public' : __DIR__ . '/public';
                    
                    if ($isSubdirectory) {
                        echo "⚠ Subdirectory deployment detected (e.g., iFastNet)<br>";
                        echo "Assets should be in: laravel/public/<br>";
                    } else {
                        echo "✓ Standard deployment structure<br>";
                    }
                    
                    // Check if assets should be accessible from root
                    $needsRootAssets = ($_SERVER['DOCUMENT_ROOT'] === __DIR__ || 
                                       (strpos(__DIR__, 'public_html') !== false && 
                                        strpos(__DIR__, 'laravel') === false));
                    
                    if ($needsRootAssets && !$isSubdirectory) {
                        echo "<br>🔗 Setting up root-level asset access...<br>";
                        
                        // Check if .htaccess properly handles assets
                        $htaccessPath = __DIR__ . '/.htaccess';
                        if (file_exists($htaccessPath)) {
                            $htaccessContent = file_get_contents($htaccessPath);
                            if (strpos($htaccessContent, 'RewriteRule ^(css|js|images') !== false) {
                                echo "✓ .htaccess properly configured for asset serving<br>";
                            } else {
                                echo "⚠ .htaccess may need asset rules (check SERVING_FROM_ROOT.md)<br>";
                            }
                        }
                        
                        // Copy assets from public/ to root for direct access
                        echo "<br>📋 Copying assets from public/ to root...<br>";
                        $dirsToSync = ['css', 'js', 'images', 'fonts'];
                        $copiedCount = 0;
                        
                        foreach ($dirsToSync as $dir) {
                            $sourceDir = __DIR__ . '/public/' . $dir;
                            $destDir = __DIR__ . '/' . $dir;
                            
                            if (is_dir($sourceDir)) {
                                if (!is_dir($destDir)) {
                                    mkdir($destDir, 0755, true);
                                }
                                
                                // Recursively copy
                                $iterator = new RecursiveIteratorIterator(
                                    new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
                                    RecursiveIteratorIterator::SELF_FIRST
                                );
                                
                                foreach ($iterator as $item) {
                                    $target = $destDir . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                                    if ($item->isDir()) {
                                        if (!is_dir($target)) {
                                            mkdir($target, 0755, true);
                                        }
                                    } else {
                                        copy($item, $target);
                                        $copiedCount++;
                                    }
                                }
                                echo "✓ Synced $dir/ directory<br>";
                            }
                        }
                        
                        echo "✓ Copied $copiedCount files to root<br>";
                        echo "✓ Assets now accessible from both /public/* and root /*<br>";
                    }
                    
                    // Clear caches
                    echo "<br>🧹 Clearing configuration cache...<br>";
                    $kernel->call('config:clear');
                    echo "✓ Config cache cleared<br><br>";
                    
                    echo "🎨 Verifying VantaPress assets...<br>";
                    $publicDir = $publicPath;
                    
                    if (file_exists($publicDir . '/css/vantapress-admin.css')) {
                        echo "✓ VantaPress admin CSS found<br>";
                    } else {
                        echo "⚠ VantaPress admin CSS not found (create resources/css/vantapress-admin.css)<br>";
                    }
                    
                    if (file_exists($publicDir . '/images/vantapress-logo.svg')) {
                        echo "✓ VantaPress logo found<br>";
                    } else {
                        echo "⚠ VantaPress logo not found (upload to public/images/)<br>";
                    }
                    
                    if (file_exists($publicDir . '/images/vantapress-icon.svg')) {
                        echo "✓ VantaPress icon found<br>";
                    } else {
                        echo "⚠ VantaPress icon not found (upload to public/images/)<br>";
                    }
                    
                    // Verify critical Filament assets
                    echo "<br>🔍 Verifying Filament assets...<br>";
                    $criticalAssets = [
                        'css/filament/filament/app.css',
                        'css/filament/forms/forms.css',
                        'css/filament/support/support.css',
                        'js/filament/filament/app.js',
                        'js/filament/support/support.js',
                        'js/filament/notifications/notifications.js',
                    ];
                    
                    $missingCount = 0;
                    foreach ($criticalAssets as $asset) {
                        if (file_exists($publicDir . '/' . $asset)) {
                            echo "✓ " . basename($asset) . "<br>";
                        } else {
                            echo "❌ Missing: $asset<br>";
                            $missingCount++;
                        }
                    }
                    
                    if ($missingCount > 0) {
                        echo "<br>⚠ Warning: $missingCount critical assets missing<br>";
                        echo "Run 'php artisan filament:assets' via SSH if available<br>";
                    } else {
                        echo "<br>✅ All Filament assets verified!<br>";
                    }
                    
                    echo "<br>✅ Asset setup complete! Admin panel should be fully styled.<br>";
                    
                } catch (Exception $e) {
                    echo "❌ Asset error: " . htmlspecialchars($e->getMessage()) . "<br>";
                    echo "<pre style='color:#721c24; font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                }
                
                $output = ob_get_clean();
                echo $output;
                exit;
            }
        }

        // Step 5: Create Admin User
        elseif ($step === 5) {
            if ($action === 'create_admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "<div class='content-area'>";
                try {
                    require __DIR__ . '/vendor/autoload.php';
                    
                    // Load environment directly from .env file to avoid caching issues
                    $envPath = __DIR__ . '/.env';
                    $dbConfig = [];
                    
                    if (!file_exists($envPath)) {
                        throw new Exception(".env file not found. Please complete Step 2 first.");
                    }
                    
                    // Parse .env file manually
                    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
                        list($key, $value) = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value, " \t\n\r\0\x0B\"'");
                        $dbConfig[$key] = $value;
                    }
                    
                    if (empty($dbConfig['DB_HOST']) || empty($dbConfig['DB_DATABASE'])) {
                        throw new Exception("Database configuration incomplete in .env file. Please complete Step 2 first.");
                    }
                    
                    $name = $_POST['name'] ?? 'Administrator';
                    $email = $_POST['email'] ?? 'admin@vantapress.com';
                    $password = $_POST['password'] ?? 'admin123';
                    
                    // Connect to database using parsed credentials
                    $pdo = new PDO(
                        "mysql:host={$dbConfig['DB_HOST']};dbname={$dbConfig['DB_DATABASE']};charset=utf8mb4",
                        $dbConfig['DB_USERNAME'],
                        $dbConfig['DB_PASSWORD'],
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    
                    // Hash password using bcrypt with cost of 12 (Laravel default)
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    
                    // Create users table if not exists
                    $pdo->exec("
                        CREATE TABLE IF NOT EXISTS users (
                            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            email VARCHAR(255) NOT NULL UNIQUE,
                            email_verified_at TIMESTAMP NULL,
                            password VARCHAR(255) NOT NULL,
                            remember_token VARCHAR(100) NULL,
                            created_at TIMESTAMP NULL,
                            updated_at TIMESTAMP NULL
                        )
                    ");
                    
                    // Check if admin already exists
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    
                    if ($stmt->fetchColumn() > 0) {
                        echo "<div class='status warning'>";
                        echo "<span class='icon'>⚠</span> User with email <code>$email</code> already exists!";
                        echo "</div>";
                    } else {
                        // Insert admin user
                        $stmt = $pdo->prepare("
                            INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
                            VALUES (?, ?, ?, NOW(), NOW(), NOW())
                        ");
                        $stmt->execute([$name, $email, $hashedPassword]);
                        
                        echo "<div class='status success'>";
                        echo "<span class='icon'>✓</span> Admin user created successfully!";
                        echo "</div>";
                    }
                    
                    echo "<div class='status success'>";
                    echo "<span class='icon'>🔑</span> <strong>Save these credentials:</strong><br><br>";
                    echo "Email: <code>$email</code><br>";
                    echo "Password: <code>" . htmlspecialchars($password) . "</code>";
                    echo "</div>";
                    
                    echo "<div class='status success'>";
                    echo "<span class='icon'>🎉</span> Admin account configured! Ready to complete installation.";
                    echo "</div>";
                    
                    echo "</div><!-- end content-area -->";
                    echo "<div class='fixed-actions'><div class='actions'>";
                    echo "<button onclick='location.href=\"?step=6\"'>Complete Installation →</button>";
                    echo "</div></div>";
                    
                } catch (Exception $e) {
                    echo "<div class='status error'>";
                    echo "<span class='icon'>✗</span> Error: " . htmlspecialchars($e->getMessage());
                    echo "</div>";
                    echo "</div><!-- end content-area -->";
                }
            } else {
                ?>
                <div class="content-area">
                <div class="step">
                    <h2>Step 5: Create Admin Account</h2>
                    <p>Set up your administrator credentials...</p>
                </div>

                <form method="POST" action="?step=5&action=create_admin" id="adminForm">
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600;">Name:</label>
                        <input type="text" name="name" value="Administrator" required 
                               style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:16px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600;">Email:</label>
                        <input type="email" name="email" value="admin@vantapress.com" required 
                               style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:16px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:5px; font-weight:600;">Password:</label>
                        <input type="password" name="password" value="admin123" required 
                               style="width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; font-size:16px;">
                    </div>
                </form>
                </div><!-- end content-area -->
                
                <div class='fixed-actions'>
                    <div class='actions'>
                        <button type="submit" form="adminForm">Create Admin User</button>
                    </div>
                </div>
                <?php
            }
        }

        // Step 6: Complete
        elseif ($step === 6) {
            // Activate Laravel by renaming _index.php back to index.php
            // Files are in ROOT directory (not public/) for shared hosting
            $rootDir = __DIR__;
            $publicDir = __DIR__ . '/public';
            
            // Rename index.html to index-off.html (preserve pre-installation page)
            if (file_exists("$rootDir/index.html")) {
                rename("$rootDir/index.html", "$rootDir/index-off.html");
                echo "<!-- Renamed pre-installation landing page: index.html → index-off.html -->\n";
            }
            
            if (file_exists("$publicDir/index.html")) {
                rename("$publicDir/index.html", "$publicDir/index-off.html");
                echo "<!-- Renamed public/index.html → public/index-off.html -->\n";
            }
            
            // Check both locations and activate whichever exists
            if (file_exists("$rootDir/_index.php")) {
                rename("$rootDir/_index.php", "$rootDir/index.php");
                echo "<!-- Activated Laravel routing (root/index.php) -->\n";
            }
            
            if (file_exists("$publicDir/_index.php")) {
                rename("$publicDir/_index.php", "$publicDir/index.php");
                echo "<!-- Activated Laravel routing (public/index.php) -->\n";
            }
            ?>
            <div class="content-area">
            <div class="step">
                <h2>🎉 Installation Complete!</h2>
                <p>Your VantaPress CMS is ready to use.</p>
            </div>

            <div class='status success'>
                <span class='icon'>✓</span> All installation steps completed successfully!
            </div>

            <div class='status warning'>
                <span class='icon'>⚠</span> <strong>Important Security Steps:</strong><br><br>
                1. <strong>Delete install.php</strong> - Remove this installer file immediately<br>
                2. <strong>Secure .env</strong> - Ensure .env file is not publicly accessible<br>
                3. <strong>Change default password</strong> - Login and change your admin password
            </div>
            </div><!-- end content-area -->
            
            <div class='fixed-actions'>
                <div class='actions'>
                    <button onclick='location.href="/"'>Go to Homepage</button>
                    <button onclick='location.href="/admin"' style='background:#28a745;'>Go to Admin Panel</button>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
