<?php

declare(strict_types=1);

use App\Support\Site;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $settings = Site::settings();
    $sections = Site::sections();
    $hero = Site::section('hero');

    return view('landing.index', [
        'settings' => $settings,
        'sections' => $sections,
        'hero' => $hero,
    ]);
});
