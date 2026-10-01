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
        $response->assertSee('When Communities Unite, Hope Becomes Real');
        $response->assertSee('142K+');

        // 2. About
        $response->assertSee('Our Coalition Story');
        $response->assertSee('Many Groups, One Unbroken Circle');
        $response->assertSee('Radical Transparency');

        // 3. Member Groups
        $response->assertSee('Addis Mutual Aid Association');
        $response->assertSee('Oromia Community Elders Committee');
        $response->assertSee('Amhara Health & Reconstruction Taskforce');

        // 4. Programs
        $response->assertSee('Emergency Nutritional Relief & Grain Reserves');
        $response->assertSee('Community Clean Water Wells & Boreholes');

        // 5. Impact Stats
        $response->assertSee('Individuals Provided Direct Emergency Relief');
        $response->assertSee('Direct Grassroots Allocation & Open Auditing');

        // 6. How It Works
        $response->assertSee('Grassroots Need Verification');
        $response->assertSee('Coalition Resource Pooling');
        $response->assertSee('Dignified Direct Distribution');

        // 7. Testimonials
        $response->assertSee('W/ro Aster Tesfaye');
        $response->assertSee('Dr. Dawit Bekele');

        // 8. Gallery
        $response->assertSee('Volunteers loading sacks of grain for rural woreda distribution.');

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
        $response->assertSee('Ethiopian Red Cross Society');
        $response->assertSee('Addis Ababa University Community Service');

        // 13. News
        $response->assertSee('Emergency Grain Convoy Reaches 2,400 Families in North Wollo');

        // 14. FAQ
        $response->assertSee('What makes Aim Charity different from conventional charities?');

        // 15. Team
        $response->assertSee('Ato Yohannes Hailemariam');
        $response->assertSee('Steering Committee Chairperson');

        // 16. CTA Banner
        $response->assertSee('Take Action Today');
        $response->assertSee('Together, We Can Deliver Hope and Relief Across Ethiopia');
    }

    public function test_hiding_a_section_in_admin_removes_it_from_the_page(): void
    {
        // Programs are visible initially
        $response = $this->get('/');
        $response->assertSee('Emergency Nutritional Relief & Grain Reserves');

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
        $response->assertDontSee('Emergency Nutritional Relief & Grain Reserves');
    }

    public function test_news_detail_route_renders_post_by_slug(): void
    {
        $post = NewsPost::query()->visible()->firstOrFail();

        $response = $this->get(route('news.show', $post->slug));

        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->excerpt);
        $response->assertSee('verified family registries', false);
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
