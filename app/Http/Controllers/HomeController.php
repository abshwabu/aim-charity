<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NewsPost;
use App\Support\Site;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the Aim Charity coalition landing page.
     */
    public function index(): View
    {
        $settings = Site::settings();
        $sections = Site::sections();
        $items = Site::items();

        return view('landing.index', [
            'settings' => $settings,
            'sections' => $sections,
            'items' => $items,
        ]);
    }

    /**
     * Display an individual news post / report by slug.
     */
    public function showNews(string $slug): View
    {
        $post = NewsPost::query()
            ->visible()
            ->where('slug', $slug)
            ->firstOrFail();

        $settings = Site::settings();

        return view('news.show', [
            'post' => $post,
            'settings' => $settings,
        ]);
    }
}
