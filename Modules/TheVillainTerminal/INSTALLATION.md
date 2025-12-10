# The Villain Terminal - Installation Guide

## Quick Start

### 1. Module is Already Created

The Villain Terminal module is located in:
```
Modules/TheVillainTerminal/
```

### 2. Register the Module

Ensure the service provider is registered in VantaPress's module loading system.

The module should auto-register if your VantaPress installation scans the `Modules/` directory.

### 3. Access the Terminal

1. Log in to your VantaPress Admin Panel
2. Navigate to **System → Villain Terminal**
3. Start typing commands!

### 4. Verify Installation

Type this command in the terminal:
```bash
vanta-help
```

You should see a list of available commands.

## First Commands to Try

### Check VantaPress Version
```bash
vanta-version
```

### View System Information
```bash
vanta-system-info
```

### Run Migrations (if needed)
```bash
vanta-migrate
```

### Create a Test Theme Layout
```bash
vanta-make-theme-layout testLayout
```

## Troubleshooting

### "Access Denied" or Terminal Not Visible

The terminal is restricted to super admins only. Ensure:
- You're logged in as user ID 1, OR
- Your user has the `super_admin` role

### Commands Not Working

1. All commands must start with `vanta-`
2. Type `vanta-help` to see available commands
3. Check logs: `storage/logs/laravel.log`

### Module Not Loading

Verify the service provider is registered in your module configuration:
```json
// Modules/TheVillainTerminal/module.json
{
    "providers": [
        "Modules\\TheVillainTerminal\\TheVillainTerminalServiceProvider"
    ]
}
```

## Security Notes

- Only super admins can access the terminal
- No actual shell commands are executed
- All commands are whitelisted
- All inputs are sanitized
- Command execution is logged

## Support

For issues or questions:
- Check `storage/logs/laravel.log`
- Review the README.md file
- Visit: https://github.com/sepiroth-x/vantapress

---

**Happy Terminal-ing! 🚀**
