<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'videos'])->name('videos');
Route::get('/articles', [HomeController::class, 'articles'])->name('articles');
