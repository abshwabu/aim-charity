<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use FlushesSiteCache, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'branding',
        'theme',
        'contact',
        'social',
        'navigation',
        'seo',
        'footer',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'theme' => 'array',
            'contact' => 'array',
            'social' => 'array',
            'navigation' => 'array',
            'seo' => 'array',
            'footer' => 'array',
        ];
    }

    /**
     * Retrieve the singleton site settings instance, initializing with defaults if missing.
     */
    public static function current(): self
    {
        try {
            /** @var self|null $settings */
            $settings = static::query()->first();

            if ($settings !== null) {
                return $settings;
            }
        } catch (Throwable) {
            // Database not yet migrated or table unavailable
        }

        return new self([
            'branding' => [
                'site_name' => 'Aim Charity',
                'tagline' => 'A Coalition of Community Organizations in Ethiopia',
                'logo_light' => null,
                'logo_dark' => null,
                'favicon' => null,
                'footer_logo' => null,
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
                'email' => 'contact@aimcharity.org',
                'phone' => '+251 11 000 0000',
                'address' => 'Addis Ababa, Ethiopia',
                'map_embed_url' => null,
                'working_hours' => 'Mon - Fri: 8:30 AM - 5:30 PM',
            ],
            'social' => [],
            'navigation' => [],
            'seo' => [
                'meta_title' => 'Aim Charity — Coalition of Community Organizations',
                'meta_description' => 'Aim Charity brings together community groups in Ethiopia to help people in need.',
                'og_image' => null,
                'twitter_handle' => null,
                'analytics_snippet' => null,
            ],
            'footer' => [
                'about_blurb' => 'Aim Charity is a coalition of grassroots organizations dedicated to mutual aid and community resilience across Ethiopia.',
                'copyright_text' => 'Aim Charity. All rights reserved.',
                'legal_links' => [],
            ],
        ]);
    }
}
