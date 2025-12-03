# VantaPress - Create Release Package with Vendor Directory
# This creates a complete deployment-ready package

$version = "v1.0.0"
$packageName = "vantapress-$version-complete"
$outputDir = ".\release"
$zipFile = "$outputDir\$packageName.zip"

Write-Host "Creating VantaPress release package..." -ForegroundColor Cyan
Write-Host "Version: $version" -ForegroundColor Yellow

# Create output directory
if (!(Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir | Out-Null
}

# Remove old package if exists
if (Test-Path $zipFile) {
    Remove-Item $zipFile
    Write-Host "Removed old package" -ForegroundColor Yellow
}

# Install/update composer dependencies
Write-Host "`nInstalling Composer dependencies..." -ForegroundColor Cyan
if (Test-Path ".\vendor") {
    Write-Host "Vendor directory exists, updating..." -ForegroundColor Yellow
    composer update --no-dev --optimize-autoloader
} else {
    Write-Host "Installing fresh dependencies..." -ForegroundColor Yellow
    composer install --no-dev --optimize-autoloader
}

# Create temporary directory for packaging
$tempDir = ".\temp-package"
if (Test-Path $tempDir) {
    Remove-Item -Recurse -Force $tempDir
}
New-Item -ItemType Directory -Path $tempDir | Out-Null

Write-Host "`nCopying files to package..." -ForegroundColor Cyan

# Define what to include
$includeItems = @(
    "app",
    "bootstrap",
    "build-tools",
    "config",
    "css",
    "database",
    "docs",
    "images",
    "js",
    "Modules",
    "public",
    "resources",
    "routes",
    "scripts",
    "storage",
    "themes",
    "vendor",
    "vantapress",
    ".env.example",
    ".htaccess",
    ".gitignore",
    "artisan",
    "composer.json",
    "composer.lock",
    "index.php",
    "install.php",
    "LICENSE",
    "package.json",
    "postcss.config.js",
    "README.md",
    "RELEASE_NOTES_v1.0.0.md",
    "tailwind.config.js",
    "vite.config.js"
)

foreach ($item in $includeItems) {
    if (Test-Path $item) {
        Write-Host "  + $item" -ForegroundColor Green
        Copy-Item -Path $item -Destination $tempDir -Recurse -Force
    } else {
        Write-Host "  - $item (not found, skipping)" -ForegroundColor DarkGray
    }
}

# Create empty .env from .env.example
Copy-Item "$tempDir\.env.example" "$tempDir\.env"
Write-Host "  + .env (created from .env.example)" -ForegroundColor Green

# Ensure storage directories exist with proper structure
$storageDirs = @(
    "framework\cache\data",
    "framework\sessions",
    "framework\views",
    "logs",
    "app\public"
)

foreach ($dir in $storageDirs) {
    $fullPath = Join-Path $tempDir "storage\$dir"
    if (!(Test-Path $fullPath)) {
        New-Item -ItemType Directory -Path $fullPath -Force | Out-Null
        Write-Host "  + storage/$dir (created)" -ForegroundColor Green
    }
}

# Create .gitkeep files in storage directories
Get-ChildItem -Path "$tempDir\storage" -Directory -Recurse | ForEach-Object {
    $gitkeep = Join-Path $_.FullName ".gitkeep"
    if (!(Test-Path $gitkeep)) {
        New-Item -ItemType File -Path $gitkeep -Force | Out-Null
    }
}

# Clean bootstrap/cache
if (Test-Path "$tempDir\bootstrap\cache") {
    Get-ChildItem "$tempDir\bootstrap\cache" -Exclude ".gitignore" | Remove-Item -Force
    Write-Host "  + bootstrap/cache (cleaned)" -ForegroundColor Green
}

Write-Host "`nCreating ZIP archive..." -ForegroundColor Cyan
Compress-Archive -Path "$tempDir\*" -DestinationPath $zipFile -CompressionLevel Optimal

# Cleanup
Remove-Item -Recurse -Force $tempDir

# Get file size
$fileSize = [math]::Round((Get-Item $zipFile).Length / 1MB, 2)

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "SUCCESS!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "Package: $packageName.zip" -ForegroundColor Yellow
Write-Host "Location: $zipFile" -ForegroundColor Yellow
Write-Host "Size: $fileSize MB" -ForegroundColor Yellow
Write-Host "`nThis package includes:" -ForegroundColor Cyan
Write-Host "  - All source code" -ForegroundColor White
Write-Host "  - Vendor directory (Composer dependencies)" -ForegroundColor White
Write-Host "  - Documentation" -ForegroundColor White
Write-Host "  - Installation wizard (install.php)" -ForegroundColor White
Write-Host "`nReady for deployment to any web server!" -ForegroundColor Green
Write-Host "No Composer or SSH needed on target server." -ForegroundColor Green
Write-Host "`nUpload, extract, visit install.php - Done! 🚀" -ForegroundColor Cyan
