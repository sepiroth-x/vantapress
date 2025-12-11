<?php

use Illuminate\Support\Facades\Route;
use Modules\VPTelemetryServer\Http\Controllers\TelemetryApiController;

/*
|--------------------------------------------------------------------------
| Telemetry API Routes
|--------------------------------------------------------------------------
|
| API endpoints for receiving telemetry data from VantaPress installations
|
*/

Route::post('/collect', [TelemetryApiController::class, 'collect'])
    ->name('telemetry.collect');

Route::get('/health', [TelemetryApiController::class, 'health'])
    ->name('telemetry.health');
