<?php

use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['message' => 'pong', 'timestamp' => now()->toISOString()]);

Route::prefix('v1')->name('api.v1.')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
});
