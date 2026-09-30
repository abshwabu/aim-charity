<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
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
                    'phone' => '+251 11 123 4567',
                    'address' => 'Bole Sub-City, Addis Ababa, Ethiopia',
                    'map_embed_url' => 'https://maps.google.com/?q=Addis+Ababa',
                    'working_hours' => 'Mon - Fri: 8:30 AM - 5:30 PM (EAT)',
                ],
                'social' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/aimcharity_et', 'icon' => 'heroicon-o-paper-airplane'],
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com/aimcharityet', 'icon' => 'heroicon-o-globe-alt'],
                    ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/aimcharity', 'icon' => 'heroicon-o-briefcase'],
                ],
                'navigation' => [
                    ['label' => 'About', 'target' => 'section', 'section_key' => 'about', 'open_in_new_tab' => false],
                    ['label' => 'Member Groups', 'target' => 'section', 'section_key' => 'member_groups', 'open_in_new_tab' => false],
                    ['label' => 'Programs', 'target' => 'section', 'section_key' => 'programs', 'open_in_new_tab' => false],
                    ['label' => 'Impact', 'target' => 'section', 'section_key' => 'impact_stats', 'open_in_new_tab' => false],
                    ['label' => 'How It Works', 'target' => 'section', 'section_key' => 'how_it_works', 'open_in_new_tab' => false],
                    ['label' => 'Donate', 'target' => 'section', 'section_key' => 'donate', 'open_in_new_tab' => false],
                    ['label' => 'Contact', 'target' => 'section', 'section_key' => 'contact', 'open_in_new_tab' => false],
                ],
                'seo' => [
                    'meta_title' => 'Aim Charity — A Coalition of Community Organizations in Ethiopia',
                    'meta_description' => 'Aim Charity unites community groups across Ethiopia to coordinate relief, build mutual aid networks, and uplift vulnerable families.',
                    'og_image' => null,
                    'twitter_handle' => '@aimcharity',
                    'analytics_snippet' => null,
                ],
                'footer' => [
                    'about_blurb' => 'Aim Charity is a coalition ("a group of groups") of grassroots community organizations in Ethiopia that came together to help people in need with dignity, solidarity, and transparency.',
                    'copyright_text' => 'Aim Charity Coalition. All rights reserved.',
                    'legal_links' => [
                        ['label' => 'Privacy Policy', 'url' => '#'],
                        ['label' => 'Terms of Use', 'url' => '#'],
                        ['label' => 'Transparency & Reports', 'url' => '#'],
                    ],
                ],
            ]
        );
    }
}
