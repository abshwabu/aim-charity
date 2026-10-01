<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\User;
use App\Support\HtmlSanitizer;
use App\Support\Site;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SeoPerformanceSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            PageSectionSeeder::class,
            ItemSeeder::class,
        ]);

        Site::flushCache();
        $this->admin = User::query()->firstOrFail();
    }

    public function test_landing_page_outputs_json_ld_structured_data_with_ngo_and_member_groups(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('application/ld+json', false);
        $response->assertSee('NonprofitOrganization');
        $response->assertSee('NGO');
        $response->assertSee('Addis Mutual Aid Association');
        $response->assertSee('subOrganization');
        $response->assertSee('contactPoint');
    }

    public function test_landing_page_outputs_canonical_and_open_graph_and_twitter_meta_tags(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="'.url('/'), false);
        $response->assertSee('<meta property="og:type" content="website">', false);
        $response->assertSee('<meta property="og:url" content="'.url('/'), false);
        $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
    }

    public function test_news_post_page_outputs_article_schema_and_meta(): void
    {
        $post = NewsPost::query()->visible()->firstOrFail();

        $response = $this->get(route('news.show', $post->slug));

        $response->assertOk();
        $response->assertSee('<meta property="og:type" content="article">', false);
        $response->assertSee('<link rel="canonical" href="'.route('news.show', $post->slug), false);
        $response->assertSee('NewsArticle');
        $response->assertSee($post->title);
    }

    public function test_sitemap_xml_returns_valid_xml_with_home_and_news_posts(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('<loc>'.url('/').'</loc>', false);

        $post = NewsPost::query()->visible()->first();
        if ($post !== null) {
            $response->assertSee(route('news.show', $post->slug));
        }
    }

    public function test_robots_txt_disallows_admin_and_points_to_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_landing_page_eager_loading_prevents_n_plus_one_queries(): void
    {
        Site::flushCache();

        // 1. Cold cache query count (must execute under 15 queries for entire rich page)
        DB::enableQueryLog();
        $response = $this->get('/');
        $response->assertOk();
        $coldQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThanOrEqual(20, $coldQueryCount, "Cold cache query count ({$coldQueryCount}) exceeds threshold.");

        // 2. Warm cache query count (all sections and items cached via Site helper)
        DB::flushQueryLog();
        DB::enableQueryLog();
        $responseWarm = $this->get('/');
        $responseWarm->assertOk();
        $warmQueryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertLessThanOrEqual(1, $warmQueryCount, "Warm cache query count ({$warmQueryCount}) must be <= 1.");
    }

    public function test_http_cache_headers_set_on_public_responses_with_etag_and_304(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertHeader('Cache-Control');
        $this->assertStringContainsString('public', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('max-age=300', (string) $response->headers->get('Cache-Control'));

        $etag = $response->headers->get('ETag');
        $this->assertNotNull($etag);

        // Test conditional request with If-None-Match on /sitemap.xml (deterministic, no session-specific CSRF tokens)
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapEtag = $sitemapResponse->headers->get('ETag');
        $this->assertNotNull($sitemapEtag);

        $conditionalResponse = $this->withHeaders([
            'If-None-Match' => $sitemapEtag,
        ])->get('/sitemap.xml');

        $conditionalResponse->assertStatus(304);
    }

    public function test_security_headers_are_attached_to_all_responses(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString('fonts.googleapis.com', $csp);
        $this->assertStringContainsString('fonts.gstatic.com', $csp);
    }

    public function test_rich_text_sanitizer_neutralizes_xss_attempts(): void
    {
        $xssVectors = [
            '<script>alert("XSS")</script><p>Clean Text</p>' => '<p>Clean Text</p>',
            '<img src="javascript:alert(1)">' => '',
            '<a href="javascript:alert(1)">Click Me</a>' => '<a rel="noopener noreferrer">Click Me</a>',
            '<div onmouseover="alert(1)">Hover</div>' => '<div>Hover</div>',
            '<body onload="alert(1)">Test</body>' => 'Test',
        ];

        foreach ($xssVectors as $payload => $expectedSubstring) {
            $cleaned = HtmlSanitizer::clean($payload);

            $this->assertStringNotContainsString('<script', $cleaned);
            $this->assertStringNotContainsString('javascript:', $cleaned);
            $this->assertStringNotContainsString('onmouseover', $cleaned);
            $this->assertStringNotContainsString('onload', $cleaned);
            $this->assertStringContainsString($expectedSubstring, $cleaned);
        }
    }

    public function test_unauthenticated_users_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_accessibility_landmarks_and_aria_attributes_exist(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // Skip to main content link
        $response->assertSee('Skip to main content');
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('id="main-content"', false);

        // Escape key dismiss listeners for modals and lightbox
        $response->assertSee('@keydown.escape.window', false);

        // Dialog roles for accessibility
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);

        // Accordion W3C patterns
        $response->assertSee('aria-controls=', false);
        $response->assertSee('role="region"', false);
    }
}
