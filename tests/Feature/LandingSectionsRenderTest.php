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
        $response->assertSee('120+');

        // 2. About
        $response->assertSee('Our Story');
        $response->assertSee('How a Group of Friends Started a Town Association');
        $response->assertSee('Complete Openness');

        // 3. Member Groups
        $response->assertSee('Founding Friends Circle');
        $response->assertSee('Town Youth Volunteers');
        $response->assertSee('Neighborhood Mothers & Elders Circle');

        // 4. Programs
        $response->assertSee('Weekly Elder Care & Food Baskets');
        $response->assertSee('Back-to-School Supplies for Town Kids');

        // 5. Impact Stats
        $response->assertSee('Contributing Friends & Neighbors');
        $response->assertSee('Direct to Neighbors (Zero Overhead)');

        // 6. How It Works
        $response->assertSee('Friends Pool Weekly Donations');
        $response->assertSee('Personal Neighbor Visits');
        $response->assertSee('Handover with Open Books');

        // 7. Testimonials
        $response->assertSee('Emaye Almaz Gebre');
        $response->assertSee('Teacher Solomon Haile');

        // 8. Gallery
        $response->assertSee('Volunteers packing weekly food care baskets at the town community center.');

        // 9. Donate
        $response->assertSee('Commercial Bank of Ethiopia (CBE)');
        $response->assertSee('1000-0000-0000-0000');
        $response->assertSee('Telebirr Mobile Money');

        // 10. Volunteer
        $response->assertSee('Full Name');
        $response->assertSee('Submit Volunteer Application');

        // 11. Contact
        $response->assertSee('Your Full Name');
        $response->assertSee('Send Message');

        // 12. Partners
        $response->assertSee('Town Community Elders Council');
        $response->assertSee('Local Kebele Health Post');

        // 13. News
        $response->assertSee('Celebrating Our 50th Consecutive Weekly Food Basket Delivery');

        // 14. FAQ
        $response->assertSee('What is Aim Charity and how did it start?');

        // 15. Team
        $response->assertSee('Dawit Mekonnen');
        $response->assertSee('Founding Friend & Coordinator');

        // 16. CTA Banner
        $response->assertSee('Small Town, Big Heart. Stand With Our Neighbors.');
        $response->assertSee('Start Weekly Giving');
    }

    public function test_hiding_a_section_in_admin_removes_it_from_the_page(): void
    {
        // Programs are visible initially
        $response = $this->get('/');
        $response->assertSee('Weekly Elder Care & Food Baskets');

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
        $response->assertDontSee('Weekly Elder Care & Food Baskets');
    }

    public function test_news_detail_route_renders_post_by_slug(): void
    {
        $post = NewsPost::query()->visible()->firstOrFail();

        $response = $this->get(route('news.show', $post->slug));

        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->excerpt);
        $response->assertSee('consecutive weeks of food basket deliveries', false);
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
