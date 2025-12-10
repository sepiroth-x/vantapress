# 🔧 Quick Fix for Missing Assets on Server

If `/admin` loads but has no styling (white screen), run this command via SSH or create a PHP file to execute:

## Option 1: SSH Access

```bash
cd /path/to/vantapress
php artisan filament:assets
php artisan config:cache
```

## Option 2: No SSH (Create fix-assets.php in public/)

Create `public/fix-assets.php`:

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Publishing Filament assets...\n";
$kernel->call('filament:assets');
echo "Done! Delete this file now.\n";
```

Visit `https://yourdomain.com/fix-assets.php` in browser, then delete the file.

## What This Does

Publishes Filament CSS/JS to:
- `public/css/filament/filament/app.css`
- `public/css/filament/forms/forms.css`
- `public/js/filament/filament/app.js`
- `public/js/filament/notifications/notifications.js`

Plus your custom VantaPress assets at:
- `public/css/vantapress-admin.css`
- `public/images/vantapress-logo.svg`

## Prevention

The new deployment package (after this update) includes all assets pre-published. Just re-download and upload the latest ZIP.
