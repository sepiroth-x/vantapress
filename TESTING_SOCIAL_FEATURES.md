# 🧪 Testing VP Social Features - Complete Guide

**Last Updated:** December 12, 2025

---

## 🎯 Quick Start

### Step 1: Enable VPSocial Theme

The theme is **already activated** in your `.env` file but may not appear in the admin list. Here's why and how to access it:

#### Why Theme Not Showing in List?
The VP Social Theme (`VPSocial`) is installed in the `themes/` directory but theme lists are typically populated from the database `themes` table, not the filesystem.

#### Option A: Use Theme Directly (Recommended for Testing)
The theme is already active via `.env`:
```env
CMS_ACTIVE_THEME=VPSocial
```

Clear caches and access social routes directly:
```bash
php artisan optimize:clear
```

Then visit: **http://127.0.0.1:8001/social/newsfeed**

#### Option B: Register Theme in Database
If you need it in the admin theme selector, you can manually add it:
```sql
INSERT INTO themes (name, slug, version, description, author, is_active, created_at, updated_at)
VALUES (
    'VP Social Theme',
    'VPSocial',
    '1.0.0',
    'Modern social networking theme for VantaPress',
    'VantaPress Team',
    1,
    NOW(),
    NOW()
);
```

---

## 🚀 Testing Social Features

### 1. **Create Test Users** (Required First!)

You need at least 2 users to test social features properly:

```bash
# Option A: Using Tinker
php artisan tinker
```

Then run:
```php
use App\Models\User;
use Modules\VPEssential1\Models\UserProfile;

// Create User 1
$user1 = User::create([
    'name' => 'John Doe',
    'email' => 'john@test.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now()
]);

UserProfile::create([
    'user_id' => $user1->id,
    'bio' => 'Software developer and tech enthusiast',
    'location' => 'New York, USA',
    'website' => 'https://johndoe.com'
]);

// Create User 2
$user2 = User::create([
    'name' => 'Jane Smith',
    'email' => 'jane@test.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now()
]);

UserProfile::create([
    'user_id' => $user2->id,
    'bio' => 'Designer & Creative Director',
    'location' => 'Los Angeles, USA',
    'website' => 'https://janesmith.com'
]);

echo "✅ Test users created!\n";
echo "Login: john@test.com / password\n";
echo "Login: jane@test.com / password\n";
```

---

### 2. **Test Routes & Features**

After logging in as one of the test users:

#### **A. Newsfeed (Home Page)**
- **URL:** http://127.0.0.1:8001/social/newsfeed
- **What to test:**
  - ✅ Page loads with VP Social Theme
  - ✅ Post creation form visible
  - ✅ Sidebar navigation (left)
  - ✅ Suggestions sidebar (right)
  - ✅ Dark mode toggle button
  - ✅ Navigation menu (Home, Profile, Friends, Messages)

#### **B. Create Posts**
- **Action:** Fill out "What's on your mind?" form
- **Test:**
  - ✅ Text post (up to 5000 characters)
  - ✅ Post with image upload
  - ✅ Visibility settings (Public/Friends/Private)
  - ✅ Post appears in newsfeed after creation

#### **C. Profile Pages**
- **Your Profile:** http://127.0.0.1:8001/social/profile
- **Other User:** http://127.0.0.1:8001/social/profile/2
- **Edit Profile:** http://127.0.0.1:8001/social/profile/edit
- **What to test:**
  - ✅ View user information
  - ✅ See user's posts
  - ✅ Friend count display
  - ✅ Avatar & cover photo
  - ✅ Edit bio, location, website
  - ✅ Upload profile picture

#### **D. Friend System**
- **Friends List:** http://127.0.0.1:8001/social/friends
- **Friend Requests:** http://127.0.0.1:8001/social/friends/requests
- **What to test:**
  1. ✅ Send friend request to another user
  2. ✅ Switch to other user account
  3. ✅ View friend requests notification
  4. ✅ Accept friend request
  5. ✅ View friends list
  6. ✅ Mutual friends display
  7. ✅ Remove friend

#### **E. Private Messaging**
- **Inbox:** http://127.0.0.1:8001/social/messages
- **New Conversation:** http://127.0.0.1:8001/social/messages/create/{userId}
- **What to test:**
  - ✅ Start conversation with friend
  - ✅ Send text messages
  - ✅ Send images
  - ✅ Real-time message display
  - ✅ Unread count badge
  - ✅ Mark messages as read
  - ✅ Conversation list

#### **F. Reactions & Comments**
- **Test on any post:**
  - ✅ Like button (6 reaction types: Like, Love, Laugh, Wow, Sad, Angry)
  - ✅ Add comment
  - ✅ Reply to comment (nested)
  - ✅ Delete own comment
  - ✅ Reaction count updates
  - ✅ Comment count updates

#### **G. Post Sharing**
- **Test:**
  - ✅ Click "Share" on any post
  - ✅ Share to your timeline
  - ✅ Share count increments
  - ✅ Original post linked

#### **H. Hashtags**
- **Test:**
  - ✅ Create post with #hashtag
  - ✅ Hashtag automatically extracted
  - ✅ Clickable hashtag links
  - ✅ View posts by hashtag
  - ✅ Trending hashtags sidebar

#### **I. Notifications**
- **What to test:**
  - ✅ Friend request notification
  - ✅ Comment notification
  - ✅ Reaction notification
  - ✅ Message notification
  - ✅ Unread badge in header
  - ✅ Mark as read
  - ✅ Click to navigate to source

---

### 3. **Test Dark Mode**

- **Toggle:** Click moon/sun icon in header
- **Test:**
  - ✅ All backgrounds change
  - ✅ Text colors invert
  - ✅ Cards remain readable
  - ✅ Sidebar adapts
  - ✅ Forms styled correctly
  - ✅ Setting persists on refresh

---

### 4. **Test Responsive Design**

Use browser dev tools (F12) to test:

- **Desktop:** 1920x1080 ✅
- **Tablet:** 768x1024 ✅
- **Mobile:** 375x667 ✅

**What to check:**
- ✅ Sidebars collapse on mobile
- ✅ Navigation becomes hamburger menu
- ✅ Post cards stack vertically
- ✅ Images scale properly
- ✅ Buttons remain clickable
- ✅ Forms adapt to screen size

---

## 🔧 Admin Panel Testing

### Social Settings Page

**Access:** http://127.0.0.1:8001/admin/social-settings

**Feature Toggles to Test:**
- ✅ Enable/Disable Registration
- ✅ Enable/Disable Profiles
- ✅ Enable/Disable Friends
- ✅ Enable/Disable Followers
- ✅ Enable/Disable Pokes
- ✅ Enable/Disable Posts
- ✅ Enable/Disable Tweets
- ✅ Enable/Disable Comments
- ✅ Enable/Disable Reactions
- ✅ Enable/Disable Sharing
- ✅ Enable/Disable Hashtags
- ✅ Enable/Disable Messaging
- ✅ Enable/Disable Notifications
- ✅ Enable/Disable Verification

**Settings:**
- ✅ Max Post Length (default: 5000)
- ✅ Max Tweet Length (default: 280)
- ✅ Posts Per Page (default: 20)

---

## 🐛 Troubleshooting

### Theme Not Loading?

```bash
# 1. Clear all caches
php artisan optimize:clear

# 2. Verify .env setting
grep "CMS_ACTIVE_THEME" .env
# Should show: CMS_ACTIVE_THEME=VPSocial

# 3. Check theme exists
ls -la themes/VPSocial

# 4. Restart server
php artisan serve --host=127.0.0.1 --port=8001
```

### Routes Not Working?

```bash
# Check routes registered
php artisan route:list --path=social

# Should show:
# GET|HEAD  social/newsfeed
# GET|HEAD  social/profile
# GET|HEAD  social/friends
# GET|HEAD  social/messages
# etc.
```

### Views Not Found?

```bash
# VPEssential1 module might not have views directory
# Theme views are in: themes/VPSocial/views/

# Check if theme views exist:
ls -la themes/VPSocial/views/
```

### Database Errors?

```bash
# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# All social tables should be "Ran":
# - vp_posts
# - vp_user_profiles
# - vp_friends
# - vp_messages
# - vp_comments
# - vp_reactions
# - vp_hashtags
# - vp_notifications
```

---

## 📊 Sample Test Scenarios

### Scenario 1: Complete User Journey

1. **Register/Login** as john@test.com
2. **Complete Profile** - Add bio, avatar, cover
3. **Create Post** - "Hello VantaPress! 🚀 #socialmedia"
4. **Find Friend** - Navigate to jane@test.com's profile
5. **Send Friend Request**
6. **Logout & Login** as jane@test.com
7. **Accept Friend Request**
8. **Comment** on John's post
9. **React** with ❤️ to John's post
10. **Send Message** to John
11. **Switch back to John** and check notifications
12. **Reply to message**
13. **Toggle Dark Mode**
14. **Test on mobile view**

### Scenario 2: Content Moderation

1. **Create inappropriate post**
2. **Delete post** (as owner)
3. **Try to delete others' posts** (should fail)
4. **Try to edit others' profiles** (should fail)
5. **Admin:** Access admin panel
6. **Disable feature** (e.g., comments)
7. **Verify** feature hidden on frontend
8. **Re-enable** feature

### Scenario 3: Performance Test

1. **Create 20+ posts**
2. **Check pagination** works
3. **Add 50+ comments** to single post
4. **Verify** load times acceptable
5. **Check** database queries (use debugbar if installed)
6. **Test** caching effectiveness

---

## ✅ Expected Results

After testing all features, you should see:

- ✅ **Theme:** Modern, responsive social network UI
- ✅ **Features:** All social features functional
- ✅ **Performance:** Pages load in <2 seconds
- ✅ **Mobile:** Fully responsive on all devices
- ✅ **Dark Mode:** Smooth toggle, all elements styled
- ✅ **Security:** Authorization checks working
- ✅ **UX:** Intuitive navigation, clear actions
- ✅ **Data:** All posts, comments, reactions persist

---

## 🎨 Theme Customization

### Via Admin Panel (Future)
Once theme is registered in database, you can customize via:
- **Admin → Appearance → Themes → VP Social → Customize**

### Via Code (Now)
Edit theme files directly:
- **Styles:** `themes/VPSocial/assets/css/social.css`
- **JavaScript:** `themes/VPSocial/assets/js/social.js`
- **Layouts:** `themes/VPSocial/layouts/*.blade.php`
- **Components:** `themes/VPSocial/components/*.blade.php`

---

## 📞 Need Help?

- **Logs:** Check `storage/logs/laravel.log`
- **Errors:** Use browser DevTools Console (F12)
- **Database:** Use phpMyAdmin or TablePlus
- **Server:** Ensure PHP 8.5+ and MySQL 8.0+ running

---

**Happy Testing! 🚀**
