<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;

// ホームページ
Route::get('/', [UserController::class, 'index']);

Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance');

// 登録ページと登録処理
Route::get('/register', [UserController::class, 'showRegister']);
Route::post('/register', [UserController::class, 'register']);

// ログインページとログイン処理
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);

// 認証が必要なルート
Route::middleware('auth')->group(function() {
    Route::get('/home', [UserController::class, 'index'])->name('index');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
});

// 勤怠管理
Route::post('/workStart', [AttendanceController::class, 'workStart'])->middleware('auth');
Route::post('/workEnd', [AttendanceController::class,'workEnd'])->middleware('auth');
Route::post('/breakStart', [AttendanceController::class, 'breakStart'])->middleware('auth');
Route::post('/breakEnd', [AttendanceController::class,'breakEnd'])->middleware('auth');

Route::get('/changeDate', [AttendanceController::class, 'changeDate']);