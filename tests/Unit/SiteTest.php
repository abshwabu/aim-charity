<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\MemberGroup;
use App\Models\PageSection;
use App\Models\SiteSetting;
use App\Support\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_section_returns_null_safe_defaults_when_missing(): void
    {
        $section = Site::section('non_existent_key');

        $this->assertInstanceOf(PageSection::class, $section);
        $this->assertSame('non_existent_key', $section->key);
        $this->assertFalse($section->is_visible);
        $this->assertIsArray($section->content);
        $this->assertArrayHasKey('heading', $section->content);
        $this->assertArrayHasKey('buttons', $section->content);
        $this->assertIsArray($section->style);
        $this->assertArrayHasKey('text_theme', $section->style);
    }

    public function test_site_settings_returns_cached_singleton(): void
    {
        Cache::flush();

        $setting = SiteSetting::factory()->create([
            'branding' => ['site_name' => 'Custom Name'],
        ]);

        $first = Site::settings();
        $this->assertSame('Custom Name', $first->branding['site_name']);

        // Directly update DB without triggering Eloquent events to verify cache holds
        SiteSetting::query()->where('id', $setting->id)->update([
            'branding' => json_encode(['site_name' => 'Direct DB Update']),
        ]);

        $cached = Site::settings();
        $this->assertSame('Custom Name', $cached->branding['site_name']);

        // Now flush cache and verify new data is fetched
        Site::flushCache();
        $fresh = Site::settings();
        $this->assertSame('Direct DB Update', $fresh->branding['site_name']);
    }

    public function test_site_cache_flushes_automatically_when_model_saved(): void
    {
        $setting = SiteSetting::factory()->create([
            'branding' => ['site_name' => 'Initial Name'],
        ]);

        $this->assertSame('Initial Name', Site::settings()->branding['site_name']);
        $this->assertTrue(Cache::has(Site::CACHE_SETTINGS_KEY));

        $setting->update([
            'branding' => ['site_name' => 'Updated Name'],
        ]);

        $this->assertFalse(Cache::has(Site::CACHE_SETTINGS_KEY));
        $this->assertSame('Updated Name', Site::settings()->branding['site_name']);
    }

    public function test_site_cache_flushes_automatically_when_repeatable_model_saved_or_deleted(): void
    {
        Site::sections();
        $this->assertTrue(Cache::has(Site::CACHE_SECTIONS_KEY));

        $group = MemberGroup::factory()->create();

        $this->assertFalse(Cache::has(Site::CACHE_SECTIONS_KEY));

        Site::sections();
        $this->assertTrue(Cache::has(Site::CACHE_SECTIONS_KEY));

        $group->delete();

        $this->assertFalse(Cache::has(Site::CACHE_SECTIONS_KEY));
    }

    public function test_site_sections_returns_only_visible_and_ordered_sections(): void
    {
        PageSection::factory()->create([
            'key' => 'second',
            'sort_order' => 20,
            'is_visible' => true,
        ]);

        PageSection::factory()->create([
            'key' => 'hidden',
            'sort_order' => 5,
            'is_visible' => false,
        ]);

        PageSection::factory()->create([
            'key' => 'first',
            'sort_order' => 10,
            'is_visible' => true,
        ]);

        $sections = Site::sections();

        $this->assertCount(2, $sections);
        $this->assertTrue($sections->has('first'));
        $this->assertTrue($sections->has('second'));
        $this->assertFalse($sections->has('hidden'));

        $this->assertSame(['first', 'second'], $sections->keys()->all());
    }

    public function test_image_url_helper_returns_null_safe_urls(): void
    {
        Storage::fake('public');

        // Blank or null
        $this->assertNull(Site::imageUrl(null));
        $this->assertNull(Site::imageUrl(''));
        $this->assertSame('fallback.jpg', Site::imageUrl(null, 'fallback.jpg'));
        $this->assertSame('fallback.jpg', imageUrl('', 'fallback.jpg'));

        // Absolute URLs
        $this->assertSame('https://example.com/photo.jpg', Site::imageUrl('https://example.com/photo.jpg'));
        $this->assertSame('http://example.com/photo.jpg', imageUrl('http://example.com/photo.jpg'));
        $this->assertSame('data:image/png;base64,abc', Site::imageUrl('data:image/png;base64,abc'));

        // Relative path on public storage disk
        $relativeUrl = Site::imageUrl('media/sample.webp');
        $this->assertStringContainsString('storage/media/sample.webp', (string) $relativeUrl);

        $globalHelperUrl = imageUrl('media/sample.webp');
        $this->assertSame($relativeUrl, $globalHelperUrl);
    }
}
