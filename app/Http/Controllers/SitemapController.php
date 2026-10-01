<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.xml containing home and all published news posts.
     */
    public function index(): Response
    {
        $posts = NewsPost::query()
            ->visible()
            ->latest('published_at')
            ->get();

        $content = view('seo.sitemap', [
            'posts' => $posts,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600, stale-while-revalidate=7200',
        ]);
    }

    /**
     * Return dynamic robots.txt pointing to the sitemap.
     */
    public function robots(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = "User-agent: *\n"
            ."Disallow: /admin/\n"
            ."Allow: /\n\n"
            ."Sitemap: {$sitemapUrl}\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
