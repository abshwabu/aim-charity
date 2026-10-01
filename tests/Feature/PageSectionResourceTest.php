<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\PageSections\Pages\CreatePageSection;
use App\Filament\Resources\PageSections\Pages\EditPageSection;
use App\Filament\Resources\PageSections\Pages\ListPageSections;
use App\Filament\Resources\PageSections\PageSectionResource;
use App\Models\PageSection;
use App\Models\User;
use App\Support\Site;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageSectionResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminUserSeeder::class);
        $this->seed(PageSectionSeeder::class);
        $this->admin = User::query()->firstOrFail();
    }

    public function test_guests_cannot_access_page_sections(): void
    {
        $response = $this->get('/admin/page-sections');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_page_sections_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/page-sections');

        $response->assertOk();
        $response->assertSee('Page Sections');
        $response->assertSee('Hero (Main Introduction)');
        $response->assertSee('Preview Landing Page');
    }

    public function test_authenticated_admin_can_view_edit_page_section(): void
    {
        $this->actingAs($this->admin);

        $hero = PageSection::query()->where('key', 'hero')->firstOrFail();

        $response = $this->get("/admin/page-sections/{$hero->id}/edit");

        $response->assertOk();
        $response->assertSee('General');
        $response->assertSee('Content');
        $response->assertSee('Style');
    }

    public function test_admin_can_reorder_sections_and_persist_order(): void
    {
        $this->actingAs($this->admin);

        $hero = PageSection::query()->where('key', 'hero')->firstOrFail();
        $about = PageSection::query()->where('key', 'about')->firstOrFail();

        $this->assertSame(1, $hero->sort_order);
        $this->assertSame(2, $about->sort_order);

        // Pre-warm the cache
        $this->assertSame('hero', Site::sections()->first()->key);

        // Call reorderTable with swapped keys (using string record keys)
        Livewire::test(ListPageSections::class)
            ->call('reorderTable', [(string) $about->id, (string) $hero->id]);

        $hero->refresh();
        $about->refresh();

        $this->assertSame(1, $about->sort_order);
        $this->assertSame(2, $hero->sort_order);

        // Assert cache was flushed and refreshed with new order
        $firstSectionInCache = Site::sections()->first();
        $this->assertSame('about', $firstSectionInCache->key);
    }

    public function test_admin_can_edit_section_fields_and_flush_cache(): void
    {
        $this->actingAs($this->admin);

        $hero = PageSection::query()->where('key', 'hero')->firstOrFail();

        Livewire::test(EditPageSection::class, ['record' => $hero->id])
            ->fillForm([
                'content.heading' => 'Updated Hero Headline for Aim Charity',
                'content.eyebrow' => 'Updated Eyebrow Tag',
                'style.text_theme' => 'dark',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $hero->refresh();

        $this->assertSame('Updated Hero Headline for Aim Charity', $hero->content['heading']);
        $this->assertSame('Updated Eyebrow Tag', $hero->content['eyebrow']);
        $this->assertSame('dark', $hero->style['text_theme']);

        // Assert Site::section() reflects changes
        $this->assertSame('Updated Hero Headline for Aim Charity', Site::section('hero')->content['heading']);
    }

    public function test_admin_can_toggle_section_visibility(): void
    {
        $this->actingAs($this->admin);

        $about = PageSection::query()->where('key', 'about')->firstOrFail();
        $this->assertTrue($about->is_visible);

        // Warm cache: about should be present in visible sections
        $this->assertTrue(Site::sections()->has('about'));

        $about->update(['is_visible' => false]);

        // FlushesSiteCache should have forgotten the cache
        $this->assertFalse(Site::sections()->has('about'));
    }

    public function test_core_sections_cannot_be_deleted_while_custom_can(): void
    {
        $hero = PageSection::query()->where('key', 'hero')->firstOrFail();
        $custom = PageSection::query()->create([
            'key' => 'custom-initiative',
            'type' => 'programs',
            'sort_order' => 99,
            'is_visible' => true,
            'content' => [],
            'style' => [],
        ]);

        $this->assertFalse(PageSectionResource::canDelete($hero));
        $this->assertTrue(PageSectionResource::canDelete($custom));
    }

    public function test_admin_can_create_new_section_of_registered_type(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreatePageSection::class)
            ->fillForm([
                'key' => 'emergency-response',
                'type' => 'programs',
                'nav_label' => 'Emergency Response',
                'anchor' => 'emergency',
                'is_visible' => true,
                'content.heading' => 'Emergency Disaster Relief',
                'content.subheading' => 'Rapid response across vulnerable regional zones.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        /** @var PageSection $created */
        $created = PageSection::query()->where('key', 'emergency-response')->firstOrFail();

        $this->assertSame('programs', $created->type);
        $this->assertSame('Emergency Disaster Relief', $created->content['heading']);
        $this->assertNotNull($created->sort_order);
    }

    public function test_admin_can_duplicate_section_via_replicate_action(): void
    {
        $this->actingAs($this->admin);

        $programs = PageSection::query()->where('key', 'programs')->firstOrFail();

        Livewire::test(ListPageSections::class)
            ->callTableAction('replicate', $programs);

        /** @var PageSection|null $duplicate */
        $duplicate = PageSection::query()->where('key', 'programs-2')->first();

        $this->assertNotNull($duplicate);
        $this->assertSame('programs', $duplicate->type);
        $this->assertStringContainsString('(Copy)', (string) $duplicate->nav_label);
        $this->assertGreaterThan($programs->sort_order, $duplicate->sort_order);
    }
}
