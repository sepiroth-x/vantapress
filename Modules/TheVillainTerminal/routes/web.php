<?php

use Illuminate\Support\Facades\Route;
use Modules\TheVillainTerminal\Http\Controllers\TerminalController;

Route::middleware(['web', 'auth'])->prefix('admin/terminal')->group(function () {
    Route::post('/execute', [TerminalController::class, 'execute'])->name('terminal.execute');
});
