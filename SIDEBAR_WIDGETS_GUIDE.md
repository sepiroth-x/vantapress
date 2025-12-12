# VantaPress Sidebar Widgets Guide

## Overview
Yes! VantaPress has a complete widget system similar to WordPress, where you can add custom sidebars (left and right) with various widgets through the admin panel.

---

## Widget System Architecture

VantaPress uses a **Zone-based Widget System** that allows you to:
- Create custom widget zones (sidebar-left, sidebar-right, footer, header, etc.)
- Assign multiple widgets to each zone
- Control widget order with drag-and-drop (in admin panel)
- Enable/disable widgets without deleting them
- Configure widget settings per instance

---

## How to Add Sidebars (Like WordPress)

### Step 1: Register Widget Zones

Widget zones are defined in your **theme's configuration** or through the **Admin Panel**.

#### Option A: Through Admin Panel (Recommended)
1. Login to admin at `http://yoursite.com/admin`
2. Navigate to **VP Essential 1 → Widgets** (coming soon in next update)
3. Click **"Add Widget Zone"**
4. Configure:
   - **Name**: `sidebar-left` or `sidebar-right`
   - **Display Name**: "Left Sidebar" or "Right Sidebar"
   - **Description**: "Main left sidebar for blog pages"
   - **Status**: Active

#### Option B: Programmatically (For Developers)

Create a migration or seeder:

```php
use Modules\VPEssential1\Models\WidgetZone;

WidgetZone::create([
    'name' => 'sidebar-left',
    'display_name' => 'Left Sidebar',
    'description' => 'Widgets displayed on the left side',
    'is_active' => true,
]);

WidgetZone::create([
    'name' => 'sidebar-right',
    'display_name' => 'Right Sidebar', 
    'description' => 'Widgets displayed on the right side',
    'is_active' => true,
]);
```

---

### Step 2: Display Widget Zones in Your Views

In your theme's blade templates (e.g., `posts/index.blade.php`):

```blade
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-12 gap-6">
        
        {{-- LEFT SIDEBAR --}}
        @if(vp_has_widgets('sidebar-left'))
            <aside class="col-span-12 lg:col-span-3">
                {!! vp_render_widgets('sidebar-left') !!}
            </aside>
        @endif
        
        {{-- MAIN CONTENT --}}
        <main class="col-span-12 {{ vp_has_widgets('sidebar-left') || vp_has_widgets('sidebar-right') ? 'lg:col-span-6' : 'lg:col-span-12' }}">
            {{-- Your main content (posts, etc.) --}}
            @yield('content')
        </main>
        
        {{-- RIGHT SIDEBAR --}}
        @if(vp_has_widgets('sidebar-right'))
            <aside class="col-span-12 lg:col-span-3">
                {!! vp_render_widgets('sidebar-right') !!}
            </aside>
        @endif
        
    </div>
</div>
```

---

## Available Widget Types

VantaPress comes with built-in widget types (more can be added):

### 1. **Text Widget**
Display custom HTML/text content
```php
type: 'text'
settings: [
    'title' => 'About Us',
    'content' => '<p>Welcome to our social network!</p>'
]
```

### 2. **Menu Widget**
Display navigation menus
```php
type: 'menu'
settings: [
    'menu_id' => 1,
    'title' => 'Quick Links'
]
```

### 3. **Recent Posts Widget**
Show latest posts with thumbnails
```php
type: 'recent_posts'
settings: [
    'title' => 'Recent Posts',
    'count' => 5,
    'show_thumbnails' => true
]
```

### 4. **User Stats Widget** (VP Social)
Display user profile statistics
```php
type: 'user_stats'
settings: [
    'title' => 'Your Stats',
    'show_posts' => true,
    'show_friends' => true,
    'show_likes' => true
]
```

### 5. **Trending Hashtags Widget**
Show popular hashtags
```php
type: 'trending_hashtags'
settings: [
    'title' => 'Trending',
    'count' => 10,
    'days' => 7
]
```

### 6. **Friend Suggestions Widget**
Suggest new friends to connect with
```php
type: 'friend_suggestions'
settings: [
    'title' => 'People You May Know',
    'count' => 5
]
```

---

## Adding Widgets Through Admin

### Using Filament Admin Panel

1. **Navigate to Widgets**
   - Login to `/admin`
   - Go to **VP Essential 1 → Widgets**

2. **Create New Widget**
   - Click **"New Widget"**
   - Select Widget Type from dropdown
   - Configure settings
   - Assign to Zone (sidebar-left, sidebar-right, etc.)
   - Set order number (1 = first, 2 = second, etc.)
   - Enable/Disable toggle

3. **Manage Widgets**
   - Drag to reorder
   - Edit settings
   - Duplicate widgets
   - Delete unused widgets

---

## Creating Custom Widget Types

### Step 1: Create Widget Class

Create `Modules/VPEssential1/Widgets/CustomWidget.php`:

```php
<?php

namespace Modules\VPEssential1\Widgets;

use Modules\VPEssential1\Widgets\BaseWidget;

class CustomWidget extends BaseWidget
{
    public static function getName(): string
    {
        return 'Custom Widget';
    }
    
    public static function getType(): string
    {
        return 'custom_widget';
    }
    
    public static function getSettingsSchema(): array
    {
        return [
            'title' => [
                'type' => 'text',
                'label' => 'Widget Title',
                'default' => 'My Custom Widget'
            ],
            'custom_field' => [
                'type' => 'text',
                'label' => 'Custom Field',
                'default' => ''
            ],
        ];
    }
    
    public function render(): string
    {
        $title = $this->getSetting('title', 'My Custom Widget');
        $customField = $this->getSetting('custom_field', '');
        
        return view('vpessential1::widgets.custom', [
            'title' => $title,
            'customField' => $customField,
        ])->render();
    }
}
```

### Step 2: Create Widget View

Create `Modules/VPEssential1/views/widgets/custom.blade.php`:

```blade
<div class="widget custom-widget bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4">
    @if($title)
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
            {{ $title }}
        </h3>
    @endif
    
    <div class="widget-content">
        <p class="text-gray-700 dark:text-gray-300">
            {{ $customField }}
        </p>
    </div>
</div>
```

### Step 3: Register Widget

Add to `VPEssential1ServiceProvider.php`:

```php
use Modules\VPEssential1\Services\WidgetService;
use Modules\VPEssential1\Widgets\CustomWidget;

public function boot(): void
{
    // ... existing code ...
    
    // Register custom widgets
    WidgetService::registerWidget(CustomWidget::class);
}
```

---

## Helper Functions

VantaPress provides convenient helper functions for widgets:

### `vp_has_widgets($zone)`
Check if a zone has active widgets
```blade
@if(vp_has_widgets('sidebar-left'))
    <aside>...</aside>
@endif
```

### `vp_render_widgets($zone)`
Render all widgets in a zone
```blade
{!! vp_render_widgets('sidebar-left') !!}
```

### `vp_get_widgets($zone)`
Get array of widgets for manual rendering
```php
$widgets = vp_get_widgets('sidebar-left');
foreach($widgets as $widget) {
    echo $widget->render();
}
```

### `vp_widget_count($zone)`
Get number of widgets in a zone
```blade
Sidebar has {{ vp_widget_count('sidebar-left') }} widgets
```

---

## Example: Complete Two-Sidebar Layout

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-12 gap-6">
        
        {{-- LEFT SIDEBAR (1/4 width) --}}
        @if(vp_has_widgets('sidebar-left'))
            <aside class="col-span-12 lg:col-span-3">
                <div class="sticky top-4 space-y-4">
                    {!! vp_render_widgets('sidebar-left') !!}
                </div>
            </aside>
        @endif
        
        {{-- MAIN CONTENT (1/2 width) --}}
        <main class="col-span-12 
                     {{ vp_has_widgets('sidebar-left') && vp_has_widgets('sidebar-right') ? 'lg:col-span-6' : 'lg:col-span-9' }}">
            
            {{-- Newsfeed / Posts --}}
            @foreach($posts as $post)
                @include('vpessential1::components.post-card', ['post' => $post])
            @endforeach
            
        </main>
        
        {{-- RIGHT SIDEBAR (1/4 width) --}}
        @if(vp_has_widgets('sidebar-right'))
            <aside class="col-span-12 lg:col-span-3">
                <div class="sticky top-4 space-y-4">
                    {!! vp_render_widgets('sidebar-right') !!}
                </div>
            </aside>
        @endif
        
    </div>
</div>
@endsection
```

---

## Widget Styling

Widgets use consistent Tailwind CSS classes for theming:

```css
/* Base widget container */
.widget {
    @apply bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-4;
}

/* Widget title */
.widget h3 {
    @apply text-lg font-bold text-gray-900 dark:text-white mb-3 pb-2 border-b border-gray-200 dark:border-gray-700;
}

/* Widget content */
.widget-content {
    @apply text-gray-700 dark:text-gray-300;
}

/* Widget list items */
.widget ul li {
    @apply py-2 border-b border-gray-100 dark:border-gray-700 last:border-0;
}
```

---

## Database Schema

Widgets are stored in two tables:

### `vp_widget_zones`
```sql
id, name, display_name, description, is_active, created_at, updated_at
```

### `vp_widgets`
```sql
id, zone_id, type, title, settings (JSON), order, is_active, created_at, updated_at
```

Example settings JSON:
```json
{
    "title": "Recent Posts",
    "count": 5,
    "show_thumbnails": true,
    "show_dates": true
}
```

---

## WordPress Comparison

| WordPress | VantaPress |
|-----------|------------|
| `register_sidebar()` | `WidgetZone::create()` |
| `dynamic_sidebar('sidebar-1')` | `vp_render_widgets('sidebar-left')` |
| `is_active_sidebar()` | `vp_has_widgets()` |
| Widget admin panel | Filament admin at `/admin` |
| `widgets_init` hook | `WidgetService::registerWidget()` |

---

## Advanced: Conditional Widget Display

Control widget visibility based on conditions:

```php
// In your Widget class
public function shouldDisplay(): bool
{
    // Only show on blog pages
    if (request()->is('blog/*')) {
        return true;
    }
    
    // Only show to logged-in users
    if (auth()->check()) {
        return true;
    }
    
    return false;
}
```

---

## Next Steps

1. ✅ Widget zones exist in database
2. ✅ Widget system is implemented
3. 🔄 Add Filament admin interface (coming in next update)
4. 🔄 Pre-built social widgets (trending, suggestions, stats)
5. 🔄 Drag-and-drop widget ordering in admin

**Current Status:** Backend ready, admin UI needs Filament resource creation

---

## Quick Start Checklist

- [ ] Create widget zones (sidebar-left, sidebar-right)
- [ ] Add widgets through database or admin
- [ ] Update your theme template to include sidebars
- [ ] Style widgets with Tailwind CSS
- [ ] Test responsive behavior (sidebars stack on mobile)

---

**Need Help?** Check the widget tables in your database:
```sql
SELECT * FROM vp_widget_zones;
SELECT * FROM vp_widgets;
```

The widget system is fully functional and ready to use! Just need to create the Filament admin interface for easier management. For now, you can manage widgets programmatically or through database.
