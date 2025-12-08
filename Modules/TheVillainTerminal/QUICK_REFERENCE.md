# The Villain Terminal - Quick Reference Card

## 🚀 Quick Start

**Access:** Admin Panel → System → Villain Terminal  
**Prefix:** All commands start with `vanta-`  
**Help:** Type `vanta-help` anytime

---

## 📋 Command Reference

### 🔧 Core Commands

| Command | Description | Example |
|---------|-------------|---------|
| `vanta-help` | Show all commands | `vanta-help` |
| `vanta-version` | VantaPress version | `vanta-version` |
| `clear` | Clear terminal | `clear` |

### 🗄️ Database

| Command | Description | Example |
|---------|-------------|---------|
| `vanta-migrate` | Run all migrations | `vanta-migrate` |

### 📊 System Info

| Command | Description | Example |
|---------|-------------|---------|
| `vanta-system-info` | Complete system info | `vanta-system-info` |
| `vanta-php-version` | PHP version & extensions | `vanta-php-version` |
| `vanta-filament-version` | Filament info | `vanta-filament-version` |

### 🎨 Theme Layouts

| Command | Description | Example |
|---------|-------------|---------|
| `vanta-make-theme-layout` | Create layout | `vanta-make-theme-layout blogTheme` |
| `vanta-export-layout` | Export as ZIP | `vanta-export-layout blogTheme` |

### ⚡ Aliases

| Alias | Full Command |
|-------|--------------|
| `vanta-h` | `vanta-help` |
| `vanta-m` | `vanta-migrate` |
| `vanta-info` | `vanta-system-info` |

---

## ⌨️ Keyboard Shortcuts

| Key | Action |
|-----|--------|
| `↑` UP | Previous command |
| `↓` DOWN | Next command |
| `TAB` | Autocomplete |
| `ENTER` | Execute command |

---

## 🎨 Output Colors

Commands use color coding:
- 🟢 **Green** - Success
- 🔴 **Red** - Error
- 🟡 **Yellow** - Warning
- 🔵 **Cyan** - Info

---

## 💡 Common Use Cases

### Fresh Installation Setup
```bash
vanta-system-info    # Check system
vanta-migrate        # Run migrations
vanta-version        # Verify version
```

### Create New Theme
```bash
vanta-make-theme-layout myTheme
vanta-export-layout myTheme
```

### System Diagnostics
```bash
vanta-system-info
vanta-php-version
vanta-filament-version
```

---

## 🔒 Security

- ✅ Super Admin Only
- ✅ No Shell Commands
- ✅ Input Sanitized
- ✅ Fully Logged

---

## 📝 For Developers

### Register Custom Command

```php
use Modules\TheVillainTerminal\Services\CommandRegistry;

CommandRegistry::register(
    'vanta-mycommand',
    function($args) {
        return [
            'output' => 'Hello!',
            'success' => true
        ];
    },
    'My command description'
);
```

### Command Response Format

```php
return [
    'output' => 'Text output',
    'success' => true  // or false
];
```

---

## 🆘 Troubleshooting

### Can't see terminal?
→ You must be super admin

### Command not found?
→ Type `vanta-help` to see all commands

### Command failed?
→ Check `storage/logs/laravel.log`

---

**Terminal Location:** `Modules/TheVillainTerminal/`  
**Documentation:** See `README.md` in module folder

---

**🦹 Happy Terminal-ing!**
