# 📊 VantaPress Telemetry System

Complete guide to VantaPress anonymous usage telemetry system.

---

## 📖 Table of Contents

- [Overview](#overview)
- [For End Users](#for-end-users)
- [For Developers](#for-developers)
- [Privacy & Security](#privacy--security)
- [Technical Architecture](#technical-architecture)
- [API Documentation](#api-documentation)

---

## 🌟 Overview

VantaPress includes an **opt-out anonymous telemetry system** that helps developers understand how VantaPress is used in the real world. This data guides feature development, bug fixes, and compatibility improvements.

### Key Principles

✅ **Anonymous** - No personal data collected  
✅ **Transparent** - Full disclosure of what's collected  
✅ **Opt-Out** - Users can disable anytime  
✅ **Secure** - HTTPS-only transmission  
✅ **Minimal** - Only essential usage statistics  

---

## 👥 For End Users

### What Data is Collected?

The following **anonymous system information** is collected:

| Data Point | Example | Purpose |
|------------|---------|---------|
| Installation ID | `550e8400-e29b-41d4-a716-446655440000` | Anonymous unique identifier |
| Domain | `example.com` | Understand hosting patterns |
| Server IP | `203.0.113.1` | Geographic distribution |
| VantaPress Version | `1.0.7` | Track version adoption |
| PHP Version | `8.2.5` | Ensure compatibility |
| Server OS | `Linux 5.15` | Platform support |
| Enabled Modules | `["VPEssential1", "TheVillainTerminal"]` | Module popularity |
| Enabled Themes | `["DefaultTheme"]` | Theme usage |
| Timestamps | `2025-12-10T10:30:00Z` | Usage patterns |

### What is NOT Collected?

❌ **Email addresses**  
❌ **Usernames**  
❌ **Passwords**  
❌ **Page content or posts**  
❌ **Media files**  
❌ **User data of any kind**  
❌ **Database contents**  
❌ **Custom configurations**  

### How to Disable Telemetry

#### Method 1: Admin Panel (Recommended)

1. Log in to your VantaPress admin panel
2. Navigate to **System → Telemetry**
3. Toggle **"Enable Telemetry"** to OFF
4. Changes take effect immediately

#### Method 2: Environment Variable

Add to your `.env` file:

```env
TELEMETRY_ENABLED=false
```

Then clear cache:

```bash
php artisan config:clear
```

### When is Data Sent?

Telemetry data is sent at these events:

- **Installation Complete** - Once when VantaPress is first installed
- **Version Update** - When you update to a new version
- **Module Change** - When modules are enabled/disabled
- **Daily Heartbeat** - Once per day to show active installations

**Rate Limited:** Maximum 10 requests per hour per installation to prevent abuse.

---

## 💻 For Developers

### Architecture Overview

The telemetry system consists of **two separate modules**:

1. **VPTelemetry** (Sender) - Installed on user sites
2. **VPTelemetryServer** (Receiver) - Installed on your private analytics server

### Module 1: VPTelemetry (Sender)

**Purpose:** Collects and sends anonymous usage data from VantaPress installations.

**Key Components:**

- `TelemetryService` - Collects system data
- `TelemetryLog` Model - Logs sent data locally
- Settings Page - User control panel
- Event Listeners - Triggers for data collection
- Scheduler - Daily heartbeat task

**Installation:**

Already included in VantaPress core. No action needed.

**Configuration:**

Create `.env` variables:

```env
TELEMETRY_ENABLED=true
TELEMETRY_API_ENDPOINT=https://your-server.com/api/v1/telemetry/collect
```

### Module 2: VPTelemetryServer (Receiver)

**Purpose:** Receives, validates, and stores telemetry data from installations.

**Key Components:**

- `TelemetryApiController` - API endpoint
- Database Models - Installation, Module, Theme, Log
- Filament Dashboard - Data visualization
- Widgets - Stats, charts, trends

**Installation:**

⚠️ **Install ONLY on your private developer server, NOT on production user sites.**

1. Module already exists in `Modules/VPTelemetryServer/`
2. Run migrations:

```bash
php artisan migrate
```

3. Access dashboard: `/admin/telemetry-dashboard`

**API Endpoint:**

```
POST https://your-server.com/api/v1/telemetry/collect
```

**Database Tables:**

- `telemetry_installations` - Installation records
- `telemetry_installation_modules` - Module usage
- `telemetry_installation_themes` - Theme usage
- `telemetry_logs` - Event logs

### Dashboard Features

Access the telemetry dashboard at `/admin/telemetry-dashboard` to view:

📊 **Stats Overview**
- Total installations
- Active installations (last 7 days)
- New installations this week
- Pings received today

📈 **Charts**
- Most used modules (bar chart)
- Most used themes (doughnut chart)
- PHP version distribution (pie chart)
- Installation timeline (line chart)

🔍 **Installation Details**
- Filter by version, PHP version, status
- View individual installation details
- See modules, themes, and logs
- Track installation history

---

## 🔒 Privacy & Security

### Data Protection

1. **No Personal Data** - System information only
2. **HTTPS Only** - Encrypted transmission
3. **Rate Limited** - Max 10 requests/hour per installation
4. **Local Logging** - Users can audit sent data
5. **User Control** - Easy opt-out mechanism

### Compliance

✅ **GDPR Compliant** - No personal data processing  
✅ **Transparent** - Full disclosure of collection  
✅ **User Rights** - Easy opt-out mechanism  
✅ **Data Minimization** - Only essential metrics  

### Data Retention

- **Telemetry Logs:** 90 days (configurable)
- **Installation Records:** 365 days for inactive installations
- **Active Installations:** Kept as long as they ping

Configure in `Modules/VPTelemetryServer/config/telemetry-server.php`:

```php
'retention' => [
    'logs_days' => 90,
    'installations_days' => 365,
],
```

---

## 🛠️ Technical Architecture

### Data Flow

```
┌─────────────────────┐
│  User Installation  │
│   (VPTelemetry)     │
└──────────┬──────────┘
           │
           │ HTTP POST (HTTPS)
           │
           ▼
┌─────────────────────┐
│  Your Server        │
│ (VPTelemetryServer) │
│                     │
│  ┌──────────────┐  │
│  │ API Endpoint │  │
│  └──────┬───────┘  │
│         │          │
│         ▼          │
│  ┌──────────────┐  │
│  │  Validation  │  │
│  └──────┬───────┘  │
│         │          │
│         ▼          │
│  ┌──────────────┐  │
│  │   Database   │  │
│  └──────┬───────┘  │
│         │          │
│         ▼          │
│  ┌──────────────┐  │
│  │  Dashboard   │  │
│  └──────────────┘  │
└─────────────────────┘
```

### Event Triggers

**1. Installation Complete**

Triggered when install.php finishes successfully.

```php
Event::dispatch('vantapress.installed');
```

**2. Version Update**

Triggered after successful version update.

```php
Event::dispatch('vantapress.updated');
```

**3. Module Toggle**

Triggered when modules are enabled/disabled.

```php
Event::dispatch('vantapress.module.toggled');
```

**4. Daily Heartbeat**

Scheduled task runs daily via Laravel Scheduler.

```php
$schedule->call(function () {
    app(TelemetryService::class)->sendDailyHeartbeat();
})->daily();
```

---

## 📡 API Documentation

### Endpoint: Collect Telemetry

**URL:** `POST /api/v1/telemetry/collect`

**Authentication:** None (public endpoint, rate-limited)

**Rate Limit:** 60 requests per minute (throttle:60,1)

**Request Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Request Body:**

```json
{
  "installation_id": "550e8400-e29b-41d4-a716-446655440000",
  "event_type": "heartbeat",
  "domain": "example.com",
  "ip": "203.0.113.1",
  "version": "1.0.7",
  "php_version": "8.2.5",
  "server_os": "Linux 5.15.0",
  "modules": ["VPEssential1", "TheVillainTerminal", "VPTelemetry"],
  "themes": ["DefaultTheme"],
  "installed_at": "2025-12-01T10:00:00Z",
  "timestamp": "2025-12-10T10:30:00Z"
}
```

**Validation Rules:**

| Field | Type | Required | Rules |
|-------|------|----------|-------|
| installation_id | string | Yes | UUID (36 chars) |
| event_type | string | Yes | `install`, `update`, `module_change`, `heartbeat`, `test` |
| domain | string | No | Max 255 chars |
| ip | string | No | Valid IP address |
| version | string | No | Max 50 chars |
| php_version | string | No | Max 50 chars |
| server_os | string | No | Max 100 chars |
| modules | array | No | Array of strings |
| themes | array | No | Array of strings |
| installed_at | datetime | No | ISO 8601 format |
| timestamp | datetime | No | ISO 8601 format |

**Success Response:**

```json
{
  "success": true,
  "message": "Telemetry data received"
}
```

**Error Response (Validation Failed):**

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "installation_id": ["The installation id field is required."]
  }
}
```

**Error Response (Rate Limited):**

```json
{
  "success": false,
  "message": "Rate limit exceeded"
}
```

### Endpoint: Health Check

**URL:** `GET /api/v1/telemetry/health`

**Response:**

```json
{
  "status": "ok",
  "service": "VantaPress Telemetry Server",
  "version": "1.0.0"
}
```

---

## 🔧 Configuration

### Sender Configuration

File: `Modules/VPTelemetry/config/telemetry.php`

```php
return [
    'enabled' => env('TELEMETRY_ENABLED', true),
    'api_endpoint' => env('TELEMETRY_API_ENDPOINT', 'https://telemetry.vantapress.com/api/v1/telemetry/collect'),
    'heartbeat_interval' => 24, // hours
    'retry_attempts' => 3,
    'retry_delay' => 100, // ms
    'timeout' => 10, // seconds
    'privacy_url' => 'https://vantapress.com/telemetry',
];
```

### Receiver Configuration

File: `Modules/VPTelemetryServer/config/telemetry-server.php`

```php
return [
    'enabled' => env('TELEMETRY_SERVER_ENABLED', true),
    'rate_limit' => [
        'max_per_hour' => 10,
    ],
    'retention' => [
        'logs_days' => 90,
        'installations_days' => 365,
    ],
    'dashboard' => [
        'active_threshold_days' => 7,
        'inactive_threshold_days' => 30,
    ],
    'security' => [
        'require_https' => true,
        'allowed_ips' => null,
    ],
];
```

---

## 🚀 Deployment

### For End Users (Sender)

Already included in VantaPress. Enabled by default.

### For Developers (Receiver)

1. **Clone VantaPress to your private server**
2. **Run migrations:**
   ```bash
   php artisan migrate
   ```
3. **Set environment variables:**
   ```env
   TELEMETRY_SERVER_ENABLED=true
   APP_URL=https://your-telemetry-server.com
   ```
4. **Access dashboard:** `https://your-telemetry-server.com/admin/telemetry-dashboard`

5. **Update sender installations:**
   Update `.env` on sender installations to point to your server:
   ```env
   TELEMETRY_API_ENDPOINT=https://your-telemetry-server.com/api/v1/telemetry/collect
   ```

---

## 📊 Analytics Best Practices

1. **Review Dashboard Weekly** - Track adoption trends
2. **Monitor PHP Versions** - Plan compatibility updates
3. **Identify Popular Modules** - Prioritize development
4. **Track Active Installations** - Measure user retention
5. **Respect User Privacy** - Never request personal data

---

## ❓ FAQ

**Q: Can users opt-out?**  
A: Yes, easily via Admin Panel → System → Telemetry or `.env` variable.

**Q: Is data encrypted?**  
A: Yes, HTTPS-only transmission required.

**Q: How often is data sent?**  
A: Maximum once per day (heartbeat), plus event-triggered pings (install, update, module change).

**Q: Can I see what data was sent?**  
A: Yes, view recent logs in Admin Panel → Telemetry → Recent Logs.

**Q: Where is data stored?**  
A: On your private telemetry server (VPTelemetryServer module).

**Q: Does it slow down my site?**  
A: No, telemetry runs asynchronously and is rate-limited.

---

## 📝 License

VantaPress Telemetry System is part of VantaPress and follows the same license.

---

**Questions or concerns?** Contact: support@vantapress.com
