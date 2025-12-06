<?php
/**
 * VantaPress Production Menu Tables Fix
 * 
 * This script safely drops the conflicting menus and menu_items tables
 * Run this ONCE on your production server before using "Update Database Now"
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your VantaPress root directory (same folder as index.php)
 * 2. Visit: https://yourdomain.com/fix-production-menus.php
 * 3. After success, DELETE this file from server for security
 * 4. Then go to /admin/database-updates and click "Update Database Now"
 */

// Load Laravel application
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Use Laravel's Schema facade
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// HTML Header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VantaPress - Production Menu Tables Fix</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dc2626;
            margin-bottom: 10px;
        }
        .warning {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
        }
        .success {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
            padding: 15px;
            margin: 20px 0;
        }
        .info {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
        }
        .error {
            background: #fef2f2;
            border: 2px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            color: #dc2626;
        }
        .step {
            margin: 10px 0;
            padding: 10px;
            background: #f9fafb;
            border-radius: 4px;
        }
        code {
            background: #1f2937;
            color: #10b981;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 VantaPress Production Menu Tables Fix</h1>
        <p><strong>Version:</strong> v1.0.42-complete</p>

        <?php
        try {
            echo '<div class="info">';
            echo '<strong>🔍 Checking for conflicting tables...</strong><br><br>';
            
            $tablesDropped = [];
            $errors = [];
            
            // Check and drop menu_items table first (foreign key dependency)
            if (Schema::hasTable('menu_items')) {
                try {
                    Schema::dropIfExists('menu_items');
                    $tablesDropped[] = 'menu_items';
                    echo '✅ Dropped table: <code>menu_items</code><br>';
                } catch (Exception $e) {
                    $errors[] = 'Failed to drop menu_items: ' . $e->getMessage();
                    echo '❌ Error dropping menu_items: ' . htmlspecialchars($e->getMessage()) . '<br>';
                }
            } else {
                echo '⚠️ Table <code>menu_items</code> does not exist (already deleted)<br>';
            }
            
            // Check and drop menus table
            if (Schema::hasTable('menus')) {
                try {
                    Schema::dropIfExists('menus');
                    $tablesDropped[] = 'menus';
                    echo '✅ Dropped table: <code>menus</code><br>';
                } catch (Exception $e) {
                    $errors[] = 'Failed to drop menus: ' . $e->getMessage();
                    echo '❌ Error dropping menus: ' . htmlspecialchars($e->getMessage()) . '<br>';
                }
            } else {
                echo '⚠️ Table <code>menus</code> does not exist (already deleted)<br>';
            }
            
            echo '</div>';
            
            // Show results
            if (count($tablesDropped) > 0) {
                echo '<div class="success">';
                echo '<strong>✅ Success!</strong><br><br>';
                echo 'Dropped ' . count($tablesDropped) . ' table(s): <code>' . implode('</code>, <code>', $tablesDropped) . '</code><br><br>';
                echo '<strong>Next Steps:</strong><br>';
                echo '<div class="step">1. <strong>DELETE THIS FILE</strong> from your server immediately for security</div>';
                echo '<div class="step">2. Go to <code>/admin/database-updates</code> in your admin panel</div>';
                echo '<div class="step">3. Click <strong>"Update Database Now"</strong> button</div>';
                echo '<div class="step">4. All 26 migrations should run successfully</div>';
                echo '<br><a href="/admin/database-updates" class="btn">Go to Database Updates →</a>';
                echo '</div>';
            } elseif (count($errors) > 0) {
                echo '<div class="error">';
                echo '<strong>❌ Errors Occurred</strong><br><br>';
                echo implode('<br>', array_map('htmlspecialchars', $errors));
                echo '</div>';
            } else {
                echo '<div class="warning">';
                echo '<strong>⚠️ No Tables to Drop</strong><br><br>';
                echo 'The <code>menus</code> and <code>menu_items</code> tables do not exist in your database.<br>';
                echo 'This means either:<br>';
                echo '<div class="step">• They were already dropped successfully</div>';
                echo '<div class="step">• They never existed in this database</div>';
                echo '<br><strong>You can now:</strong><br>';
                echo '<div class="step">1. <strong>DELETE THIS FILE</strong> from your server</div>';
                echo '<div class="step">2. Try running migrations via <code>/admin/database-updates</code></div>';
                echo '<br><a href="/admin/database-updates" class="btn">Go to Database Updates →</a>';
                echo '</div>';
            }
            
        } catch (Exception $e) {
            echo '<div class="error">';
            echo '<strong>❌ Fatal Error</strong><br><br>';
            echo htmlspecialchars($e->getMessage());
            echo '<br><br><strong>Troubleshooting:</strong><br>';
            echo '<div class="step">• Check database credentials in <code>.env</code> file</div>';
            echo '<div class="step">• Ensure database user has DROP TABLE privileges</div>';
            echo '<div class="step">• Contact your hosting support for assistance</div>';
            echo '</div>';
        }
        ?>

        <div class="warning" style="margin-top: 30px;">
            <strong>⚠️ SECURITY REMINDER</strong><br>
            Delete this file (<code>fix-production-menus.php</code>) from your server after use!
        </div>
    </div>
</body>
</html>
