<?php

use App\Http\Controllers\Api\EmployeeController;
use Illuminate\Support\Facades\Route;

// APIサーバーの稼働確認用エンドポイント
Route::get('/ping', fn () => ['message' => 'pong', 'timestamp' => now()->toISOString()]);

// Sanctum認証が必要な社員情報API（読み取り専用）
Route::prefix('v1')->name('api.v1.')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);
});
