<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CreationStudioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::post('uploadImage', [AttachmentController::class, 'uploadImage'])->name('uploadImage');

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
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
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

Route::controller(ContentController::class)->name('contents.')->prefix('contents')->group(function () {
    Route::post('/{content}/activate', 'activateSkills')->name('skills.activate');
    Route::post('/{content}/analyzeAgain', 'analyzeSkills')->name('skills.analyzeSkills');
});


// Subscription Service
Route::middleware(['auth'])->name('subscription.')->prefix('subscription')->group(function () {
    Route::post('/{channel}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/{channel}/unsubscribe', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
});

// Views Service
Route::middleware(['auth'])->name('views.')->prefix('views')->group(function () {
    Route::post('/{content}/view', [ViewController::class, 'view'])->name('view');
});

// Review Service
Route::middleware(['auth'])->name('review.')->prefix('review/{content}')->group(function () {
    Route::post('/', [ReviewController::class, 'store'])->name('store');
});

// Wallet Controller
Route::middleware(['auth'])->name('account.wallet.')->prefix('account/wallet')->group(function () {
    Route::get('/', [WalletController::class, 'index'])->name('index');
    Route::post('/deposit', [WalletController::class, 'deposit'])->name('deposit');
    Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
});
