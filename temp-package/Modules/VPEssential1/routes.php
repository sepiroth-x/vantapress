<?php

use Illuminate\Support\Facades\Route;

/**
 * VP Essential 1 Module Routes
 * 
 * Register public and API routes for the module
 */

// Load helper functions
$helpersPath = __DIR__ . '/helpers/functions.php';
if (file_exists($helpersPath)) {
    require_once $helpersPath;
}

// Public routes (optional - for future frontend pages)
Route::prefix('vp')->name('vp.')->group(function () {
    // Profile routes
    Route::get('/profile/{user}', function($user) {
        $profile = vp_get_user_profile($user);
        return view('VPEssential1::profile', compact('profile'));
    })->name('profile');
    
    // Tweets feed
    Route::get('/tweets', function() {
        $tweets = vp_get_recent_tweets(20);
        return view('VPEssential1::tweets', compact('tweets'));
    })->name('tweets');
});
