# VP Social Theme

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-Open%20Source-green)
![VantaPress](https://img.shields.io/badge/vantapress-%3E%3D1.2.0-purple)

A modern, feature-rich social networking theme for VantaPress CMS. Facebook and Twitter inspired design with full dark mode support and responsive layouts.

## 🎨 Features

### Design
- ✅ **Modern UI/UX** - Clean, intuitive interface inspired by leading social networks
- ✅ **Dark Mode** - Complete dark mode support with smooth transitions
- ✅ **Responsive Design** - Mobile-first approach, works on all devices
- ✅ **Tailwind CSS** - Built with utility-first CSS framework
- ✅ **Custom Components** - Reusable Blade components for consistent UI

### Social Features
- ✅ **User Profiles** - Customizable profiles with avatar & cover photos
- ✅ **Newsfeed** - Facebook-style newsfeed with post creation
- ✅ **Posts & Comments** - Share updates, photos, and engage with comments
- ✅ **Reactions** - Express yourself with 6 reaction types
- ✅ **Friends System** - Connect with friends and manage friend requests
- ✅ **Messaging** - Private conversations with real-time updates
- ✅ **Hashtags** - Discover trending topics
- ✅ **Notifications** - Stay updated with 14 notification types
- ✅ **Verification Badges** - Blue, gold, and gray badges for verified users

### Customization
- ✅ **Theme Customizer** - Full customizer integration
- ✅ **Color Schemes** - Customize primary, secondary, and accent colors
- ✅ **Layout Options** - Toggle sidebar, adjust posts per page
- ✅ **Feature Toggles** - Enable/disable features as needed

## 📋 Requirements

- VantaPress ≥ 1.2.0
- VPEssential1 Module ≥ 1.0.0
- PHP ≥ 8.2
- Laravel ≥ 11.0
- Tailwind CSS

## 🚀 Installation

### 1. Copy Theme Files
The theme is located in `/themes/VPSocial/` directory.

### 2. Activate Theme
In your `.env` file, set:
```env
CMS_ACTIVE_THEME=VPSocial
```

Or via admin panel:
- Navigate to **Admin Panel → Appearance → Themes**
- Activate **VP Social Theme**

### 3. Clear Cache
```bash
php artisan optimize:clear
```

### 4. Configure Theme
- Navigate to **Admin Panel → Appearance → Customize**
- Configure colors, layout, and features
- Save changes

## 📁 Theme Structure

```
VPSocial/
├── theme.json                    # Theme configuration
├── README.md                     # This file
├── layouts/
│   ├── app.blade.php            # Master layout
│   ├── social.blade.php         # Social feed layout
│   └── profile.blade.php        # Profile page layout
├── components/
│   ├── header.blade.php         # Navigation header
│   ├── footer.blade.php         # Footer section
│   ├── sidebar-left.blade.php   # Left sidebar (quick access)
│   └── sidebar-right.blade.php  # Right sidebar (suggestions)
├── views/
│   ├── profile/
│   │   ├── show.blade.php       # User profile page
│   │   └── edit.blade.php       # Profile editing
│   ├── posts/
│   │   └── index.blade.php      # Newsfeed
│   ├── friends/
│   │   ├── index.blade.php      # Friends list
│   │   └── requests.blade.php   # Friend requests
│   ├── messages/
│   │   ├── index.blade.php      # Inbox
│   │   └── show.blade.php       # Conversation view
│   └── components/
│       └── post-card.blade.php  # Reusable post component
└── assets/
    ├── css/
    │   └── social.css           # Theme stylesheet
    └── js/
        └── social.js            # Theme JavaScript
```

## 🎨 Customization

### Colors
Access **Admin Panel → Appearance → Customize → Colors**

- **Primary Color**: Main brand color (default: #1877f2 - Facebook blue)
- **Secondary Color**: Secondary actions (default: #42b72a - Green)
- **Accent Color**: Highlights (default: #1da1f2 - Twitter blue)
- **Dark Mode**: Toggle dark mode support

### Layout
Access **Admin Panel → Appearance → Customize → Social Features**

- **Newsfeed Style**: Facebook, Twitter, or Instagram style
- **Show Sidebar**: Toggle left/right sidebars
- **Posts Per Page**: Number of posts to display (5-100)

### Header
Access **Admin Panel → Appearance → Customize → Header**

- **Background Color**: Header background
- **Text Color**: Header text color
- **Show Search**: Toggle search bar
- **Show Notifications**: Toggle notifications icon

### Footer
Access **Admin Panel → Appearance → Customize → Footer**

- **Background Color**: Footer background
- **Text Color**: Footer text color
- **Copyright Text**: Custom copyright message

## 🔧 Configuration

### Feature Toggles
All social features can be enabled/disabled via **Admin Panel → VP Essential 1 → Social Settings**:

- Registration
- Profiles
- Friends & Followers
- Posts & Tweets
- Comments & Reactions
- Sharing & Hashtags
- Messaging & Notifications
- Verification Badges

### Content Limits
Configure limits for user-generated content:

- **Max Post Length**: 5000 characters (default)
- **Max Tweet Length**: 280 characters (default)
- **Posts Per Page**: 20 posts (default)

## 📱 Responsive Breakpoints

- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

All components are fully responsive and tested on various devices.

## 🎯 Routes

The theme uses the following main routes:

### Public
- `/social/register` - User registration (if enabled)

### Authenticated
- `/social/newsfeed` - Main newsfeed
- `/social/profile/{id}` - User profile
- `/social/profile/edit` - Edit profile
- `/social/friends` - Friends list
- `/social/friends/requests` - Friend requests
- `/social/messages` - Inbox
- `/social/messages/{id}` - Conversation
- `/social/notifications` - Notifications

## 🛠️ Development

### CSS Customization
Edit `assets/css/social.css` to customize styles. The theme uses:

- Tailwind CSS utility classes
- CSS custom properties for theming
- Dark mode classes with `.dark` prefix

### JavaScript Customization
Edit `assets/js/social.js` for custom functionality:

- AJAX interactions
- Real-time updates
- Form handling
- Infinite scroll

### Creating Custom Components
```blade
{{-- Example: Custom widget --}}
@extends('VPSocial::layouts.social')

@section('social-content')
    <div class="card">
        <div class="card-body">
            Your content here
        </div>
    </div>
@endsection
```

## 📚 Component Usage

### Using the Post Card Component
```blade
@include('VPSocial::components.post-card', [
    'post' => $post,
    'showActions' => true
])
```

### Using Layouts
```blade
{{-- For social pages --}}
@extends('VPSocial::layouts.social')

{{-- For profile pages --}}
@extends('VPSocial::layouts.profile')

{{-- For other pages --}}
@extends('VPSocial::layouts.app')
```

## 🐛 Troubleshooting

### Theme Not Showing
1. Check `.env` file: `CMS_ACTIVE_THEME=VPSocial`
2. Clear cache: `php artisan optimize:clear`
3. Check permissions on theme directory

### Styles Not Loading
1. Check asset paths in `layouts/app.blade.php`
2. Run: `php artisan storage:link`
3. Clear browser cache

### Dark Mode Not Working
1. Check theme customizer settings
2. Clear local storage in browser
3. Verify JavaScript is loading correctly

## 📄 License

Open Source - Free to use and modify

## 👨‍💻 Author

**VantaPress Team**
- Email: chardy.tsadiq02@gmail.com
- GitHub: [@sepiroth-x](https://github.com/sepiroth-x)
- Twitter: [@sepirothx000](https://x.com/sepirothx000)

## 🙏 Credits

Built with:
- [Laravel](https://laravel.com/) - PHP Framework
- [Tailwind CSS](https://tailwindcss.com/) - CSS Framework
- [Alpine.js](https://alpinejs.dev/) - JavaScript Framework
- [Heroicons](https://heroicons.com/) - SVG Icons

## 📝 Changelog

### Version 1.0.0 (December 12, 2025)
- ✅ Initial release
- ✅ Complete social networking features
- ✅ Dark mode support
- ✅ Responsive design
- ✅ Theme customizer integration
- ✅ Full documentation

## 🔮 Future Updates

Planned features for upcoming versions:
- Stories (Instagram-style)
- Live streaming
- Video calling
- Voice messages
- Advanced search
- PWA support
- Real-time WebSocket integration

## 💬 Support

For support, email chardy.tsadiq02@gmail.com or visit:
- GitHub: https://github.com/sepiroth-x/vantapress
- Documentation: See SOCIAL_FEATURES_DOCUMENTATION.md

---

**Made with ❤️ for VantaPress CMS**
