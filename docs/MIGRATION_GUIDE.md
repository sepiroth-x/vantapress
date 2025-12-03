# Database Migration Instructions

## ⚠️ Important: Database Setup Required

The VantaPress admin panel requires database tables to be created before it can function. You'll need to run migrations on your server.

## Migration Files

Two new migrations have been created:
- `2025_01_14_000001_create_pages_table.php` - Creates the pages table
- `2025_01_14_000002_create_media_table.php` - Creates the media table

## Running Migrations

### Option 1: Via SSH (Recommended)
If you have SSH access to your server:

```bash
cd /path/to/vantapress
php artisan migrate
```

### Option 2: Via Web Interface
Use the `install.php` wizard:

1. Navigate to `yourdomain.com/install.php`
2. Complete Steps 1-3 (Environment, Database, Admin)
3. Step 4 will automatically run migrations

### Option 3: Via post-deploy.php
If you have SSH access:

```bash
php post-deploy.php
```

This script automatically runs migrations as part of the deployment process.

### Option 4: Manual SQL Execution
If you only have cPanel/PHPMyAdmin access, you can manually create the tables.

#### Pages Table SQL:
```sql
CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext,
  `excerpt` text,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `template` varchar(255) NOT NULL DEFAULT 'default',
  `featured_image_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) NULL DEFAULT NULL,
  `meta_description` text NULL DEFAULT NULL,
  `meta_keywords` text NULL DEFAULT NULL,
  `og_image` varchar(255) NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_author_id_foreign` (`author_id`),
  KEY `pages_parent_id_foreign` (`parent_id`),
  KEY `pages_featured_image_id_foreign` (`featured_image_id`),
  KEY `pages_status_published_at_index` (`status`, `published_at`),
  KEY `pages_parent_id_index` (`parent_id`),
  CONSTRAINT `pages_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pages_featured_image_id_foreign` FOREIGN KEY (`featured_image_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Media Table SQL:
```sql
CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `width` int(11) NULL DEFAULT NULL,
  `height` int(11) NULL DEFAULT NULL,
  `alt_text` varchar(255) NULL DEFAULT NULL,
  `description` text NULL DEFAULT NULL,
  `caption` varchar(255) NULL DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_mime_type_index` (`mime_type`),
  KEY `media_uploaded_by_index` (`uploaded_by`),
  CONSTRAINT `media_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Verifying Migrations

After running migrations, verify the tables exist:

```bash
php artisan migrate:status
```

Or check in PHPMyAdmin/cPanel for:
- ✅ `pages` table
- ✅ `media` table

## Existing Tables

VantaPress expects these tables to already exist:
- `users` - For user authentication
- `menus` - For navigation menus
- `menu_items` - For menu entries
- `modules` - For plugin management
- `themes` - For theme management
- `settings` - For system configuration
- `roles` - For permissions (Spatie)
- `permissions` - For permissions (Spatie)

## Troubleshooting

### "Access denied" Error
This error means:
- Database credentials in `.env` are incorrect
- Database user doesn't have permission to create tables
- You're testing locally with production credentials

**Solution**: Update your `.env` file with correct database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### "Table already exists" Error
If you see this error, the table was already created. You can:
- Skip the migration: `php artisan migrate --skip-existing`
- Or rollback and retry: `php artisan migrate:rollback` then `php artisan migrate`

### Foreign Key Constraint Errors
If you get foreign key errors, ensure these tables exist first:
- `users` (required by pages, media)
- `media` (required by pages for featured_image_id)

Create them in this order:
1. users
2. media
3. pages

## Development vs Production

### Development (Local)
- Use local database credentials
- Run migrations with: `php artisan migrate`

### Production (Deployed)
- Use production database credentials from hosting provider
- Run migrations via SSH or install.php wizard
- Ensure database user has CREATE, ALTER, DROP privileges

## Next Steps

After migrations are complete:
1. Access admin panel at `/admin`
2. Create your first page
3. Upload media files
4. Install themes and modules
5. Configure site settings

---

**Note**: The admin panel will show database errors until migrations are run successfully.
