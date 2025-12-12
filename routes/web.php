<?php

use Illuminate\Support\Facades\Route;
use App\Models\Theme;
use App\Services\CMS\ThemeManager;

Route::get('/', function (ThemeManager $themeManager) {
    try {
        // Check if database is configured and accessible
        $pdo = \DB::connection()->getPdo();
        
        // Check if themes table exists (installation complete)
        $tablesExist = \DB::select("SHOW TABLES LIKE 'themes'");
        
        if (empty($tablesExist)) {
            // Installation not complete, redirect to installer
            if (file_exists(base_path('install.php'))) {
                return redirect('/install.php');
            }
            // Fallback to welcome page if installer deleted
            return view('welcome');
        }
        
        // Check if there's an active theme
        $activeTheme = Theme::where('is_active', true)->first();
        
        if ($activeTheme) {
            // If user is logged in, redirect to newsfeed
            if (auth()->check()) {
                return redirect()->route('social.newsfeed');
            }
            
            // Guest user - show landing page
            return view('vpessential1::landing');
        }
        
        // Fallback to default welcome page
        return view('welcome');
    } catch (\Exception $e) {
        // Database not configured yet, redirect to installer
        if (file_exists(base_path('install.php'))) {
            return redirect('/install.php');
        }
        // Fallback to welcome page if installer deleted
        return view('welcome');
    }
})->name('home');

// Authentication routes
Route::get('/login', function() {
    if (auth()->check()) {
        return redirect()->route('social.newsfeed');
    }
    return view('vpessential1::landing');
})->name('login');

Route::post('/login', function(\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|string',
        'password' => 'required',
    ]);

    // Determine if input is email or username
    $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $loginCredentials = [
        $loginField => $credentials['email'],
        'password' => $credentials['password']
    ];

    if (auth()->attempt($loginCredentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        
        // Clear any intended URL from previous session
        $request->session()->forget('url.intended');
        
        // Always redirect to newsfeed for social logins
        return redirect()->route('social.newsfeed');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

Route::post('/logout', function(\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/register', function() {
    if (auth()->check()) {
        return redirect()->route('social.newsfeed');
    }
    return view('vpessential1::auth.register');
})->name('register');

Route::post('/register', function(\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'accepted',
    ]);

    // Generate username from name (lowercase, no spaces)
    $baseUsername = strtolower(str_replace(' ', '', $validated['name']));
    $username = $baseUsername;
    $counter = 1;
    
    // Ensure username is unique
    while (\App\Models\User::where('username', $username)->exists()) {
        $username = $baseUsername . $counter;
        $counter++;
    }

    // Create user
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'username' => $username,
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    // Create user profile automatically
    \Modules\VPEssential1\Models\UserProfile::create([
        'user_id' => $user->id,
        'display_name' => $validated['name'],
        'bio' => 'New to ' . config('app.name', 'VP Social') . '!',
    ]);

    // Assign default role (if using Spatie permissions)
    if (class_exists(\Spatie\Permission\Models\Role::class)) {
        try {
            $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);
            $user->assignRole($userRole);
        } catch (\Exception $e) {
            \Log::warning('Could not assign user role: ' . $e->getMessage());
        }
    }

    // Log the user in
    auth()->login($user);
    $request->session()->regenerate();

    // Redirect with welcome message
    return redirect()->route('social.newsfeed')->with('success', 'Welcome to ' . config('app.name') . '! Start by creating your first post.');
});

Route::get('/old-landing', function () {
    return view('landing');
})->name('old.landing');

// Additional routes will be registered by modules

Route::get('/livewire-test', function() { return view('livewire-test'); });

// Theme Customizer (Full-page, WordPress-style)
Route::middleware(['auth'])->group(function () {
    Route::get('/theme-customizer/{id}', [App\Http\Controllers\ThemeCustomizerController::class, 'show'])
        ->name('theme-customizer.show');
    Route::post('/theme-customizer/{id}/save', [App\Http\Controllers\ThemeCustomizerController::class, 'save'])
        ->name('theme-customizer.save');
    Route::post('/theme-customizer/{id}/reset', [App\Http\Controllers\ThemeCustomizerController::class, 'reset'])
        ->name('theme-customizer.reset');
    Route::post('/theme-customizer/{id}/activate', [App\Http\Controllers\ThemeCustomizerController::class, 'activate'])
        ->name('theme-customizer.activate');
    Route::get('/theme-customizer/{id}/elements', [App\Http\Controllers\ThemeCustomizerController::class, 'getElements'])
        ->name('theme-customizer.elements');
    Route::get('/theme-customizer/{id}/pages', [App\Http\Controllers\ThemeCustomizerController::class, 'getPages'])
        ->name('theme-customizer.pages');
    Route::post('/theme-customizer/{id}/save-layout-template', [App\Http\Controllers\ThemeCustomizerController::class, 'saveLayoutTemplate'])
        ->name('theme-customizer.save-layout-template');
    Route::get('/theme-customizer/{id}/layout-templates', [App\Http\Controllers\ThemeCustomizerController::class, 'getLayoutTemplates'])
        ->name('theme-customizer.layout-templates');
});

// Catch-all route for dynamic pages (must be last)
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('slug', '.*')
    ->name('page.show');
