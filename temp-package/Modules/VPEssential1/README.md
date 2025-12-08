# VP Essential 1 Module

**The core functionality module for VantaPress CMS**

VP Essential 1 provides essential WordPress-like features for VantaPress, including theme customization, menu management, widget system, user profiles, and micro-blogging capabilities.

## Features

### 🎨 Theme Customizer
- Site branding (logo, favicon, title, tagline)
- Color customization (primary, accent colors)
- Hero section configuration (title, subtitle, CTA buttons)
- Background options (gradient or image)
- Footer customization
- Social media links
- Custom CSS/JS injection
- Dark mode toggle

### 🧭 Menu Builder
- Create unlimited menus
- Assign menus to locations (primary, footer)
- Drag-and-drop menu ordering
- Hierarchical menu structure
- Custom URLs and links
- Menu widget support

### 📦 Widget Manager
- Three default widget areas (header, footer, sidebar)
- Multiple widget types:
  - Text widgets
  - HTML widgets
  - Menu widgets
  - Recent posts widgets
- Drag-and-drop ordering
- Enable/disable widgets
- Widget-specific settings

### 👤 User Profiles
- Extended user information
- Custom display names
- Bio and avatar
- Website and location
- Social media links
- Profile statistics

### 🐦 Micro-Blogging (Tweets)
- Twitter-like functionality
- Create tweets (280 characters)
- Reply to tweets
- Retweet functionality
- Like system
- Soft deletes
- Tweet statistics

## Installation

### 1. Database Migration

Run the migrations to create required database tables:

```bash
php artisan migrate
```

This will create the following tables:
- `vp_theme_settings` - Theme configuration storage
- `vp_menus` - Menu definitions
- `vp_menu_items` - Menu item entries
- `vp_widget_areas` - Widget area definitions
- `vp_widgets` - Widget instances
- `vp_user_profiles` - Extended user data
- `vp_tweets` - Tweet posts
- `vp_tweet_likes` - Tweet like tracking

### 2. Service Provider Registration

The module automatically registers its service provider via ModuleLoader. Ensure `VPEssential1` is listed as active in your module configuration.

### 3. Widget Areas Setup

Default widget areas are automatically created on first access:
- **Header** - Widgets in the site header
- **Footer** - Widgets in the site footer
- **Sidebar** - Widgets in the sidebar

## Helper Functions

VP Essential 1 provides 12 global helper functions for theme integration:

### Theme Settings

```php
// Get a theme setting
$logo = vp_get_theme_setting('logo', '/default-logo.png');

// Set a theme setting
vp_set_theme_setting('site_title', 'My Awesome Site', 'string', 'theme');
```

### Menus

```php
// Get menu items for a location
$menuItems = vp_get_menu('primary');

foreach ($menuItems as $item) {
    echo '<a href="' . $item['url'] . '">' . $item['title'] . '</a>';
}
```

### Widgets

```php
// Render a widget area
echo vp_get_widget_area('header');
```

### Hero Configuration

```php
// Get hero section settings
$hero = vp_get_hero_config();

echo $hero['title'];
echo $hero['subtitle'];
echo $hero['primary_button']['text'];
```

### User Profiles

```php
// Get current user's profile
$profile = vp_get_current_user_profile();

// Get specific user's profile
$profile = vp_get_user_profile(1);

echo $profile->display_name;
echo $profile->bio;
```

### Tweets

```php
// Get recent tweets
$tweets = vp_get_recent_tweets(10);

foreach ($tweets as $tweet) {
    echo $tweet->content;
    echo $tweet->user->name;
}
```

### Widget Rendering

```php
// Render a widget by type
$html = vp_render_widget($widget);

// Render menu widget
$html = vp_render_menu_widget('primary');

// Render recent posts widget
$html = vp_render_recent_posts_widget(5);
```

## Filament Admin Pages

### Theme Customizer
**Navigation:** VP Essential → Theme Customizer

Configure all theme settings through a comprehensive tabbed interface:
- General settings (logo, favicon, dark mode)
- Color scheme
- Hero section
- Footer content
- Advanced (custom CSS/JS)

### Menu Builder
**Navigation:** VP Essential → Menu Builder

Manage navigation menus:
1. Create new menus
2. Assign locations (primary, footer)
3. Add menu items
4. Reorder items with drag-and-drop
5. Set hierarchical relationships

### Widget Manager
**Navigation:** VP Essential → Widget Manager

Organize widgets across widget areas:
1. Select a widget area
2. Add widgets (text, HTML, menu, recent posts)
3. Configure widget settings
4. Reorder with drag-and-drop
5. Enable/disable widgets

### User Profiles
**Navigation:** VP Essential → User Profiles

Manage user profile extensions:
- Edit display names and bios
- Upload avatars
- Add social media links
- View profile statistics

### Tweet Manager
**Navigation:** VP Essential → Tweets

Moderate micro-blog content:
- View all tweets with statistics
- Create new tweets
- View replies and retweets
- Delete or restore tweets
- Filter by author, type, date

## Database Schema

### vp_theme_settings
```
id, key, value, type, group, created_at, updated_at
```

### vp_menus
```
id, name, location, description, created_at, updated_at
```

### vp_menu_items
```
id, menu_id, parent_id, title, url, target, order, created_at, updated_at
```

### vp_widget_areas
```
id, name, slug, description, created_at, updated_at
```

### vp_widgets
```
id, widget_area_id, title, type, content, settings, order, is_active, created_at, updated_at
```

### vp_user_profiles
```
id, user_id, display_name, bio, avatar, website, location, social_links, created_at, updated_at
```

### vp_tweets
```
id, user_id, content, reply_to_id, retweet_of_id, created_at, updated_at, deleted_at
```

### vp_tweet_likes
```
id, user_id, tweet_id, created_at, updated_at
```

## Theme Integration

To integrate VP Essential 1 with your theme:

### 1. Check for Helper Functions

```php
@if(function_exists('vp_get_theme_setting'))
    <h1>{{ vp_get_theme_setting('site_title', config('app.name')) }}</h1>
@endif
```

### 2. Display Menus

```php
<nav>
    @foreach(vp_get_menu('primary') as $item)
        <a href="{{ $item['url'] }}" 
           target="{{ $item['target'] }}"
           class="{{ $item['class'] }}">
            {{ $item['title'] }}
        </a>
    @endforeach
</nav>
```

### 3. Display Widgets

```php
<aside>
    {!! vp_get_widget_area('sidebar') !!}
</aside>
```

### 4. Display Hero Section

```php
@php
    $hero = vp_get_hero_config();
@endphp

<section class="hero">
    <h1>{{ $hero['title'] }}</h1>
    <p>{{ $hero['subtitle'] }}</p>
    <a href="{{ $hero['primary_button']['url'] }}">
        {{ $hero['primary_button']['text'] }}
    </a>
</section>
```

## Configuration

Module metadata is stored in `module.json`:

```json
{
    "name": "VP Essential 1",
    "slug": "vp-essential-1",
    "version": "1.0.0",
    "description": "Essential features for VantaPress including theme customizer, menus, widgets, profiles, and tweeting system.",
    "active": true,
    "author": "VantaPress",
    "requires": []
}
```

## API Reference

### ThemeSetting Model
- `getValueAttribute()` - Automatic type conversion
- `setValueAttribute()` - Type-aware storage

### Menu Model
- `items()` - HasMany relationship
- `buildTree()` - Hierarchical structure

### Widget Model
- `moveUp()` - Reorder widget up
- `moveDown()` - Reorder widget down

### Tweet Model
- `user()` - BelongsTo relationship
- `replyTo()` - Parent tweet
- `retweetOf()` - Original tweet
- `replies()` - Child tweets
- `likes()` - Like relationships

## Security

- All forms use Laravel validation
- CSRF protection enabled
- XSS prevention via Blade escaping
- SQL injection protection via Eloquent
- File upload validation
- Authentication required for admin pages

## Performance

- Database queries optimized with eager loading
- Settings cached automatically
- Widget rendering cached
- JSON fields for flexible data

## Compatibility

- **Laravel:** 11.x
- **Filament:** 3.x
- **PHP:** 8.2+
- **MySQL:** 5.7+ / MariaDB 10.3+

## Support

For issues or questions:
- Check the VantaPress documentation
- Review helper function implementations
- Inspect database schema
- Test with The Villain Arise theme

## License

Same as VantaPress core.

---

**Built for VantaPress** | WordPress-like CMS powered by Laravel & Filament
