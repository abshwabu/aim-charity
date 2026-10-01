<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{slug}', [HomeController::class, 'showNews'])->name('news.show');
