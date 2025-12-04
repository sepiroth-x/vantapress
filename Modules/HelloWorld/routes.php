<?php

use Illuminate\Support\Facades\Route;
use Modules\HelloWorld\Controllers\HelloWorldController;

/*
|--------------------------------------------------------------------------
| HelloWorld Module Routes
|--------------------------------------------------------------------------
|
| Example routes demonstrating VantaPress module routing capabilities.
| This file shows various routing patterns you can use in your modules.
|
| Route Documentation: https://laravel.com/docs/routing
|
*/

// Basic Routes
Route::prefix('hello')->name('hello.')->group(function () {
    
    // Main module page
    // URL: /hello
    // Route Name: hello.index
    Route::get('/', [HelloWorldController::class, 'index'])
        ->name('index');
    
    // Welcome page
    // URL: /hello/welcome
    // Route Name: hello.welcome
    Route::get('/welcome', [HelloWorldController::class, 'welcome'])
        ->name('welcome');
    
    // About page
    // URL: /hello/about
    // Route Name: hello.about
    Route::get('/about', [HelloWorldController::class, 'about'])
        ->name('about');
    
    // API endpoint example (returns JSON)
    // URL: /hello/api
    // Route Name: hello.api
    Route::get('/api', [HelloWorldController::class, 'apiExample'])
        ->name('api');
    
    // Form submission example (POST request)
    // URL: /hello/submit
    // Route Name: hello.submit
    Route::post('/submit', [HelloWorldController::class, 'submitForm'])
        ->name('submit');
});

/*
|--------------------------------------------------------------------------
| Advanced Routing Examples (commented out)
|--------------------------------------------------------------------------
|
| Uncomment these to see more advanced routing patterns:
|
*/

// Example: Protected route (requires authentication)
// Route::prefix('hello')->middleware('auth')->group(function () {
//     Route::get('/dashboard', [HelloWorldController::class, 'dashboard'])
//         ->name('hello.dashboard');
// });

// Example: API routes with rate limiting
// Route::prefix('api/hello')->middleware('throttle:60,1')->group(function () {
//     Route::get('/data', [HelloWorldController::class, 'getData']);
// });

// Example: Route with parameters
// Route::get('/hello/user/{id}', [HelloWorldController::class, 'showUser'])
//     ->name('hello.user.show')
//     ->where('id', '[0-9]+');

// Example: Resource routes (RESTful)
// Route::resource('hello/posts', HelloWorldPostController::class);
