# 🚀 VPTelemetryServer - Quick Setup Guide

Step-by-step guide to setting up your private telemetry receiver server.

---

## 📋 Prerequisites

- **Separate VantaPress Installation** - Do NOT install on production user sites
- **Private Server** - Your developer/analytics server
- **HTTPS Domain** - Required for secure telemetry transmission
- **Database Access** - MySQL/PostgreSQL for telemetry data storage

---

## 🔧 Installation Steps

### Step 1: Prepare Your Server

1. **Install fresh VantaPress** on your private server (not on user sites)
2. **Complete installation** via `install.php`
3. **Access admin panel** and verify everything works

### Step 2: Enable VPTelemetryServer Module

The module is already included in VantaPress, just needs to be activated.

**Via Admin Panel (Recommended):**
1. Navigate to **Admin → Modules** in admin panel
2. Find **VPTelemetryServer**
3. Click **Enable**
4. **Migrations run automatically!** ✅

When you enable the module, VantaPress automatically:
- ✅ Runs all 4 required database migrations
- ✅ Creates `telemetry_installations` table
- ✅ Creates `telemetry_installation_modules` table
- ✅ Creates `telemetry_installation_themes` table
- ✅ Creates `telemetry_logs` table
- ✅ Registers migrations in database
- ✅ Loads the service provider

**Alternative: Via Web Interface (Shared Hosting)**
If you don't have terminal access:
1. Navigate to **Admin → System → Database Updates**
2. Click **Check for Migrations**
3. Click **Run Migrations** if any are pending
4. Then enable the module via Admin → Modules

**No manual migration commands needed!** The system handles everything automatically.

### Step 3: Configure Environment

Add to your `.env` file:

```env
# Telemetry Server Configuration
TELEMETRY_SERVER_ENABLED=true
APP_URL=https://your-telemetry-server.com

# Optional: Security settings
TELEMETRY_REQUIRE_HTTPS=true
TELEMETRY_RATE_LIMIT=10
TELEMETRY_LOGS_RETENTION_DAYS=90
TELEMETRY_INSTALLATIONS_RETENTION_DAYS=365
```

### Step 4: Verify API Endpoint

Test the health check endpoint:

```bash
curl https://dev5.thevillainousacademy.it.nf/api/v1/telemetry/health
```

Expected response:
```json
{
  "status": "ok",
  "service": "VantaPress Telemetry Server",
  "version": "1.0.0"
}
```

### Step 5: Access Dashboard

1. Log in to admin panel: `https://dev5.thevillainousacademy.it.nf/admin`
2. Navigate to **Analytics → Telemetry Dashboard**
3. You should see:
   - Stats overview widgets
   - Empty charts (will populate when data arrives)
   - "About Telemetry Data" section

### Step 6: Configure Client Installations

**IMPORTANT:** The default telemetry endpoint is already configured to point to your server!

**For new installations:** 
- VPTelemetry module will automatically send data to `https://dev5.thevillainousacademy.it.nf/api/v1/telemetry/collect`
- No configuration needed!

**For existing installations (if any):**

On **client installations** (sender side), update their `.env`:

```env
# Point telemetry data to your server
TELEMETRY_ENABLED=true
TELEMETRY_API_ENDPOINT=https://dev5.thevillainousacademy.it.nf/api/v1/telemetry/collect
```

Then clear config cache:
```bash
php artisan config:clear
```

---

## 🧪 Testing

### Test from Development Installation

On a **test VantaPress installation** (sender), trigger a test ping:

1. Go to **System → Telemetry** in admin
2. Click **"Send Test Ping"** button
3. Should see success notification

### Verify on Receiver Server

1. Check **Telemetry Dashboard** - should show:
   - Total Installations: 1
   - Active Installations: 1
   - New This Week: 1
   - Pings Today: 1

2. Go to **Analytics → Installations**
3. Should see your test installation listed

---

## 📊 Dashboard Features

### Stats Overview (Header Widgets)

- **Total Installations** - All-time count
- **Active Installations** - Pinged within last 7 days (with trend)
- **New This Week** - Fresh installations count
- **Pings Today** - Telemetry events received today

### Charts

1. **Most Used Modules** - Bar chart of module popularity
2. **Most Used Themes** - Doughnut chart of theme usage
3. **PHP Version Distribution** - Pie chart of PHP versions
4. **New Installations Timeline** - Line chart showing growth (last 30 days)

### Installations Resource

- **List View** - Filterable table of all installations
  - Filters: Version, PHP version, Active/Inactive status
  - Search: By domain
  - Sort: By last ping, install date, etc.

- **Detail View** - Individual installation details
  - Installation overview (ID, domain, IP)
  - System information (version, PHP, OS)
  - Enabled modules (badge list)
  - Enabled themes (badge list)
  - Timeline (install date, last ping, last update)
  - Recent logs (last 10 events with payloads)

---

## 🔒 Security Checklist

✅ **HTTPS Only** - Ensure your server uses HTTPS  
✅ **Private Server** - Never install on user sites  
✅ **Rate Limiting** - Enabled by default (60 req/min)  
✅ **Database Security** - Use strong credentials  
✅ **Firewall** - Restrict admin panel access if needed  
✅ **Regular Backups** - Backup telemetry database  

---

## 🐛 Troubleshooting

### Issue: "Connection Failed" when testing from sender

**Cause:** API endpoint unreachable or misconfigured

**Solution:**
1. Verify `TELEMETRY_API_ENDPOINT` in sender `.env`
2. Test manually:
   ```bash
   curl -X POST https://your-server.com/api/v1/telemetry/collect \
     -H "Content-Type: application/json" \
     -d '{"installation_id":"test-123","event_type":"test","domain":"test.local"}'
   ```
3. Check server firewall rules
4. Ensure HTTPS certificate is valid

### Issue: Dashboard shows no data

**Cause:** No telemetry received yet

**Solution:**
1. Send test ping from sender installation
2. Check receiver logs: `storage/logs/laravel.log`
3. Look for `[TelemetryServer] Telemetry collected` messages
4. Check database: `SELECT * FROM telemetry_installations;`

### Issue: "Rate limit exceeded" error

**Cause:** Too many requests from same installation

**Solution:**
1. This is normal protection (max 10 req/hour per installation)
2. Wait 1 hour for rate limit to reset
3. Or adjust in `.env`: `TELEMETRY_RATE_LIMIT=20`

### Issue: Migrations fail

**Cause:** Conflicting table names

**Solution:**
1. Check if tables already exist: `SHOW TABLES LIKE 'telemetry_%';`
2. Drop existing tables if safe:
   ```sql
   DROP TABLE IF EXISTS telemetry_logs;
   DROP TABLE IF EXISTS telemetry_installation_themes;
   DROP TABLE IF EXISTS telemetry_installation_modules;
   DROP TABLE IF EXISTS telemetry_installations;
   ```
3. Re-run migrations: `php artisan migrate`

---

## 📈 Best Practices

1. **Regular Monitoring** - Check dashboard weekly
2. **Data Retention** - Clean old logs per configured retention policy
3. **Privacy Respect** - Never request personal data from users
4. **Transparency** - Keep users informed about data collection
5. **Security Updates** - Keep receiver server updated

---

## 🔧 Advanced Configuration

### Custom Rate Limits

Edit `Modules/VPTelemetryServer/config/telemetry-server.php`:

```php
'rate_limit' => [
    'max_per_hour' => 20, // Increase from default 10
],
```

### Data Retention

```php
'retention' => [
    'logs_days' => 30, // Keep logs for 30 days (default 90)
    'installations_days' => 180, // Keep inactive for 180 days (default 365)
],
```

### IP Whitelisting

```php
'security' => [
    'allowed_ips' => '203.0.113.1,203.0.113.2', // Comma-separated
],
```

### Threshold Adjustments

```php
'dashboard' => [
    'active_threshold_days' => 14, // Consider active if pinged within 14 days (default 7)
    'inactive_threshold_days' => 60, // Inactive after 60 days (default 30)
],
```

---

## � Technical Notes

### Automatic Migration System

VPTelemetryServer uses VantaPress's built-in automatic migration system:

**How It Works:**
1. When you enable the module via Admin Panel, `ModuleLoader::activateModule()` is called
2. This triggers `runModuleMigrations()` automatically
3. The system scans `Modules/VPTelemetryServer/database/migrations/`
4. Checks which migrations haven't been executed (via `migrations` table)
5. Runs pending migrations in order
6. Records each migration in the `migrations` table

**Migrations Included:**
- `2025_12_10_000001_create_telemetry_installations_table.php`
- `2025_12_10_000002_create_telemetry_installation_modules_table.php`
- `2025_12_10_000003_create_telemetry_installation_themes_table.php`
- `2025_12_10_000004_create_telemetry_logs_table.php`

**Database Tables Created:**
- `telemetry_installations` - Main installation records
- `telemetry_installation_modules` - Module usage tracking
- `telemetry_installation_themes` - Theme usage tracking
- `telemetry_logs` - Incoming telemetry event logs

**Safety Features:**
- ✅ Idempotent - Safe to enable/disable multiple times
- ✅ Skip executed - Won't re-run migrations that already executed
- ✅ Error recovery - Continues with other migrations if one fails
- ✅ Logging - Full execution log in Laravel logs

**Troubleshooting:**
If migrations don't run automatically:
1. Check logs: `storage/logs/laravel.log`
2. Look for: `[VPTelemetryServer]` entries
3. Manually trigger via: **Admin → System → Database Updates → Run Migrations**
4. Verify tables exist: Check database for `telemetry_*` tables

---

## �📞 Support

**Issues?** Check `TELEMETRY.md` for comprehensive documentation.

**Questions?** Contact: support@vantapress.com

---

**Remember:** VPTelemetryServer should ONLY be installed on your private developer/analytics server, NOT on production user sites!
