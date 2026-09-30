<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\SiteSettings;
use App\Models\PageSection;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\Site;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminUserSeeder::class);
        $this->admin = User::query()->firstOrFail();
    }

    public function test_guests_are_redirected_away_from_site_settings(): void
    {
        $response = $this->get('/admin/site-settings');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_site_settings_page(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/site-settings');

        $response->assertOk();
        $response->assertSee('Site Settings');
    }

    public function test_site_settings_page_loads_existing_state_from_database(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'branding' => [
                    'site_name' => 'Custom Aim Charity Name',
                    'tagline' => 'Uniting Grassroots in Ethiopia',
                ],
                'theme' => [
                    'primary' => '#059669',
                    'secondary' => '#0d9488',
                    'accent' => '#f59e0b',
                    'background' => '#f8fafc',
                    'surface' => '#ffffff',
                    'text' => '#0f172a',
                    'heading_font' => 'Instrument Sans',
                    'body_font' => 'Instrument Sans',
                    'corner_radius' => 'soft',
                    'radius_style' => 'rounded-xl',
                ],
                'contact' => [
                    'email' => 'info@aimcharity.org',
                ],
            ]
        );

        $this->actingAs($this->admin);

        Livewire::test(SiteSettings::class)
            ->assertFormSet([
                'branding.site_name' => 'Custom Aim Charity Name',
                'branding.tagline' => 'Uniting Grassroots in Ethiopia',
                'contact.email' => 'info@aimcharity.org',
            ]);
    }

    public function test_admin_can_save_site_settings_and_update_database(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'branding.site_name' => 'Aim Charity Ethiopia',
                'branding.tagline' => 'Grassroots Coalition for Impact',
                'theme.primary' => '#10b981',
                'theme.corner_radius' => 'pill',
                'contact.email' => 'contact@aimcharity.org',
                'contact.phone' => '+251 11 555 0199',
                'footer.copyright_text' => 'Aim Charity {year}. All rights reserved.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        /** @var SiteSetting $settings */
        $settings = SiteSetting::query()->firstOrFail();

        $this->assertSame('Aim Charity Ethiopia', $settings->branding['site_name']);
        $this->assertSame('Grassroots Coalition for Impact', $settings->branding['tagline']);
        $this->assertSame('#10b981', $settings->theme['primary']);
        $this->assertSame('pill', $settings->theme['corner_radius']);
        $this->assertSame('rounded-full', $settings->theme['radius_style']);
        $this->assertSame('contact@aimcharity.org', $settings->contact['email']);
        $this->assertSame('+251 11 555 0199', $settings->contact['phone']);
        $this->assertSame('Aim Charity {year}. All rights reserved.', $settings->footer['copyright_text']);
    }

    public function test_changing_primary_color_updates_site_settings_helper_immediately(): void
    {
        $this->actingAs($this->admin);

        // Pre-warm the cache
        $initialPrimary = Site::settings()->theme['primary'];
        $this->assertNotEmpty($initialPrimary);

        $newPrimary = '#e11d48';

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'theme.primary' => $newPrimary,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        // Must immediately reflect new value from helper without stale cache
        $this->assertSame($newPrimary, Site::settings()->theme['primary']);
    }

    public function test_validation_rejects_invalid_email(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'contact.email' => 'invalid-email-format',
            ])
            ->call('save')
            ->assertHasFormErrors(['contact.email']);
    }

    public function test_validation_rejects_invalid_url(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'contact.map_embed_url' => 'not-a-valid-url',
            ])
            ->call('save')
            ->assertHasFormErrors(['contact.map_embed_url']);
    }

    public function test_admin_can_save_social_links_and_navigation_items(): void
    {
        $this->actingAs($this->admin);

        PageSection::factory()->create([
            'key' => 'programs',
            'nav_label' => 'Our Programs',
        ]);

        Livewire::test(SiteSettings::class)
            ->fillForm([
                'social' => [
                    [
                        'platform' => 'telegram',
                        'url' => 'https://t.me/aimcharity',
                    ],
                    [
                        'platform' => 'facebook',
                        'url' => 'https://facebook.com/aimcharity',
                    ],
                ],
                'navigation.items' => [
                    [
                        'label' => 'Programs',
                        'type' => 'section',
                        'section_key' => 'programs',
                        'open_in_new_tab' => false,
                    ],
                ],
                'navigation.cta_label' => 'Donate Now',
                'navigation.cta_link_type' => 'section',
                'navigation.cta_section_key' => 'programs',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        /** @var SiteSetting $settings */
        $settings = SiteSetting::query()->firstOrFail();

        $this->assertCount(2, $settings->social);
        $this->assertSame('telegram', $settings->social[0]['platform']);
        $this->assertSame('https://t.me/aimcharity', $settings->social[0]['url']);

        $this->assertSame('Donate Now', $settings->navigation['cta_label']);
        $this->assertCount(1, $settings->navigation['items']);
        $this->assertSame('Programs', $settings->navigation['items'][0]['label']);
    }
}
