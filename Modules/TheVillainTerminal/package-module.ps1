# Package TheVillainTerminal Module as .vpm file
# This script creates a clean .vpm package for distribution

$moduleName = "TheVillainTerminal"
$version = "1.0.0"
$outputDir = Join-Path $PSScriptRoot ".."
$outputFile = Join-Path $outputDir "$moduleName-v$version.vpm"

Write-Host "Packaging $moduleName v$version..." -ForegroundColor Cyan

# Remove old package if exists
if (Test-Path $outputFile) {
    Remove-Item $outputFile -Force
    Write-Host "Removed existing package" -ForegroundColor Yellow
}

# Create temporary zip first
$tempZip = Join-Path $outputDir "$moduleName-temp.zip"

# Compress the module directory
Compress-Archive -Path $PSScriptRoot\* -DestinationPath $tempZip -Force

# Rename to .vpm
Move-Item $tempZip $outputFile -Force

Write-Host "`nPackage created successfully!" -ForegroundColor Green
Write-Host "Location: $outputFile" -ForegroundColor White
Write-Host "Size: $([math]::Round((Get-Item $outputFile).Length / 1KB, 2)) KB" -ForegroundColor White

# Display package contents
Write-Host "`nPackage Contents:" -ForegroundColor Cyan
Add-Type -AssemblyName System.IO.Compression.FileSystem
$zip = [System.IO.Compression.ZipFile]::OpenRead($outputFile)
$zip.Entries | Select-Object -First 20 | ForEach-Object {
    Write-Host "  $_" -ForegroundColor Gray
}
$zip.Dispose()

Write-Host "`nReady to upload to VantaPress!" -ForegroundColor Green
