<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', [HomeController::class, 'videos'])->name('videos');
        Route::get('/articles', [HomeController::class, 'articles'])->name('articles');
    });

Route::controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');
    });

Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
Route::inertia('/login', 'Login')->name('loginForm');
Route::inertia('/register', 'Register')->name('registerForm');


Route::controller(CabinetController::class)->group(function () {
    Route::get('/account', 'account')->name('account');
    Route::post('/upload-avatar', 'uploadAvatar')->name('upload.avatar');
});

Route::resource('channels', ChannelController::class);

