<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\PageSection;
use App\Models\Program;
use App\Support\Site;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\ItemSeeder;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LandingSectionsRenderTest extends TestCase
{
    use RefreshDatabase;

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
    }

    public function test_landing_page_renders_all_seeded_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // 1. Hero
        $response->assertSee('Started by Friends. Sustained by Weekly Kindness.');
        $response->assertSee('25');

        // 2. About
        $response->assertSee('Our Story');
        $response->assertSee('How 25 Friends Started a Small-Town Association');
        $response->assertSee('Complete Openness');

        // 3. Our Group
        $response->assertSee('One Group of 25 Friends');
        $response->assertSee('Aim Charity (25 Member Friends)');

        // 4. Programs
        $response->assertSee('Food Support for Local Families & Elders');
        $response->assertSee('Clothing, Blankets & Warmth Support');

        // 4. Impact Stats
        $response->assertSee('Member Friends in Our Group');
        $response->assertSee('Town Families Supported with Food & Clothes');
        $response->assertSee('Direct to Neighbors (Zero Overhead)');

        // 5. How It Works
        $response->assertSee('25 Friends Pool Weekly Donations');
        $response->assertSee('Personal Neighbor Visits');
        $response->assertSee('In-Person Handover with Open Receipts');

        // 6. Testimonials
        $response->assertSee('Emaye Almaz Gebre');
        $response->assertSee('Teacher Solomon Haile');

        // 7. Gallery
        $response->assertSee('Volunteers packing weekly food care baskets at the town community center.');

        // 8. Donate
        $response->assertSee('Commercial Bank of Ethiopia (CBE)');
        $response->assertSee('1000-0000-0000-0000');
        $response->assertSee('Telebirr Mobile Money');

        // 9. Volunteer
        $response->assertSee('Full Name');
        $response->assertSee('Submit Volunteer Application');

        // 10. Contact
        $response->assertSee('Your Full Name');
        $response->assertSee('Send Message');

        // 11. Partners
        $response->assertSee('Town Community Elders Council');
        $response->assertSee('Local Kebele Health Post');

        // 12. News
        $response->assertSee('Delivered Weekly Food Baskets to 42 Town Elder Households');

        // 13. FAQ
        $response->assertSee('What is Aim Charity and how did it start?');

        // 14. Team
        $response->assertSee('Dawit Mekonnen');
        $response->assertSee('Founding Friend & Food Logistics');

        // 15. CTA Banner
        $response->assertSee('Small Town, Big Heart. Stand With Our Neighbors.');
        $response->assertSee('Start Weekly Giving');
    }

    public function test_hiding_a_section_in_admin_removes_it_from_the_page(): void
    {
        // Programs are visible initially
        $response = $this->get('/');
        $response->assertSee('Food Support for Local Families & Elders');

        // Hide programs section in DB and flush cache (simulating Filament save)
        PageSection::query()->where('key', 'programs')->update(['is_visible' => false]);
        Site::flushCache();

        $responseAfter = $this->get('/');
        $responseAfter->assertDontSee('id="programs"', false);
    }

    public function test_section_with_no_visible_items_renders_nothing_empty_state(): void
    {
        // When all programs are hidden, the programs section renders zero markup
        Program::query()->update(['is_visible' => false]);
        Site::flushCache();

        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('id="programs"', false);
        $response->assertDontSee('Food Support for Local Families & Elders');
    }

    public function test_news_detail_route_renders_post_by_slug(): void
    {
        $post = NewsPost::query()->visible()->firstOrFail();

        $response = $this->get(route('news.show', $post->slug));

        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->excerpt);
        $response->assertSee('teff flour, cooking oil, lentils', false);
    }

    public function test_news_detail_route_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/news/non-existent-report-slug-12345');

        $response->assertNotFound();
    }

    public function test_strict_text_scanner_enforces_zero_literal_text_nodes_in_landing_views_and_layouts(): void
    {
        $files = array_unique(array_merge(
            glob(resource_path('views/landing/**/*.blade.php')),
            glob(resource_path('views/layouts/**/*.blade.php')),
            glob(resource_path('views/landing/*.blade.php'))
        ));

        $this->assertNotEmpty($files, 'No Blade template files found to scan.');

        // Allowlist strictly limited to screen-reader ARIA-only strings and standard HTML entities
        $allowlist = [
            'Skip to main content',
            'Footer',
            '&times;',
            '&rarr;',
            '&larr;',
            '&bull;',
            '&copy;',
            '&quot;',
            '&amp;',
        ];

        $violations = [];

        foreach ($files as $file) {
            $content = (string) file_get_contents($file);
            $compiled = Blade::compileString($content);
            $tokens = token_get_all($compiled);

            $inlineHtml = '';
            foreach ($tokens as $token) {
                if (is_array($token) && $token[0] === T_INLINE_HTML) {
                    $inlineHtml .= $token[1];
                }
            }

            // Strip HTML comments, style, script, and SVG blocks
            $cleaned = (string) preg_replace('/<!--.*?-->/s', '', $inlineHtml);
            $cleaned = (string) preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $cleaned);
            $cleaned = (string) preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $cleaned);
            $cleaned = (string) preg_replace('/<svg\b[^>]*>.*?<\/svg>/is', '', $cleaned);

            // Strip HTML tags with attribute quotation awareness
            $tagRegex = '/<(?:\/?[a-zA-Z0-9:_\-]+(?:\s+[^"\'\s=>]+(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+))?)*\s*\/?>|\/?[a-zA-Z0-9:_\-]+\s*\/?>)/';
            $cleanedWithoutTags = (string) preg_replace($tagRegex, "\n", $cleaned);

            $lines = explode("\n", $cleanedWithoutTags);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed !== '' && ! in_array($trimmed, $allowlist, true)) {
                    $violations[basename($file)][] = $trimmed;
                }
            }
        }

        $this->assertEmpty(
            $violations,
            'Literal text nodes found outside Blade echoes/directives: '.json_encode($violations, JSON_PRETTY_PRINT)
        );
    }
}
