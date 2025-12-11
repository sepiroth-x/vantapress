# VantaPress Production Deployment Package Creator
# Run this script to create a clean deployment zip file

Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "  VantaPress Deployment Packager" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host ""

$sourceDir = $PSScriptRoot
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$zipFileName = "vantapress_v1.0.0_$timestamp.zip"
$zipPath = Join-Path $sourceDir $zipFileName

Write-Host "Source Directory: $sourceDir" -ForegroundColor Yellow
Write-Host "Output File: $zipFileName" -ForegroundColor Yellow
Write-Host ""

# Files and folders to EXCLUDE
$excludeItems = @(
    'node_modules',
    '.git',
    '.github',
    'tests',
    '.vscode',
    '.idea',
    'REFERENCES',
    'debug-scripts',
    'package.json',
    'package-lock.json',
    'vite.config.js',
    'tailwind.config.js',
    'postcss.config.js',
    'phpunit.xml',
    '.phpunit.result.cache',
    '.DS_Store',
    'Thumbs.db',
    'NO_NODEJS_REQUIRED.md',
    '*.zip'
)

Write-Host "Creating temporary staging directory..." -ForegroundColor Green

$tempDir = Join-Path $env:TEMP "vantapress_staging_$timestamp"
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

Write-Host "Copying files (excluding development files)..." -ForegroundColor Green

# Copy all files except excluded ones
Get-ChildItem -Path $sourceDir -Recurse | ForEach-Object {
    $relativePath = $_.FullName.Substring($sourceDir.Length + 1)
    $shouldExclude = $false
    
    foreach ($exclude in $excludeItems) {
        if ($relativePath -like "*$exclude*") {
            $shouldExclude = $true
            break
        }
    }
    
    if (-not $shouldExclude) {
        $targetPath = Join-Path $tempDir $relativePath
        
        if ($_.PSIsContainer) {
            New-Item -ItemType Directory -Path $targetPath -Force | Out-Null
        } else {
            $targetDir = Split-Path $targetPath -Parent
            if (-not (Test-Path $targetDir)) {
                New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
            }
            Copy-Item $_.FullName -Destination $targetPath -Force
        }
    }
}

Write-Host "Publishing Filament assets before packaging..." -ForegroundColor Green
php artisan filament:assets 2>&1 | Out-Null

Write-Host "Verifying critical assets exist..." -ForegroundColor Green
$criticalAssets = @(
    "public/css/vantapress-admin.css",
    "public/css/filament/filament/app.css",
    "public/css/filament/forms/forms.css",
    "public/js/filament/filament/app.js",
    "public/js/filament/notifications/notifications.js",
    "public/images/vantapress-logo.svg",
    "public/images/vantapress-icon.svg",
    "public/images/favicon.svg",
    "public/favicon.ico"
)

$missingAssets = @()
foreach ($asset in $criticalAssets) {
    if (-not (Test-Path (Join-Path $sourceDir $asset))) {
        $missingAssets += $asset
        Write-Host "  ⚠ Missing: $asset" -ForegroundColor Yellow
    } else {
        Write-Host "  ✓ Found: $asset" -ForegroundColor Green
    }
}

if ($missingAssets.Count -gt 0) {
    Write-Host ""
    Write-Host "ERROR: Missing critical assets! Run 'php artisan filament:assets' first." -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Creating production README..." -ForegroundColor Green

# Create production README
$productionReadme = @'
# VantaPress v1.0.0 - Production Release

## Quick Start (WordPress-Style Deployment)

1. **Upload Files**
   - Extract this ZIP to your web hosting document root
   - All files should be in: public_html/ or htdocs/ or www/

2. **Create Database**
   - Use cPanel or hosting control panel
   - Create new MySQL database
   - Note: database name, username, password

3. **Run Installer**
   - Visit: https://yourdomain.com/install.php
   - Follow 6-step wizard:
     ✓ Step 1: Requirements check
     ✓ Step 2: Database configuration
     ✓ Step 3: Run migrations
     ✓ Step 4: Publish assets
     ✓ Step 5: Create admin user
     ✓ Step 6: Complete!

4. **Login**
   - Admin panel: https://yourdomain.com/admin
   - Use credentials from Step 5

5. **Security**
   - Delete install.php after installation
   - Delete create-admin.php after creating admin
   - Set storage/ permissions to 775

## No Build Tools Required!

✓ No Node.js needed
✓ No npm/yarn needed
✓ No Vite/Webpack needed
✓ Just upload and install!

## System Requirements

- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite
- 128MB PHP memory (256MB recommended)

## Support

- Email: chardy.tsadiq02@gmail.com
- Phone: +63 915 0388 448
- License: MIT (Open Source)

## What's Included

- Laravel 11.47 Framework
- FilamentPHP 3.3 Admin Panel
- 21-table database schema
- 7 VantaPress modules (Pages, Menus, Users, Themes, Media, Settings, SEO)
- Complete documentation

---

**VantaPress** - WordPress Philosophy, Laravel Power
Created by Sepirothx (Richard Cebel Cupal, LPT)
Copyright © 2025 - MIT Licensed
'@

Set-Content -Path (Join-Path $tempDir "README_DEPLOYMENT.txt") -Value $productionReadme

Write-Host "Creating ZIP archive..." -ForegroundColor Green

# Create ZIP file
Compress-Archive -Path "$tempDir\*" -DestinationPath $zipPath -Force

# Cleanup temp directory
Remove-Item -Path $tempDir -Recurse -Force

$zipSize = (Get-Item $zipPath).Length / 1MB
Write-Host ""
Write-Host "=======================================" -ForegroundColor Green
Write-Host "  SUCCESS!" -ForegroundColor Green
Write-Host "=======================================" -ForegroundColor Green
Write-Host ""
Write-Host "Package created: $zipFileName" -ForegroundColor Yellow
Write-Host "Size: $([math]::Round($zipSize, 2)) MB" -ForegroundColor Yellow
Write-Host "Location: $zipPath" -ForegroundColor Yellow
Write-Host ""
Write-Host "Ready to upload to your server!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Upload ZIP to your hosting" -ForegroundColor White
Write-Host "2. Extract in document root (public_html/)" -ForegroundColor White
Write-Host "3. Visit /install.php in browser" -ForegroundColor White
Write-Host "4. Follow the 6-step installation wizard" -ForegroundColor White
Write-Host ""
