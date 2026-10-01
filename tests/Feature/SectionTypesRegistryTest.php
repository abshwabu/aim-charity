<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageSection;
use App\Support\SectionTypes;
use App\Support\SectionTypes\BaseSectionType;
use App\Support\Site;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SectionTypesRegistryTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_16_section_types_are_registered(): void
    {
        $types = SectionTypes::all();

        $expectedKeys = [
            'hero',
            'about',
            'member_groups',
            'programs',
            'impact_stats',
            'how_it_works',
            'testimonials',
            'gallery',
            'donate',
            'volunteer',
            'team',
            'partners',
            'news',
            'faq',
            'contact',
            'cta_banner',
        ];

        $this->assertCount(16, $types);

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $types);
            $this->assertTrue(SectionTypes::has($key));

            $class = $types[$key];
            $this->assertTrue(is_subclass_of($class, BaseSectionType::class));
            $this->assertSame($key, $class::getKey());
            $this->assertNotEmpty($class::getLabel());
            $this->assertNotEmpty($class::getIcon());
            $this->assertIsArray($class::getDefaultContent());
            $this->assertIsArray($class::formSchema());
        }
    }

    public function test_options_returns_keyed_labels_for_forms(): void
    {
        $options = SectionTypes::options();

        $this->assertCount(16, $options);
        $this->assertSame('Hero (Main Introduction)', $options['hero']);
        $this->assertSame('Donate & Financial Contributions', $options['donate']);
        $this->assertSame('Call-to-Action Banner (Highlight Strip)', $options['cta_banner']);
    }

    public function test_find_and_default_content_helpers(): void
    {
        $this->assertNull(SectionTypes::find('unknown_type'));
        $this->assertSame([], SectionTypes::defaultContent('unknown_type'));

        $heroContent = SectionTypes::defaultContent('hero');
        $this->assertArrayHasKey('headline', $heroContent);
        $this->assertArrayHasKey('buttons', $heroContent);

        $volunteerContent = SectionTypes::defaultContent('volunteer');
        $this->assertArrayHasKey('labels', $volunteerContent);
        $this->assertArrayHasKey('button_label', $volunteerContent);
    }

    public function test_default_page_section_seeder_persists_all_16_sections_in_order(): void
    {
        $this->seed(PageSectionSeeder::class);

        $sections = PageSection::query()->ordered()->get();

        $this->assertCount(16, $sections);

        $expectedKeysInOrder = [
            'hero',
            'about',
            'member_groups',
            'programs',
            'impact_stats',
            'how_it_works',
            'testimonials',
            'gallery',
            'donate',
            'volunteer',
            'team',
            'partners',
            'news',
            'faq',
            'contact',
            'cta_banner',
        ];

        $this->assertSame($expectedKeysInOrder, $sections->pluck('key')->all());

        foreach ($sections as $index => $section) {
            $this->assertSame($index + 1, $section->sort_order);
            $this->assertTrue($section->is_visible);
            $this->assertNotEmpty($section->type);
            $this->assertIsArray($section->content);
            $this->assertIsArray($section->style);
        }

        // Test that Site::sections() resolves the seeded collection from cache
        $cachedSections = Site::sections();
        $this->assertCount(16, $cachedSections);
        $this->assertTrue($cachedSections->has('hero'));
        $this->assertTrue($cachedSections->has('donate'));
    }
}
