<?php
/**
 * Run Module Migrations
 * For shared hosting - executes migrations from enabled modules
 */

require __DIR__ . '/vendor/autoload.php';

// Load environment
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    die("Error: .env file not found.\n");
}

// Parse .env file manually
$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dbConfig = [];

foreach ($lines as $line) {
    $line = trim($line);
    if (strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value, " \t\n\r\0\x0B\"'");
    $dbConfig[$key] = $value;
    $_ENV[$key] = $value;
    putenv("$key=$value");
}

if (empty($dbConfig['DB_HOST']) || empty($dbConfig['DB_DATABASE'])) {
    die("Error: Database configuration incomplete in .env file.\n");
}

echo "🔌 Connecting to database...\n";
echo "Host: {$dbConfig['DB_HOST']}\n";
echo "Database: {$dbConfig['DB_DATABASE']}\n\n";

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['DB_HOST']};dbname={$dbConfig['DB_DATABASE']};charset=utf8mb4",
        $dbConfig['DB_USERNAME'],
        $dbConfig['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✓ Connected to database\n\n";
    
    // Bootstrap Laravel
    echo "⚡ Bootstrapping Laravel...\n";
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo "✓ Laravel initialized\n\n";
    
    // Get migrations table
    $stmt = $pdo->query("SELECT migration FROM migrations");
    $ranMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📊 Already ran: " . count($ranMigrations) . " migrations\n\n";
    
    // Get next batch number
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch), 0) + 1 as next_batch FROM migrations");
    $batch = $stmt->fetch(PDO::FETCH_ASSOC)['next_batch'];
    
    // Find all module migration directories
    $moduleDirs = glob(__DIR__ . '/Modules/*/migrations');
    
    if (empty($moduleDirs)) {
        echo "⚠ No module migrations found\n";
        exit(0);
    }
    
    echo "🔍 Found " . count($moduleDirs) . " module(s) with migrations\n\n";
    
    $newMigrations = 0;
    $skippedMigrations = 0;
    $failedMigrations = [];
    
    foreach ($moduleDirs as $moduleDir) {
        $moduleName = basename(dirname($moduleDir));
        echo "📦 Processing module: $moduleName\n";
        
        // Get migration files
        $migrationFiles = glob($moduleDir . '/*.php');
        sort($migrationFiles);
        
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.php');
            
            if (in_array($migrationName, $ranMigrations)) {
                echo "  ○ Skipped: $migrationName (already ran)\n";
                $skippedMigrations++;
                continue;
            }
            
            echo "  → Running: $migrationName\n";
            
            try {
                // Load and execute migration
                $migration = require $file;
                
                if (!is_object($migration)) {
                    throw new Exception("Migration file did not return a migration object");
                }
                
                if (!method_exists($migration, 'up')) {
                    throw new Exception("Migration object does not have an up() method");
                }
                
                // Execute the migration
                $migration->up();
                
                // Record migration in database
                $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$migrationName, $batch]);
                
                echo "    ✓ Completed successfully\n";
                $newMigrations++;
                
            } catch (PDOException $e) {
                echo "    ✗ Database Error: " . $e->getMessage() . "\n";
                $failedMigrations[] = ['file' => $migrationName, 'error' => $e->getMessage()];
            } catch (Exception $e) {
                echo "    ✗ Error: " . $e->getMessage() . "\n";
                $failedMigrations[] = ['file' => $migrationName, 'error' => $e->getMessage()];
            }
        }
        
        echo "\n";
    }
    
    echo "🎉 Module migration process complete!\n";
    echo "✓ New migrations run: $newMigrations\n";
    echo "○ Skipped (already ran): $skippedMigrations\n";
    
    if (count($failedMigrations) > 0) {
        echo "✗ Failed migrations: " . count($failedMigrations) . "\n\n";
        echo "Failed migration details:\n";
        foreach ($failedMigrations as $failed) {
            echo "  • {$failed['file']}: {$failed['error']}\n";
        }
    }
    
    // Show created tables
    echo "\n📊 Verifying database tables...\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✓ Total tables: " . count($tables) . "\n";
    
    // Show VP-specific tables
    $vpTables = array_filter($tables, function($table) {
        return strpos($table, 'vp_') === 0;
    });
    
    if (!empty($vpTables)) {
        echo "\nVP Social tables:\n";
        foreach ($vpTables as $table) {
            echo "  • $table\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
