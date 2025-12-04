<?php
/**
 * Sync Filament Assets from public/ to root/
 * 
 * VantaPress v1.0.10 uses root-level structure (no public/ folder).
 * This script copies Filament assets from public/ to root after publishing.
 * 
 * Usage: php sync-filament-assets.php
 */

echo "Syncing Filament assets from public/ to root/...\n\n";

// Copy CSS assets
if (is_dir('public/css/filament')) {
    echo "📋 Copying CSS assets...\n";
    
    // Remove old root assets
    if (is_dir('css/filament')) {
        rrmdir('css/filament');
    }
    
    // Copy from public to root
    rcopy('public/css/filament', 'css/filament');
    echo "   ✓ CSS copied to css/filament/\n";
} else {
    echo "   ⚠ No public/css/filament found\n";
}

// Copy JS assets
if (is_dir('public/js/filament')) {
    echo "📋 Copying JS assets...\n";
    
    // Remove old root assets
    if (is_dir('js/filament')) {
        rrmdir('js/filament');
    }
    
    // Copy from public to root
    rcopy('public/js/filament', 'js/filament');
    echo "   ✓ JS copied to js/filament/\n";
} else {
    echo "   ⚠ No public/js/filament found\n";
}

echo "\n✅ Sync complete!\n";
echo "Assets are now in:\n";
echo "  - css/filament/\n";
echo "  - js/filament/\n\n";

/**
 * Recursively copy directory
 */
function rcopy($src, $dst) {
    if (!file_exists($dst)) {
        mkdir($dst, 0755, true);
    }
    
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                rcopy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

/**
 * Recursively remove directory
 */
function rrmdir($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $objects = scandir($dir);
    foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
            if (is_dir($dir . "/" . $object)) {
                rrmdir($dir . "/" . $object);
            } else {
                unlink($dir . "/" . $object);
            }
        }
    }
    rmdir($dir);
}
