<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CreationStudioController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', [HomeController::class, 'videos'])->name('videos');
        Route::get('/articles', [HomeController::class, 'articles'])->name('articles');
    });

Route::controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::inertia('/login', 'Auth/Login')->name('loginForm');
        Route::post('/login', 'login')->name('login');
        Route::inertia('/register', 'Auth/Register')->name('registerForm');
        Route::post('/register', 'register')->name('register');
        Route::inertia('/forgot-password', 'Auth/ForgotPassword')->name('forgotPasswordForm');
        Route::post('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::get('/reset-password', 'resetPasswordForm')->name('resetPasswordForm');

    });
Route::post('/reset-password', [AuthController::class,'resetPassword'])->name('password.reset');
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('account')->name('account.')->middleware(['auth'])->group(function () {
    Route::controller(AccountController::class)->group(function () {
        Route::get('/', 'account')->name('index');
        Route::put('/', 'update')->name('update');
        Route::post('/upload-avatar', 'uploadAvatar')->name('upload.avatar');
    });
    Route::resource('channels', ChannelController::class)->middleware('auth');

    Route::prefix('studio')->name('studio.')->controller(CreationStudioController::class)->group(function () {
       Route::get('/', 'index')->name('index');
    });
});
Route::resource('contents', ContentController::class)->middleware('auth')->except(['index']);




