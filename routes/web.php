<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ChannelController;
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
        Route::inertia('/login', 'Login')->name('loginForm');
        Route::post('/login', 'login')->name('login');
        Route::inertia('/register', 'Register')->name('registerForm');
        Route::post('/register', 'register')->name('register');
        Route::inertia('/forgot-password', 'ForgotPassword')->name('forgotPasswordForm');
        Route::post('/forgot-password', 'forgotPassword')->name('forgotPassword');
        Route::get('/reset-password', 'resetPasswordForm')->name('resetPasswordForm');

    });
Route::post('/reset-password', [AuthController::class,'resetPassword'])->name('password.reset');
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');


Route::controller(CabinetController::class)->middleware('auth')->group(function () {
    Route::get('/account', 'account')->name('account');
    Route::put('/account', 'update')->name('account.update');
    Route::post('/upload-avatar', 'uploadAvatar')->name('upload.avatar');
});

Route::resource('channels', ChannelController::class)->middleware('auth');

