<?php
/**
 * VantaPress - Sync Themes & Modules
 * 
 * This script syncs themes and modules from the filesystem to the database.
 * Run this if your themes/modules aren't showing up in the admin panel.
 * 
 * Usage: Open this file in your browser: https://yourdomain.com/sync-themes-modules.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sync Themes & Modules - VantaPress</title>
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
            max-width: 800px;
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
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
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
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .icon {
            font-size: 20px;
            margin-right: 10px;
            font-weight: bold;
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
        }
        button:hover {
            background: #5568d3;
        }
        pre {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 13px;
            margin-top: 10px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Sync Themes & Modules</h1>
        <p class="subtitle">This will sync themes and modules from the filesystem to the database</p>

        <?php
        if (isset($_POST['sync'])) {
            try {
                // Load Laravel
                require __DIR__ . '/vendor/autoload.php';
                $app = require_once __DIR__ . '/bootstrap/app.php';
                $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

                echo "<div class='status info'><span class='icon'>ℹ️</span> Starting sync process...</div>";

                // Run the seeder
                $seeder = new \Database\Seeders\ModuleThemeSeeder();
                
                // Mock command interface
                $seeder->setCommand(new class {
                    public function info($message) {
                        echo "<div class='status success'><span class='icon'>✓</span> $message</div>";
                    }
                });
                
                $seeder->run();

                echo "<br><div class='status success'><span class='icon'>✅</span> <strong>Sync complete!</strong></div>";
                
                // Show results
                echo "<br><h3>Results:</h3>";
                
                $themeCount = \App\Models\Theme::count();
                $moduleCount = \App\Models\Module::count();
                
                echo "<div class='status info'>";
                echo "<span class='icon'>🎨</span> ";
                echo "<strong>Themes:</strong> $themeCount theme(s) found in database";
                echo "</div>";
                
                echo "<div class='status info'>";
                echo "<span class='icon'>🧩</span> ";
                echo "<strong>Modules:</strong> $moduleCount module(s) found in database";
                echo "</div>";

                echo "<br><p>✓ You can now view your themes and modules in the admin panel!</p>";
                echo "<a href='/admin/themes' class='back-link'>→ View Themes</a><br>";
                echo "<a href='/admin/modules' class='back-link'>→ View Modules</a><br>";
                echo "<a href='/admin' class='back-link'>→ Back to Dashboard</a>";

                echo "<br><br><div class='status success'>";
                echo "<span class='icon'>🗑️</span> ";
                echo "<strong>Security Reminder:</strong> Delete this sync-themes-modules.php file from your server after use!";
                echo "</div>";

            } catch (Exception $e) {
                echo "<div class='status error'>";
                echo "<span class='icon'>❌</span> ";
                echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage());
                echo "</div>";
                
                echo "<br><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                
                echo "<br><p>If you continue to experience issues, try running this command via SSH:</p>";
                echo "<pre>php artisan db:seed --class=ModuleThemeSeeder</pre>";
            }
        } else {
            ?>
            <div class="status info">
                <span class="icon">ℹ️</span>
                <div>
                    <strong>What this does:</strong><br>
                    • Scans your <code>themes/</code> folder for installed themes<br>
                    • Scans your <code>Modules/</code> folder for installed modules<br>
                    • Adds them to the database so they appear in the admin panel<br>
                    • Does NOT delete or modify any existing files
                </div>
            </div>

            <br>

            <div class="status info">
                <span class="icon">⚠️</span>
                <div>
                    <strong>When to use this:</strong><br>
                    • Your themes aren't showing up in the admin panel<br>
                    • Your modules/plugins aren't appearing in the admin panel<br>
                    • After manually uploading theme/module folders via FTP<br>
                    • After restoring from a backup
                </div>
            </div>

            <br>

            <form method="POST">
                <button type="submit" name="sync">Run Sync Now</button>
            </form>

            <br>

            <a href="/admin" class="back-link">← Back to Dashboard</a>
            <?php
        }
        ?>
    </div>
</body>
</html>
