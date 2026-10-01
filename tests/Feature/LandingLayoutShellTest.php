<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Support\ColorHelper;
use App\Support\HtmlSanitizer;
use App\Support\Site;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LandingLayoutShellTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminUserSeeder::class);
        $this->seed(SiteSettingSeeder::class);
        $this->seed(PageSectionSeeder::class);
    }

    public function test_landing_page_renders_with_accessibility_landmarks_and_seo(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        // 1. Accessibility skip link
        $response->assertSee('Skip to main content');
        $response->assertSee('href="#main-content"', false);

        // 2. Semantic landmarks
        $response->assertSee('<header', false);
        $response->assertSee('<main id="main-content"', false);
        $response->assertSee('<footer', false);
        $response->assertSee('aria-label="Main Navigation"', false);
        $response->assertSee('aria-label="Mobile Navigation"', false);

        // 3. Head & SEO meta tags
        $response->assertSee('<meta name="viewport"', false);
        $response->assertSee('<link rel="canonical"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('name="twitter:card"', false);

        // 4. Dynamic Theme CSS variables
        $response->assertSee('--color-primary:', false);
        $response->assertSee('--color-accent:', false);
        $response->assertSee('--color-background:', false);
        $response->assertSee('--radius:', false);
        $response->assertSee('--font-heading:', false);
        $response->assertSee('--font-body:', false);
    }

    public function test_changing_theme_settings_visibly_rethemes_layout(): void
    {
        // Change colors, fonts, and radius in database
        $settings = SiteSetting::current();
        $settings->update([
            'theme' => [
                'primary' => '#004d40',
                'secondary' => '#00796b',
                'accent' => '#ff6f00',
                'background' => '#fefae0',
                'surface' => '#ffffff',
                'text' => '#283618',
                'heading_font' => 'Merriweather',
                'body_font' => 'Inter',
                'radius_style' => 'sharp',
            ],
            'branding' => array_merge($settings->branding ?? [], [
                'site_name' => 'Custom Coalition Name',
            ]),
        ]);

        Site::flushCache();

        $response = $this->get('/');
        $response->assertOk();

        // Verify newly configured colors and fonts appear in <style>
        $response->assertSee('--color-primary: #004d40;', false);
        $response->assertSee('--color-accent: #ff6f00;', false);
        $response->assertSee('--color-background: #fefae0;', false);
        $response->assertSee('--radius: 0px;', false);
        $response->assertSee("'Merriweather'", false);
        $response->assertSee("'Inter'", false);

        // Verify updated brand name
        $response->assertSee('Custom Coalition Name');
    }

    public function test_color_helper_contrast_and_luminance_calculation(): void
    {
        // Dark colors (luminance < 0.45) should require light text
        $this->assertTrue(ColorHelper::isDark('#1b4332')); // deep green
        $this->assertTrue(ColorHelper::isDark('#0f172a')); // dark slate
        $this->assertSame('#ffffff', ColorHelper::contrastTextColor('#1b4332'));

        // Light colors (luminance >= 0.45) should require dark text
        $this->assertFalse(ColorHelper::isDark('#fbf9f5')); // warm cream
        $this->assertFalse(ColorHelper::isDark('#ffffff')); // pure white
        $this->assertSame('#1c1917', ColorHelper::contrastTextColor('#fbf9f5'));

        // Radius mapping
        $this->assertSame('0px', ColorHelper::radiusValue('sharp'));
        $this->assertSame('9999px', ColorHelper::radiusValue('pill'));
        $this->assertSame('1rem', ColorHelper::radiusValue('rounded-2xl'));

        // Google Fonts URL generator
        $url = ColorHelper::googleFontsUrl('Fraunces', 'Plus Jakarta Sans');
        $this->assertNotNull($url);
        $this->assertStringContainsString('family=Fraunces', $url);
        $this->assertStringContainsString('family=Plus+Jakarta+Sans', $url);
    }

    public function test_button_component_renders_accessible_markup(): void
    {
        // Render as link
        $linkHtml = Blade::render('<x-button href="https://example.com" variant="primary">Donate Now</x-button>');
        $this->assertStringContainsString('<a', $linkHtml);
        $this->assertStringContainsString('href="https://example.com"', $linkHtml);
        $this->assertStringContainsString('Donate Now', $linkHtml);
        $this->assertStringContainsString('bg-primary', $linkHtml);

        // Render as button
        $buttonHtml = Blade::render('<x-button type="submit" variant="outline">Submit Form</x-button>');
        $this->assertStringContainsString('<button', $buttonHtml);
        $this->assertStringContainsString('type="submit"', $buttonHtml);
        $this->assertStringContainsString('border-primary', $buttonHtml);
    }

    public function test_heading_block_component_is_null_safe_and_renders_hierarchy(): void
    {
        // Full heading block
        $html = Blade::render('<x-heading-block eyebrow="Our Mission" heading="Building Sustainable Communities" subheading="Detailed explanation here" />');
        $this->assertStringContainsString('Our Mission', $html);
        $this->assertStringContainsString('Building Sustainable Communities', $html);
        $this->assertStringContainsString('Detailed explanation here', $html);
        $this->assertStringContainsString('<h2', $html);

        // Null eyebrow should not render empty badge
        $partialHtml = Blade::render('<x-heading-block heading="Standalone Heading" />');
        $this->assertStringContainsString('Standalone Heading', $partialHtml);
        $this->assertStringNotContainsString('uppercase', $partialHtml);

        // Empty heading block renders nothing
        $emptyHtml = Blade::render('<x-heading-block />');
        $this->assertEmpty(trim($emptyHtml));
    }

    public function test_image_component_is_null_safe_and_adds_lazy_loading(): void
    {
        // Null image renders nothing
        $nullHtml = Blade::render('<x-image :src="null" />');
        $this->assertEmpty(trim($nullHtml));

        // Filled image renders <img> with lazy loading
        $imageHtml = Blade::render('<x-image src="https://images.unsplash.com/photo-1" alt="Community members" aspect="video" />');
        $this->assertStringContainsString('<img', $imageHtml);
        $this->assertStringContainsString('src="https://images.unsplash.com/photo-1"', $imageHtml);
        $this->assertStringContainsString('alt="Community members"', $imageHtml);
        $this->assertStringContainsString('loading="lazy"', $imageHtml);
        $this->assertStringContainsString('decoding="async"', $imageHtml);
        $this->assertStringContainsString('aspect-video', $imageHtml);
    }

    public function test_rich_text_component_sanitizes_malicious_content(): void
    {
        // Safe content preserves allowed tags
        $safeHtml = Blade::render('<x-rich-text content="<p>Welcome to <strong>Aim Charity</strong>.</p>" />');
        $this->assertStringContainsString('<p>Welcome to <strong>Aim Charity</strong>.</p>', $safeHtml);

        // Malicious script tags are completely stripped
        $unsafeInput = '<p>Click here</p><script>alert("hacked")</script><a href="javascript:alert(1)" onclick="steal()">Link</a>';
        $cleaned = HtmlSanitizer::clean($unsafeInput);
        $this->assertStringNotContainsString('<script', $cleaned);
        $this->assertStringNotContainsString('onclick', $cleaned);
        $this->assertStringNotContainsString('javascript:', $cleaned);
        $this->assertStringContainsString('<p>Click here</p>', $cleaned);

        // Empty rich text renders nothing
        $emptyRichText = Blade::render('<x-rich-text content="" />');
        $this->assertEmpty(trim($emptyRichText));
    }

    public function test_icon_component_renders_heroicons_and_images(): void
    {
        // Heroicon renders svg
        $heroiconHtml = Blade::render('<x-icon name="heroicon-o-heart" class="w-6 h-6" />');
        $this->assertStringContainsString('<svg', $heroiconHtml);

        // Image path renders img
        $imageIconHtml = Blade::render('<x-icon name="https://example.com/logo.png" class="w-8 h-8" />');
        $this->assertStringContainsString('<img', $imageIconHtml);
        $this->assertStringContainsString('src="https://example.com/logo.png"', $imageIconHtml);

        // Null name renders nothing
        $nullIcon = Blade::render('<x-icon :name="null" />');
        $this->assertEmpty(trim($nullIcon));
    }

    public function test_section_wrapper_component_applies_style_and_contrast(): void
    {
        // Section with dark background should output dark theme classes
        $darkSectionHtml = Blade::render('<x-section-wrapper anchor="test-dark" background-color="#1b4332" padding-size="L">Dark Section Content</x-section-wrapper>');
        $this->assertStringContainsString('id="test-dark"', $darkSectionHtml);
        $this->assertStringContainsString('background-color: #1b4332', $darkSectionHtml);
        $this->assertStringContainsString('text-white', $darkSectionHtml);
        $this->assertStringContainsString('py-20 md:py-32', $darkSectionHtml);

        // Section with light background should output light theme classes
        $lightSectionHtml = Blade::render('<x-section-wrapper anchor="test-light" background-color="#ffffff">Light Section Content</x-section-wrapper>');
        $this->assertStringContainsString('id="test-light"', $lightSectionHtml);
        $this->assertStringContainsString('text-text', $lightSectionHtml);
    }

    public function test_footer_renders_copyright_with_current_year_replacement(): void
    {
        $currentYear = (string) date('Y');

        $response = $this->get('/');
        $response->assertOk();

        // Footer copyright should contain current year
        $response->assertSee($currentYear);
        $response->assertSee('All rights reserved');
    }
}
