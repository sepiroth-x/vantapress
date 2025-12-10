# The Villain Terminal

**Version:** 1.0.0  
**Author:** VantaPress  
**License:** MIT

## Overview

The Villain Terminal is a web-based terminal interface for VantaPress that provides powerful command-line functionality without requiring SSH or terminal access. Perfect for shared hosting environments.

## Features

### ✅ Core Features

- **Web-Based Terminal Interface** - Full terminal experience in your browser
- **No SSH Required** - Works on any shared hosting environment
- **Module & Core Migration Runner** - Run migrations without `php artisan migrate`
- **Theme Layout Generator** - Create and export theme layouts
- **System Information** - Display PHP, Filament, and VantaPress versions
- **Command History** - Navigate previous commands with UP/DOWN arrows
- **Autocomplete** - TAB completion for available commands
- **Extensible** - Other modules can register custom commands

### 🎨 Terminal UI

- Dark theme matching VantaPress aesthetic
- Clean, modern terminal interface
- Scrollable command history
- Color-coded output (success, error, warning)
- Real-time command execution
- Keyboard shortcuts

## Available Commands

### Migration Commands

```bash
vanta-migrate
```
Runs all pending migrations from core and modules. Does NOT use `php artisan` - runs migrations directly using Laravel's database layer.

### System Information

```bash
vanta-system-info      # Complete system information
vanta-php-version      # PHP version and extensions
vanta-filament-version # Filament version and features
vanta-version          # VantaPress version
```

### Theme Layout Commands

```bash
vanta-make-theme-layout {layoutName}   # Create new theme layout
vanta-export-layout {layoutName}       # Export layout as ZIP
```

### Help

```bash
vanta-help    # Display all available commands
vanta-h       # Alias for vanta-help
```

### Aliases

```bash
vanta-m      # Alias for vanta-migrate
vanta-info   # Alias for vanta-system-info
```

## Installation

1. The module is located in `Modules/TheVillainTerminal/`
2. Ensure the module is registered in VantaPress
3. Access the terminal via **Admin Panel → System → Villain Terminal**

## Security

- **Super Admin Only** - Only users with super_admin role or user ID 1 can access
- **No Shell Commands** - Never executes actual shell/terminal commands
- **Input Sanitization** - All inputs are sanitized
- **Command Prefix Required** - All commands must start with `vanta-`
- **Whitelisted Commands** - Only registered commands can execute

## Usage Examples

### Running Migrations

```bash
richard@villain-terminal:~$ vanta-migrate

Starting migration process...

Found 5 migration file(s)

Found 2 pending migration(s):
  - 2025_12_07_create_example_table
  - 2025_12_07_add_column_to_users

Migrating: 2025_12_07_create_example_table
✓ Migrated: 2025_12_07_create_example_table

Migrating: 2025_12_07_add_column_to_users
✓ Migrated: 2025_12_07_add_column_to_users

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Migration Summary:
  Executed: 2
  Batch: #3
```

### Creating Theme Layout

```bash
richard@villain-terminal:~$ vanta-make-theme-layout myCustomTheme

Creating theme layout: myCustomTheme

  ✓ Created: app.blade.php
  ✓ Created: partials/header.blade.php
  ✓ Created: partials/footer.blade.php
  ✓ Created: components/card.blade.php
  ✓ Created: theme.json
  ✓ Created: README.md

✓ Theme layout 'myCustomTheme' created successfully!

Location: resources/views/layouts/myCustomTheme
```

### Exporting Theme Layout

```bash
richard@villain-terminal:~$ vanta-export-layout myCustomTheme

Exporting theme layout: myCustomTheme

Adding files to archive...
  ✓ app.blade.php
  ✓ partials/header.blade.php
  ✓ partials/footer.blade.php
  ✓ components/card.blade.php
  ✓ theme.json
  ✓ README.md

✓ Export completed successfully!

Archive: myCustomTheme_2025-12-07_143022.zip
Size: 4.2 KB

Download URL: /storage/exports/myCustomTheme_2025-12-07_143022.zip
```

## Extending with Custom Commands

Other modules can register custom commands:

```php
use Modules\TheVillainTerminal\Services\CommandRegistry;

// In your module's service provider:
public function boot(): void
{
    CommandRegistry::register(
        'vanta-my-command',
        function($args) {
            return [
                'output' => 'Command executed successfully!',
                'success' => true
            ];
        },
        'Description of my command'
    );
}
```

### Using Class Methods

```php
CommandRegistry::register(
    'vanta-my-command',
    MyCommandClass::class . '@handle',
    'Description of my command'
);
```

### Command Handler Response Format

```php
return [
    'output' => 'Text to display in terminal',
    'success' => true,  // or false for errors
];
```

## Architecture

### Directory Structure

```
TheVillainTerminal/
├── Commands/               # Built-in command classes
│   ├── MigrateCommand.php
│   ├── SystemInfoCommand.php
│   ├── ThemeLayoutCommand.php
│   └── HelpCommand.php
├── Services/              # Core services
│   ├── CommandRegistry.php
│   └── TerminalExecutor.php
├── Filament/
│   └── Pages/
│       └── VillainTerminal.php
├── resources/
│   └── views/
│       └── terminal.blade.php
├── module.json
├── TheVillainTerminalServiceProvider.php
└── RouteServiceProvider.php
```

### How It Works

1. **User Input** → Terminal UI (Blade + JavaScript)
2. **Livewire** → Sends command to backend
3. **TerminalExecutor** → Parses command and arguments
4. **CommandRegistry** → Looks up command handler
5. **Command Handler** → Executes the command
6. **Response** → Returns formatted output
7. **Terminal UI** → Displays result

## Troubleshooting

### Command Not Found

Ensure the command starts with `vanta-`. Type `vanta-help` to see all available commands.

### Access Denied

Only super admins can access the terminal. Check user roles and permissions.

### Migration Errors

Check `storage/logs/laravel.log` for detailed error messages. The terminal logs all operations.

## Version History

### 1.0.0 (December 7, 2025)
- Initial release
- Migration runner for core and module migrations
- System information commands
- Theme layout generator and exporter
- Command extensibility system
- Terminal UI with history and autocomplete

## Credits

Created for VantaPress CMS  
Built with Laravel, Filament, and Livewire

---

**For support, visit:** https://github.com/sepiroth-x/vantapress
