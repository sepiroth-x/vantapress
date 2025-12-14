# VantaPress Telemetry Server API Documentation

## Overview

The VantaPress Telemetry Server receives anonymous usage data from VantaPress installations to help improve the platform.

**Base URL:** `https://vantapress.com/api/v1/telemetry`

**Authentication:** None required (public endpoint with rate limiting)

## Endpoints

### 1. Collect Telemetry Data

Receives telemetry data from VantaPress installations.

**Endpoint:** `POST /api/v1/telemetry/collect`

**Rate Limit:** 10 requests per hour per installation_id

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**

```json
{
  "installation_id": "550e8400-e29b-41d4-a716-446655440000",
  "event_type": "heartbeat",
  "domain": "example.com",
  "ip": "192.168.1.1",
  "version": "1.2.1",
  "php_version": "8.2.29",
  "php_major_minor": "8.2",
  "server_os": "Linux",
  "server_software": "Apache/2.4.41",
  "laravel_version": "11.47.0",
  "modules": ["VPEssential1", "VPTelemetry", "HelloWorld"],
  "themes": ["BasicTheme", "TheVillainArise"],
  "installed_at": "2024-12-01T10:00:00Z",
  "timestamp": "2025-12-14T08:30:00Z"
}
```

**Field Descriptions:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `installation_id` | string (UUID) | Yes | Unique identifier for the installation (36 chars) |
| `event_type` | string | Yes | One of: `install`, `update`, `module_change`, `heartbeat`, `test` |
| `domain` | string | No | Domain name of the installation (max 255 chars) |
| `ip` | string | No | Server's public IP address |
| `version` | string | No | VantaPress version (max 50 chars) |
| `php_version` | string | No | Full PHP version (max 50 chars) |
| `php_major_minor` | string | No | PHP major.minor version (e.g., "8.2") |
| `server_os` | string | No | Server operating system (max 100 chars) |
| `server_software` | string | No | Web server software |
| `laravel_version` | string | No | Laravel framework version |
| `modules` | array | No | List of enabled module names |
| `themes` | array | No | List of installed theme names |
| `installed_at` | string (ISO 8601) | No | Installation date/time |
| `timestamp` | string (ISO 8601) | No | Current request timestamp |

**Event Types:**

- `install` - First installation of VantaPress
- `update` - Version update detected
- `module_change` - Module enabled/disabled
- `heartbeat` - Daily ping to confirm installation is active
- `test` - Test connection from Telemetry Settings page

**Success Response (200 OK):**

```json
{
  "success": true,
  "message": "Telemetry data received"
}
```

**Error Responses:**

**422 Unprocessable Entity** (Validation failed):
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "installation_id": ["The installation id field is required."],
    "event_type": ["The selected event type is invalid."]
  }
}
```

**429 Too Many Requests** (Rate limit exceeded):
```json
{
  "success": false,
  "message": "Rate limit exceeded"
}
```

**500 Internal Server Error** (Server error):
```json
{
  "success": false,
  "message": "Error processing telemetry data"
}
```

---

### 2. Health Check

Check if the telemetry service is operational.

**Endpoint:** `GET /api/v1/telemetry/health`

**Success Response (200 OK):**

```json
{
  "status": "ok",
  "service": "VantaPress Telemetry Server",
  "version": "1.0.0"
}
```

---

## Usage Example (PHP/Laravel)

```php
use Illuminate\Support\Facades\Http;

$response = Http::timeout(10)
    ->retry(3, 100)
    ->post('https://vantapress.com/api/v1/telemetry/collect', [
        'installation_id' => '550e8400-e29b-41d4-a716-446655440000',
        'event_type' => 'heartbeat',
        'domain' => 'example.com',
        'ip' => '192.168.1.1',
        'version' => '1.2.1',
        'php_version' => PHP_VERSION,
        'server_os' => PHP_OS,
        'modules' => ['VPEssential1', 'VPTelemetry'],
        'themes' => ['BasicTheme'],
        'timestamp' => now()->toIso8601String(),
    ]);

if ($response->successful()) {
    echo "Telemetry sent successfully";
} else {
    echo "Failed to send telemetry";
}
```

---

## Privacy & Security

**What is Collected:**
- ✅ Domain URL
- ✅ Server IP address
- ✅ VantaPress version
- ✅ PHP version
- ✅ Server OS
- ✅ List of enabled modules
- ✅ List of installed themes
- ✅ Installation/update timestamps

**What is NOT Collected:**
- ❌ Email addresses
- ❌ Usernames or passwords
- ❌ User-generated content
- ❌ Personal information
- ❌ Database contents
- ❌ Configuration values

**Security Measures:**
- Rate limiting (10 requests/hour per installation)
- Input validation and sanitization
- HTTPS required for production
- Anonymous data only
- Users can disable telemetry anytime

---

## Rate Limiting

**Per Installation:** 10 requests per hour

Exceeding this limit will result in a `429 Too Many Requests` response.

---

## Support

For issues or questions about the Telemetry API:
- GitHub: https://github.com/sepiroth-x/vantapress
- Email: support@vantapress.com
