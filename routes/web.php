<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// ============================================
// 公開ページ（認証不要）
// ============================================

// トップページは社員一覧へ遷移
Route::redirect('/', '/employees');

// ログイン・利用者登録
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});

// ============================================
// 認証必須ページ
// ============================================

Route::middleware('auth')->group(function (): void {
    // ログアウト
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 社員管理（詳細表示を除くCRUD操作）
    Route::resource('employees', EmployeeController::class)->except('show');
});
