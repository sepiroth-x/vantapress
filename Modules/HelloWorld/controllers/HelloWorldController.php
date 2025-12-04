<?php

namespace Modules\HelloWorld\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * HelloWorld Module Controller
 * 
 * This is an example controller demonstrating VantaPress module structure.
 * Use this as a template for creating your own module controllers.
 * 
 * @package Modules\HelloWorld
 * @version 1.0.0
 */
class HelloWorldController extends Controller
{
    /**
     * Display the module index page
     * 
     * This method demonstrates:
     * - Returning a view with data
     * - Using the module's view namespace
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('HelloWorld::index', [
            'title' => 'Hello World Module',
            'message' => 'This is an example VantaPress module!',
            'features' => [
                'Clean MVC Architecture',
                'Laravel Blade Templates',
                'Isolated Routes & Controllers',
                'Easy to Understand',
                'Ready for Production'
            ],
            'version' => '1.0.0',
        ]);
    }

    /**
     * Display the welcome page
     * 
     * This method demonstrates:
     * - Simple view rendering
     * - Passing dynamic data to views
     * 
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        return view('HelloWorld::welcome', [
            'title' => 'Welcome to HelloWorld',
            'message' => 'Welcome to the HelloWorld module! This demonstrates how easy it is to create custom pages.',
            'timestamp' => now()->format('F j, Y \a\t g:i A'),
        ]);
    }

    /**
     * Example: API endpoint returning JSON
     * 
     * This method demonstrates:
     * - Returning JSON responses
     * - Building APIs within modules
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiExample()
    {
        return response()->json([
            'success' => true,
            'module' => 'HelloWorld',
            'version' => '1.0.0',
            'message' => 'This is an example API endpoint',
            'timestamp' => now()->toIso8601String(),
            'data' => [
                'features' => [
                    'RESTful API support',
                    'JSON responses',
                    'Easy integration',
                ],
                'documentation' => url('/hello'),
            ],
        ]);
    }

    /**
     * Example: Form handling with validation
     * 
     * This method demonstrates:
     * - Form validation
     * - Flash messages
     * - Redirects
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // In a real module, you would:
        // - Save to database
        // - Send email notifications
        // - Process the data

        // For this example, we just flash a success message
        return redirect()
            ->route('hello.index')
            ->with('success', "Thank you, {$validated['name']}! Your message has been received.");
    }

    /**
     * Example: About page with module information
     * 
     * This method demonstrates:
     * - Reading module metadata
     * - Displaying module information
     * 
     * @return \Illuminate\View\View
     */
    public function about()
    {
        // In a real module, you might read from module.json
        $moduleInfo = [
            'name' => 'Hello World',
            'version' => '1.0.0',
            'description' => 'A comprehensive example module for VantaPress developers',
            'author' => 'VantaPress',
            'license' => 'Open Source',
        ];

        return view('HelloWorld::about', [
            'moduleInfo' => $moduleInfo,
        ]);
    }
}
