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
            // Check if VPSocial theme is active
            $isVPSocialActive = $activeTheme->slug === 'VPSocial';
            
            // If user is logged in AND VPSocial is active, redirect to newsfeed
            if (auth()->check() && $isVPSocialActive) {
                if (\Illuminate\Support\Facades\Route::has('social.newsfeed')) {
                    return redirect()->route('social.newsfeed');
                }
            }
            
            // For VPSocial theme, show landing page (for both guest and logged-in users without newsfeed)
            if ($isVPSocialActive) {
                return view('vpessential1::landing');
            }
            
            // For other themes, render the theme's homepage
            $view = $themeManager->getThemeView($activeTheme->slug, 'home');
            if (view()->exists($view)) {
                return view($view);
            }
            
            // Fallback to landing if theme home view doesn't exist
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
        // Check if social.newsfeed route exists before redirecting
        if (\Illuminate\Support\Facades\Route::has('social.newsfeed')) {
            return redirect()->route('social.newsfeed');
        }
        return redirect('/');
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
        
        // Redirect to newsfeed if route exists, otherwise home
        if (\Illuminate\Support\Facades\Route::has('social.newsfeed')) {
            return redirect()->route('social.newsfeed');
        }
        return redirect('/');
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
    if (\Illuminate\Support\Facades\Route::has('social.newsfeed')) {
        return redirect()->route('social.newsfeed')->with('success', 'Welcome to ' . config('app.name') . '! Start by creating your first post.');
    }
    return redirect('/')->with('success', 'Welcome to ' . config('app.name') . '!');
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
    
    // VP Social Groups Routes
    Route::prefix('social/groups')->name('social.groups.')->middleware(['vpsocial'])->group(function () {
        Route::get('/', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'create'])->name('create');
        Route::post('/', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'store'])->name('store');
        Route::get('/{slug}', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'show'])->name('show');
        Route::post('/{slug}/join', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'join'])->name('join');
        Route::post('/{slug}/leave', [\Modules\VPEssential1\Http\Controllers\GroupController::class, 'leave'])->name('leave');
    });
    
    // VP Social Stories Routes
    Route::prefix('social/stories')->name('social.stories.')->middleware(['vpsocial'])->group(function () {
        Route::get('/', [\Modules\VPEssential1\Http\Controllers\StoryController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\VPEssential1\Http\Controllers\StoryController::class, 'create'])->name('create');
        Route::post('/', [\Modules\VPEssential1\Http\Controllers\StoryController::class, 'store'])->name('store');
        Route::get('/{id}', [\Modules\VPEssential1\Http\Controllers\StoryController::class, 'show'])->name('show');
        Route::delete('/{id}', [\Modules\VPEssential1\Http\Controllers\StoryController::class, 'destroy'])->name('destroy');
    });
});

// Catch-all route for dynamic pages (must be last)
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])
    ->where('slug', '.*')
    ->name('page.show');
