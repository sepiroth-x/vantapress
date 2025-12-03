# VantaPress Installation Guide (Without Vendor Directory)

If you downloaded VantaPress from GitHub and the `vendor/` directory is missing, follow these steps:

## Option A: Use SSH/Terminal Access (Recommended)

1. **Upload the ZIP to your server**
2. **Extract it**
3. **Navigate to the directory:**
   ```bash
   cd /path/to/vantapress
   ```
4. **Install Composer dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
5. **Visit the installer:**
   ```
   https://yourdomain.com/install.php
   ```

## Option B: No SSH? Download Complete Package

**For shared hosting without SSH/Composer access:**

1. Contact the developer for a "complete" release package that includes `vendor/`
2. Or use the deployment script locally to create a complete package
3. Download from: [Complete Package Link - Coming Soon]

## Option C: Manual Vendor Directory Setup

If you have access to a local machine with Composer:

1. **Download VantaPress source**
2. **Run locally:**
   ```bash
   composer install --no-dev
   ```
3. **Package everything including vendor/:**
   ```bash
   # On Windows (PowerShell):
   Compress-Archive -Path ./* -DestinationPath vantapress-complete.zip
   
   # On Linux/Mac:
   zip -r vantapress-complete.zip . -x "*.git*" "node_modules/*"
   ```
4. **Upload complete package to server**
5. **Extract and run install.php**

## Why is vendor/ not included?

The `vendor/` directory contains Composer dependencies (~100+ MB). It's excluded from Git repositories to:
- Keep repository size small
- Avoid version conflicts
- Follow PHP/Laravel best practices

However, for **shared hosting deployments**, we provide complete packages with `vendor/` included.

## Need Help?

Contact: chardy.tsadiq02@gmail.com or +63 915 0388 448
