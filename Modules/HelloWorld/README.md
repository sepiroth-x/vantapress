# HelloWorld Module

A simple example module demonstrating VantaPress module structure.

## Structure

```
HelloWorld/
├── module.json         # Module metadata
├── routes.php          # Module routes
├── controllers/        # Controllers
│   └── HelloWorldController.php
├── views/              # Blade templates
│   ├── index.blade.php
│   └── welcome.blade.php
└── README.md
```

## Installation

1. Upload the module ZIP file via Filament admin panel
2. Navigate to **Extensions > Modules**
3. Click **Install Module**
4. Enable the module

## Routes

- `GET /hello` - Module index page
- `GET /hello/welcome` - Welcome page

## Module Metadata (module.json)

```json
{
    "name": "Hello World",
    "slug": "HelloWorld",
    "version": "1.0.0",
    "description": "A simple example module",
    "author": "VantaPress",
    "active": true
}
```

## Creating Your Own Module

1. Copy this module structure
2. Update `module.json` with your module details
3. Create your routes in `routes.php`
4. Add controllers in `controllers/`
5. Add views in `views/`
6. ZIP the module folder
7. Install via Filament admin

## License

MIT
