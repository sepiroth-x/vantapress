<?php

use Illuminate\Support\Facades\Route;
use Modules\HelloWorld\Controllers\HelloWorldController;

/*
|--------------------------------------------------------------------------
| HelloWorld Module Routes
|--------------------------------------------------------------------------
|
| Example routes for the HelloWorld module
|
*/

Route::prefix('hello')->group(function () {
    Route::get('/', [HelloWorldController::class, 'index'])->name('hello.index');
    Route::get('/welcome', [HelloWorldController::class, 'welcome'])->name('hello.welcome');
});
