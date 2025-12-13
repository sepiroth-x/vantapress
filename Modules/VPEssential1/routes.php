<?php

use Illuminate\Support\Facades\Route;
use Modules\VPEssential1\Controllers\ProfileController;
use Modules\VPEssential1\Controllers\PostController;
use Modules\VPEssential1\Controllers\FriendController;
use Modules\VPEssential1\Controllers\MessageController;
use Modules\VPEssential1\Controllers\CommentController;
use Modules\VPEssential1\Controllers\ReactionController;

/**
 * VP Essential 1 Module Routes
 * 
 * Register social networking routes for the module
 */

// Load helper functions
$helpersPath = __DIR__ . '/helpers/functions.php';
if (file_exists($helpersPath)) {
    require_once $helpersPath;
}

// Guest routes (registration, login pages - if enabled)
Route::prefix('social')->name('social.')->group(function () {
    Route::get('/register', function() {
        if (!\Modules\VPEssential1\Models\SocialSetting::isFeatureEnabled('registration')) {
            abort(404);
        }
        return view('vpessential1::auth.register');
    })->name('register');
});

// Authenticated routes
Route::prefix('social')->name('social.')->middleware(['auth', 'web', 'vpsocial'])->group(function () {
    
    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::get('/profile/{identifier}', 'show')->name('profile.user');
    });
    
    // Newsfeed & Posts routes
    Route::controller(PostController::class)->group(function () {
        Route::get('/newsfeed', 'index')->name('newsfeed');
        Route::post('/posts', 'store')->name('posts.store');
        Route::get('/posts/{post}', 'show')->name('posts.show');
        Route::get('/posts/{post}/edit', 'edit')->name('posts.edit');
        Route::put('/posts/{post}', 'update')->name('posts.update');
        Route::post('/posts/{post}/pin', 'pin')->name('posts.pin');
        Route::post('/posts/{post}/share', 'share')->name('posts.share');
        Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
    });
    
    // Friends routes
    Route::controller(FriendController::class)->group(function () {
        Route::get('/friends', 'index')->name('friends.index');
        Route::get('/friends/requests', 'requests')->name('friends.requests');
        Route::post('/friends/{identifier}/request', 'sendRequest')->name('friends.request');
        Route::post('/friends/{friend}/accept', 'acceptRequest')->name('friends.accept');
        Route::post('/friends/{friend}/reject', 'rejectRequest')->name('friends.reject');
        Route::delete('/friends/{identifier}', 'remove')->name('friends.remove');
    });
    
    // Messages routes
    Route::controller(MessageController::class)->group(function () {
        Route::get('/messages', 'index')->name('messages.index');
        Route::get('/messages/create/{identifier}', 'create')->name('messages.create');
        Route::get('/messages/{identifier}', 'show')->name('messages.show');
        Route::post('/messages/{identifier}', 'send')->name('messages.send');
    });
    
    // Comments routes
    Route::controller(CommentController::class)->group(function () {
        Route::post('/comments', 'store')->name('comments.store');
        Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
    });
    
    // Reactions routes
    Route::controller(ReactionController::class)->group(function () {
        Route::post('/reactions/toggle', 'toggle')->name('reactions.toggle');
    });
    
    // Hashtag routes
    Route::get('/hashtag/{tag}', [PostController::class, 'hashtag'])->name('hashtag');
    
    // Legacy tweet routes (keeping for backwards compatibility)
    Route::get('/tweets', function() {
        $tweets = vp_get_recent_tweets(20);
        return view('vpessential1::tweets.index', compact('tweets'));
    })->name('tweets');
});
