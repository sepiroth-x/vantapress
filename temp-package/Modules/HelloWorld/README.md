# 🧩 HelloWorld Module - Developer Guide

**Version:** 1.0.0  
**Author:** VantaPress  
**License:** Open Source

A comprehensive example module demonstrating VantaPress module architecture and development patterns. This module serves as a **template and learning resource** for developers creating custom VantaPress modules (plugins).

---

## 📚 Table of Contents

1. [What is a VantaPress Module?](#what-is-a-vantapress-module)
2. [Module Structure](#module-structure)
3. [Quick Start](#quick-start)
4. [File Breakdown](#file-breakdown)
5. [Creating Your Own Module](#creating-your-own-module)
6. [Best Practices](#best-practices)
7. [Advanced Features](#advanced-features)

---

## 🎯 What is a VantaPress Module?

VantaPress modules (also called **plugins**) are self-contained packages that extend the functionality of your VantaPress installation. They follow **Laravel package conventions** with a simplified structure.

### Key Features:
- ✅ **No compilation required** - Pure PHP, no build tools
- ✅ **Hot-swappable** - Enable/disable without code changes
- ✅ **Isolated** - Own routes, controllers, views, and assets
- ✅ **Database integration** - Can create migrations and models
- ✅ **Admin panel integration** - Hooks into FilamentPHP
- ✅ **WordPress-inspired** - Familiar plugin architecture

---

## 📁 Module Structure

```
HelloWorld/
├── 📄 module.json              # Module metadata (required)
├── 📄 routes.php               # Module routes (required)
├── 📄 README.md                # Documentation (recommended)
├── 📂 controllers/             # Controllers directory
│   └── HelloWorldController.php
├── 📂 views/                   # Blade templates
│   ├── index.blade.php
│   └── welcome.blade.php
├── 📂 models/                  # Eloquent models (optional)
├── 📂 migrations/              # Database migrations (optional)
├── 📂 config/                  # Configuration files (optional)
└── 📂 assets/                  # CSS, JS, images (optional)
    ├── css/
    ├── js/
    └── images/
```

---

## 🚀 Quick Start

### Installing This Module

1. **Admin Panel Method** (Recommended)
   - Login to `/admin`
   - Navigate to **Extensions > Modules (Plugins)**
   - Click **Create** or **Upload Module**
   - Select `HelloWorld.zip`
   - Click **Enable Module**

2. **Manual Installation**
   - Upload `HelloWorld/` folder to `Modules/` directory
   - Module will be auto-discovered
   - Enable via admin panel

### Testing the Module

After enabling, visit:
- **http://yourdomain.com/hello** - Main module page
- **http://yourdomain.com/hello/welcome** - Welcome page

---

## 📝 File Breakdown

### 1. `module.json` (Required)

The **module manifest** - contains metadata and configuration.

```json
{
    "name": "Hello World",                    // Display name
    "slug": "HelloWorld",                     // Unique identifier (matches folder name)
    "version": "1.0.0",                       // Semantic versioning
    "description": "Example module...",       // Short description
    "author": "VantaPress",                   // Your name
    "author_email": "email@example.com",      // Contact email
    "author_url": "https://example.com",      // Your website
    "license": "Open Source",                 // License type
    "social_links": {                         // Optional social links
        "github": "https://github.com/...",
        "facebook": "https://facebook.com/...",
        "twitter": "https://x.com/..."
    },
    "active": true                            // Default state
}
```

### 2. `routes.php` (Required)

Define your module's routes using Laravel routing conventions.

```php
<?php
use Illuminate\Support\Facades\Route;
use Modules\HelloWorld\Controllers\HelloWorldController;

// Group routes with prefix
Route::prefix('hello')->group(function () {
    // GET /hello
    Route::get('/', [HelloWorldController::class, 'index'])
        ->name('hello.index');
    
    // GET /hello/welcome
    Route::get('/welcome', [HelloWorldController::class, 'welcome'])
        ->name('hello.welcome');
    
    // POST /hello/submit
    Route::post('/submit', [HelloWorldController::class, 'submit'])
        ->name('hello.submit');
});
```

**Best Practices:**
- ✅ Use `prefix()` to namespace your routes
- ✅ Name your routes with `name()`
- ✅ Follow RESTful conventions
- ✅ Use middleware when needed: `->middleware('auth')`

### 3. Controllers

Handle business logic and return responses.

```php
<?php
namespace Modules\HelloWorld\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    /**
     * Display the module index page
     */
    public function index()
    {
        return view('HelloWorld::index', [
            'title' => 'Hello World',
            'message' => 'Welcome to VantaPress modules!',
        ]);
    }
    
    /**
     * Handle form submission
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Process data...
        
        return redirect()
            ->route('hello.index')
            ->with('success', 'Form submitted!');
    }
}
```

**View Resolution:**
- `'HelloWorld::index'` → `Modules/HelloWorld/views/index.blade.php`
- Namespace format: `{ModuleSlug}::{viewName}`

### 4. Views (Blade Templates)

Create your frontend using Laravel Blade.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $message }}</p>
    
    {{-- Blade directives work --}}
    @if($showButton)
        <button>Click Me</button>
    @endif
    
    {{-- Include other views --}}
    @include('HelloWorld::partials.footer')
</body>
</html>
```

---

## 🛠️ Creating Your Own Module

### Step 1: Plan Your Module

**Example: "Contact Form" Module**

```
Module Name: Contact Form
Slug: ContactForm
Features:
- Contact form with validation
- Email notification
- Store submissions in database
- Admin panel to view submissions
```

### Step 2: Create Module Structure

```bash
Modules/
└── ContactForm/
    ├── module.json
    ├── routes.php
    ├── README.md
    ├── controllers/
    │   └── ContactController.php
    ├── models/
    │   └── ContactSubmission.php
    ├── migrations/
    │   └── 2025_01_01_000000_create_contact_submissions_table.php
    ├── views/
    │   ├── form.blade.php
    │   └── admin/
    │       └── list.blade.php
    └── config/
        └── contact.php
```

### Step 3: Create `module.json`

```json
{
    "name": "Contact Form",
    "slug": "ContactForm",
    "version": "1.0.0",
    "description": "Add a contact form to your site with email notifications",
    "author": "Your Name",
    "author_email": "you@example.com",
    "license": "MIT",
    "active": true
}
```

### Step 4: Define Routes (`routes.php`)

```php
<?php
use Illuminate\Support\Facades\Route;
use Modules\ContactForm\Controllers\ContactController;

Route::prefix('contact')->group(function () {
    Route::get('/', [ContactController::class, 'show'])
        ->name('contact.show');
    
    Route::post('/submit', [ContactController::class, 'submit'])
        ->name('contact.submit');
});

// Admin routes (protected)
Route::prefix('admin/contact-submissions')
    ->middleware(['auth', 'web'])
    ->group(function () {
        Route::get('/', [ContactController::class, 'adminList'])
            ->name('admin.contact.list');
    });
```

### Step 5: Create Controller

```php
<?php
namespace Modules\ContactForm\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\ContactForm\Models\ContactSubmission;

class ContactController extends Controller
{
    public function show()
    {
        return view('ContactForm::form');
    }
    
    public function submit(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:5000',
        ]);
        
        // Save to database
        $submission = ContactSubmission::create($validated);
        
        // Send email
        Mail::to(config('contact.recipient'))
            ->send(new \App\Mail\ContactFormMail($submission));
        
        return back()->with('success', 'Message sent!');
    }
    
    public function adminList()
    {
        $submissions = ContactSubmission::latest()->paginate(20);
        return view('ContactForm::admin.list', compact('submissions'));
    }
}
```

### Step 6: Create Model (Optional)

```php
<?php
namespace Modules\ContactForm\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = ['name', 'email', 'message'];
    
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
```

### Step 7: Create Migration (Optional)

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('contact_submissions');
    }
};
```

### Step 8: Create Views

**`views/form.blade.php`:**
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
</head>
<body>
    <h1>Contact Us</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <form method="POST" action="{{ route('contact.submit') }}">
        @csrf
        
        <input type="text" name="name" placeholder="Your Name" required>
        @error('name')<span>{{ $message }}</span>@enderror
        
        <input type="email" name="email" placeholder="Your Email" required>
        @error('email')<span>{{ $message }}</span>@enderror
        
        <textarea name="message" placeholder="Your Message" required></textarea>
        @error('message')<span>{{ $message }}</span>@enderror
        
        <button type="submit">Send Message</button>
    </form>
</body>
</html>
```

### Step 9: Package Your Module

Create a ZIP file containing the module folder:

```
ContactForm.zip
└── ContactForm/
    ├── module.json
    ├── routes.php
    ├── controllers/
    ├── models/
    ├── migrations/
    └── views/
```

### Step 10: Install & Test

1. Upload ZIP via admin panel: **Extensions > Modules**
2. Enable the module
3. Test at `/contact`
4. Check admin panel for submissions

---

## ✅ Best Practices

### 1. Naming Conventions

```php
// ✅ GOOD
Modules/MyAwesomePlugin/
- Slug: MyAwesomePlugin (PascalCase)
- Namespace: Modules\MyAwesomePlugin\
- Routes: /my-awesome-plugin/... (kebab-case)

// ❌ BAD
Modules/my_plugin/          // Inconsistent casing
Modules/MyPlugin123/        // Numbers in names
```

### 2. Database Tables

```php
// ✅ Prefix your tables
Schema::create('myplugin_posts', function (Blueprint $table) {
    // ...
});

// ❌ Don't conflict with core tables
Schema::create('posts', function (Blueprint $table) {
    // Might conflict!
});
```

### 3. Configuration

```php
// ✅ Use config files
// config/myplugin.php
return [
    'api_key' => env('MYPLUGIN_API_KEY'),
    'timeout' => 30,
];

// Access: config('myplugin.api_key')
```

### 4. Error Handling

```php
// ✅ Graceful error handling
try {
    $result = $this->doSomething();
} catch (\Exception $e) {
    \Log::error('MyPlugin error: ' . $e->getMessage());
    return back()->with('error', 'An error occurred');
}
```

### 5. Security

```php
// ✅ Always validate input
$validated = $request->validate([
    'email' => 'required|email|max:255',
]);

// ✅ Use CSRF protection
<form method="POST">
    @csrf
    ...
</form>

// ✅ Sanitize output
{{ $userInput }}  // Auto-escaped by Blade
```

---

## 🚀 Advanced Features

### 1. Admin Panel Integration (FilamentPHP)

Create a Filament Resource for your module:

```php
<?php
namespace Modules\ContactForm\Filament;

use Filament\Resources\Resource;
use Filament\Tables;

class ContactSubmissionResource extends Resource
{
    protected static ?string $navigationGroup = 'Extensions';
    protected static ?string $navigationLabel = 'Contact Submissions';
    
    // Define your resource...
}
```

### 2. Custom Middleware

```php
// routes.php
Route::middleware(['custom-middleware'])->group(function () {
    // Protected routes
});
```

### 3. Event Listeners

```php
// Listen to Laravel events
Event::listen('user.registered', function ($user) {
    // Do something when user registers
});
```

### 4. Service Providers

For complex modules, create a service provider:

```php
<?php
namespace Modules\ContactForm\Providers;

use Illuminate\Support\ServiceProvider;

class ContactFormServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register services
    }
    
    public function boot()
    {
        // Boot services, load routes, views, etc.
    }
}
```

### 5. Assets Management

```php
// In your view
<link rel="stylesheet" href="{{ asset('modules/ContactForm/assets/css/style.css') }}">
<script src="{{ asset('modules/ContactForm/assets/js/script.js') }}"></script>
```

---

## 📖 Additional Resources

- **Laravel Documentation:** https://laravel.com/docs
- **FilamentPHP Docs:** https://filamentphp.com/docs
- **VantaPress GitHub:** https://github.com/sepiroth-x/vantapress
- **Community Forum:** (Coming Soon)

---

## 🐛 Troubleshooting

**Module not appearing?**
- Check `module.json` syntax (valid JSON)
- Verify slug matches folder name
- Clear cache: `php artisan cache:clear`

**Routes not working?**
- Check `routes.php` syntax
- Verify module is enabled in admin panel
- Clear route cache: `php artisan route:clear`

**Views not loading?**
- Use correct namespace: `ModuleSlug::viewName`
- Check file paths are correct
- Case sensitivity matters on Linux!

---

## 📝 License

This example module is open source. Feel free to use it as a template for your own modules.

**Created by:** VantaPress Team  
**Version:** 1.0.0  
**Last Updated:** December 4, 2025

---

## 💡 Need Help?

**Community Support:**
- GitHub Issues: https://github.com/sepiroth-x/vantapress/issues
- GitHub Discussions: https://github.com/sepiroth-x/vantapress/discussions

**Professional Development:**
- Email: chardy.tsadiq02@gmail.com
- Mobile: +63 915 0388 448

---

**Happy Module Development! 🎉**
