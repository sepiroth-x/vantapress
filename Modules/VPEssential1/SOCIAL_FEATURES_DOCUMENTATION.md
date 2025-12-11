# VPEssential1 - Complete Social Networking Module

**Version:** 2.0.0  
**Author:** VantaPress  
**License:** Open Source

## 🚀 Overview

VPEssential1 is a comprehensive social networking module for VantaPress CMS that transforms your website into a fully-featured social platform with Facebook and Twitter-style features.

## ✨ Features

### User Profiles
- **Facebook-style profiles** with avatar, cover photo, bio, and social links
- Customizable profile settings
- Public profile pages
- Profile visibility controls

### Social Connections
- **Friend System**: Send/accept/reject friend requests
- **Follower System**: Follow users without mutual approval
- **Poke Feature**: Send playful pokes to friends
- Mutual friends display
- Friend suggestions algorithm

### Content Creation
- **Posts (Facebook-style)**: Rich status updates with photos, videos, and links
- **Tweets (Twitter-style)**: Micro-blogging with 280 character limit
- **Visibility Control**: Public, Friends Only, or Private posts
- **Media Uploads**: Attach images and videos to posts
- **Link Previews**: Automatic link metadata extraction

### Engagement Features
- **Comments**: Nested comment threads
- **Reactions**: 6 reaction types (Like, Love, Haha, Wow, Sad, Angry)
- **Shares**: Share posts with optional commentary
- **Hashtags**: Automatic hashtag extraction and trending topics
- **@Mentions**: Tag users in posts and comments

### Messaging
- **Private Conversations**: One-on-one messaging
- **Group Chats**: Multi-user conversations
- **Read Receipts**: Track message read status
- **File Attachments**: Share files in messages
- **Mute Conversations**: Silence notifications for specific chats

### Notifications
- Real-time notification system
- 14 notification types:
  - Friend requests & acceptances
  - New followers
  - Pokes
  - Messages
  - Post likes, comments, and shares
  - Tweet likes, replies, and retweets
  - Comment likes and replies
  - @Mentions
- Unread count tracking
- Mark all as read functionality

### Verification System
- **Blue Badge**: Verified users
- **Gold Badge**: Premium/VIP users
- **Gray Badge**: Organization accounts
- Admin-controlled verification

### Admin Features
- **Social Settings Page**: Toggle all features on/off
- **Content Management**: Full CRUD for posts, tweets, comments
- **User Moderation**: Manage users, friends, messages
- **Analytics Dashboard**: Social engagement metrics
- **Hashtag Management**: Trending topics control

### Theme Integration
- **Theme Customizer**: Visual theme editor
- **Menu Builder**: Drag-and-drop menu creation
- **Widget System**: Customizable widget areas
- **Helper Functions**: Easy integration with themes

## 📊 Database Structure

**23 Database Tables:**
- `vp_user_profiles` - User profile data
- `vp_friends` - Friend relationships
- `vp_followers` - Follower relationships
- `vp_pokes` - Poke interactions
- `vp_posts` - Facebook-style posts
- `vp_post_shares` - Post sharing data
- `vp_tweets` - Twitter-style tweets
- `vp_tweet_likes` - Tweet likes
- `vp_comments` - Comments (polymorphic)
- `vp_comment_likes` - Comment likes
- `vp_reactions` - Reactions (polymorphic)
- `vp_conversations` - Message conversations
- `vp_conversation_participants` - Conversation members
- `vp_messages` - Private messages
- `vp_hashtags` - Hashtag data
- `vp_hashtaggables` - Hashtag relationships (polymorphic)
- `vp_notifications` - User notifications
- `vp_verifications` - Verification badges
- `vp_social_settings` - Feature toggles
- `vp_theme_settings` - Theme configuration
- `vp_menus` - Menu definitions
- `vp_menu_items` - Menu items
- `vp_widgets` - Widget instances
- `vp_widget_areas` - Widget areas

## 🛠️ Installation

1. **Migrations Run Automatically** (VantaPress Auto-Migration System)
2. **Configure Settings**:
   - Navigate to Admin Panel → VP Essential 1 → Social Settings
   - Enable/disable features as needed
3. **Set Permissions** (optional):
   - Configure user roles for social features

## 🎨 Usage

### Routes

All routes are prefixed with `/social` and require authentication:

```php
// Profile
GET  /social/profile              - View own profile
GET  /social/profile/{userId}     - View user profile
GET  /social/profile/edit         - Edit profile
PUT  /social/profile              - Update profile

// Newsfeed & Posts
GET  /social/newsfeed             - View newsfeed
POST /social/posts                - Create post
GET  /social/posts/{post}         - View single post
POST /social/posts/{post}/share   - Share post
DEL  /social/posts/{post}         - Delete post

// Friends
GET  /social/friends              - List friends
GET  /social/friends/requests     - View friend requests
POST /social/friends/{id}/request - Send friend request
POST /social/friends/{id}/accept  - Accept friend request
POST /social/friends/{id}/reject  - Reject friend request
DEL  /social/friends/{id}         - Remove friend

// Messages
GET  /social/messages             - List conversations
GET  /social/messages/{id}        - View conversation
POST /social/messages/{id}        - Send message
GET  /social/messages/create/{id} - Start conversation

// Comments
POST /social/comments             - Add comment
DEL  /social/comments/{id}        - Delete comment

// Reactions
POST /social/reactions/toggle     - Toggle reaction
```

### In Blade Templates

```blade
{{-- Display user profile --}}
@if(auth()->user()->profile)
    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}">
    <h1>{{ auth()->user()->profile->display_name }}</h1>
@endif

{{-- Check if feature is enabled --}}
@if(\Modules\VPEssential1\Models\SocialSetting::isFeatureEnabled('posts'))
    <a href="{{ route('social.newsfeed') }}">Newsfeed</a>
@endif

{{-- Display user verification badge --}}
@if(auth()->user()->isVerified())
    <span class="text-blue-500">✓</span>
@endif
```

### In Controllers

```php
use Modules\VPEssential1\Models\Post;
use Modules\VPEssential1\Services\HashtagService;

// Create a post with hashtags
$post = Post::create([
    'user_id' => auth()->id(),
    'content' => 'Hello #VantaPress #Laravel',
    'visibility' => 'public',
]);

// Extract and attach hashtags
app(HashtagService::class)->extractAndAttach($post, $post->content);
```

## ⚙️ Configuration

### Feature Toggles

Control features via Social Settings page or programmatically:

```php
use Modules\VPEssential1\Models\SocialSetting;

// Get setting
$enabled = SocialSetting::get('enable_posts', true);

// Set setting
SocialSetting::set('enable_registration', false);

// Check if feature is enabled
if (SocialSetting::isFeatureEnabled('messaging')) {
    // Messaging is enabled
}
```

### Content Limits

Configure in Social Settings:
- `max_post_length` - Maximum post content length (default: 5000)
- `max_tweet_length` - Maximum tweet length (default: 280)
- `posts_per_page` - Posts per newsfeed page (default: 20)

## 🔐 Security

- All routes require authentication
- CSRF protection enabled
- User ownership validation on content modification
- Admin-only verification management
- Configurable visibility controls

## 🎯 API Endpoints (Future)

API endpoints can be added by creating controllers in `Controllers/Api/` directory.

## 📱 Frontend Integration

VPEssential1 provides controllers and backend logic. Frontend views need to be created in your theme:

```
themes/your-theme/views/
├── social/
│   ├── newsfeed.blade.php
│   ├── profile.blade.php
│   ├── messages.blade.php
│   └── ...
```

Or use the included VP Social Theme (see Phase 10).

## 🔌 Extending

### Custom Notification Types

```php
// In your custom code
\Modules\VPEssential1\Services\NotificationService::create([
    'user_id' => $userId,
    'from_user_id' => auth()->id(),
    'type' => 'custom_event',
    'title' => 'Custom Event',
    'message' => 'Something happened',
    'link' => route('custom.page'),
]);
```

### Custom Hashtag Processing

```php
use Modules\VPEssential1\Services\HashtagService;

$hashtagService = app(HashtagService::class);

// Extract hashtags
$tags = $hashtagService->extract('#Laravel #PHP #VantaPress');
// Returns: ['Laravel', 'PHP', 'VantaPress']

// Search by hashtag
$posts = $hashtagService->search('VantaPress', Post::class);

// Get trending hashtags
$trending = $hashtagService->getTrending(10);
```

## 📚 Model Relationships

### User Model Extensions

All relationships added to `App\Models\User`:

```php
// Profile
$user->profile

// Posts & Tweets
$user->posts
$user->tweets

// Friends
$user->friends()
$user->friendRequestsSent()
$user->friendRequestsReceived()

// Followers
$user->followers()
$user->following()

// Social
$user->comments
$user->reactions
$user->messages
$user->conversations
$user->vpNotifications()

// Verification
$user->verification
$user->isVerified()

// Helper Methods
$user->isFriendsWith($userId)
$user->isFollowing($userId)
```

## 🐛 Troubleshooting

### Migrations Not Running
```bash
php artisan migrate
```

### Views Not Found
Ensure module is active in `vp_modules` table.

### Routes Not Working
Clear route cache:
```bash
php artisan route:clear
php artisan route:cache
```

## 📈 Performance

- Indexed database columns for fast queries
- Lazy loading relationships
- Counter caches for likes/comments/shares
- Efficient polymorphic relationships

## 🤝 Contributing

This module is part of VantaPress CMS. Contributions welcome!

## 📞 Support

- **GitHub**: https://github.com/sepiroth-x/vantapress
- **Email**: chardy.tsadiq02@gmail.com
- **Facebook**: https://www.facebook.com/sepirothx/

## 📝 Changelog

### v2.0.0 (2025-12-08)
- ✅ Complete social networking platform
- ✅ 16 new models added
- ✅ 9 new database migrations
- ✅ 6 controllers with full CRUD
- ✅ 3 service classes (Hashtag, Notification, Social)
- ✅ Comprehensive route system
- ✅ Filament admin resources
- ✅ Feature toggle system
- ✅ User model extensions

### v1.0.0
- Theme Customizer
- Menu Builder
- Widget System
- Basic Profiles
- Tweet System

## 📄 License

Open Source - MIT License

---

**VantaPress CMS** - Modular. Powerful. Social.
