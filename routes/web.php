<?php

declare(strict_types=1);

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news/{slug}', [HomeController::class, 'showNews'])->name('news.show');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::middleware('throttle:10,1')->group(function (): void {
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');
    Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
});
