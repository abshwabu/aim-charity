<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branding' => [
                'site_name' => fake()->company(),
                'tagline' => fake()->catchPhrase(),
                'logo_light' => 'branding/logo-light.webp',
                'logo_dark' => 'branding/logo-dark.webp',
                'favicon' => 'branding/favicon.ico',
                'footer_logo' => 'branding/footer-logo.webp',
            ],
            'theme' => [
                'primary' => '#059669',
                'secondary' => '#0d9488',
                'accent' => '#f59e0b',
                'background' => '#f8fafc',
                'text' => '#0f172a',
                'heading_font' => 'Instrument Sans',
                'body_font' => 'Instrument Sans',
                'radius_style' => 'rounded-xl',
            ],
            'contact' => [
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'map_embed_url' => 'https://maps.google.com/?q=Addis+Ababa',
                'working_hours' => 'Mon - Fri: 8:30 AM - 5:30 PM',
            ],
            'social' => [
                ['platform' => 'facebook', 'url' => 'https://facebook.com/aimcharity', 'icon' => 'heroicon-o-globe-alt'],
                ['platform' => 'telegram', 'url' => 'https://t.me/aimcharity', 'icon' => 'heroicon-o-paper-airplane'],
            ],
            'navigation' => [
                ['label' => 'About', 'target' => 'section', 'section_key' => 'about', 'open_in_new_tab' => false],
                ['label' => 'Programs', 'target' => 'section', 'section_key' => 'programs', 'open_in_new_tab' => false],
            ],
            'seo' => [
                'meta_title' => fake()->sentence(4),
                'meta_description' => fake()->paragraph(2),
                'og_image' => 'branding/og.webp',
                'twitter_handle' => '@aimcharity',
                'analytics_snippet' => null,
            ],
            'footer' => [
                'about_blurb' => fake()->paragraph(2),
                'copyright_text' => 'Aim Charity. All rights reserved.',
                'legal_links' => [
                    ['label' => 'Privacy Policy', 'url' => '/privacy'],
                    ['label' => 'Terms of Service', 'url' => '/terms'],
                ],
            ],
        ];
    }
}
