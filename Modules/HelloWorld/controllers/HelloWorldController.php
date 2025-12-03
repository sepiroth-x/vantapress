<?php

namespace Modules\HelloWorld\Controllers;

use App\Http\Controllers\Controller;

/**
 * HelloWorld Module Controller
 * Example controller demonstrating module structure
 */
class HelloWorldController extends Controller
{
    /**
     * Display the module index page
     */
    public function index()
    {
        return view('HelloWorld::index', [
            'title' => 'Hello World Module',
            'message' => 'This is an example VantaPress module!',
        ]);
    }

    /**
     * Display the welcome page
     */
    public function welcome()
    {
        return view('HelloWorld::welcome', [
            'title' => 'Welcome',
            'message' => 'Welcome to the HelloWorld module!',
        ]);
    }
}
