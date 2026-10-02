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
                    'tagline' => 'Small-Town Charity Association Started by Friends',
                    'logo_light' => 'branding/logo-light.svg',
                    'logo_dark' => 'branding/logo-dark.svg',
                    'favicon' => 'branding/favicon.svg',
                    'footer_logo' => 'branding/footer-logo.svg',
                ],
                'theme' => [
                    'primary' => '#1b4332',
                    'secondary' => '#2d6a4f',
                    'accent' => '#d97706',
                    'background' => '#fbf9f5',
                    'surface' => '#ffffff',
                    'text' => '#1c1917',
                    'heading_font' => 'Fraunces',
                    'body_font' => 'Plus Jakarta Sans',
                    'radius_style' => 'rounded-2xl',
                ],
                'contact' => [
                    'email' => 'contact@aimcharity.org',
                    'notification_email' => 'notifications@aimcharity.org',
                    'phone' => '+251 11 123 4567',
                    'address' => 'Community Center, Town Kebele 02, Ethiopia',
                    'map_embed_url' => 'https://maps.google.com/?q=Ethiopia',
                    'working_hours' => 'Weekly Gatherings: Sundays 9:00 AM - 1:00 PM (EAT)',
                ],
                'social' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/aimcharity_et', 'icon' => 'heroicon-o-paper-airplane'],
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com/aimcharityet', 'icon' => 'heroicon-o-globe-alt'],
                    ['platform' => 'LinkedIn', 'url' => 'https://linkedin.com/company/aimcharity', 'icon' => 'heroicon-o-briefcase'],
                ],
                'navigation' => [
                    ['label' => 'Our Story', 'target' => 'section', 'section_key' => 'about', 'open_in_new_tab' => false],
                    ['label' => 'Giving Circles', 'target' => 'section', 'section_key' => 'member_groups', 'open_in_new_tab' => false],
                    ['label' => 'Programs', 'target' => 'section', 'section_key' => 'programs', 'open_in_new_tab' => false],
                    ['label' => 'Impact', 'target' => 'section', 'section_key' => 'impact_stats', 'open_in_new_tab' => false],
                    ['label' => 'How It Works', 'target' => 'section', 'section_key' => 'how_it_works', 'open_in_new_tab' => false],
                    ['label' => 'Weekly Giving', 'target' => 'section', 'section_key' => 'donate', 'open_in_new_tab' => false],
                    ['label' => 'Contact', 'target' => 'section', 'section_key' => 'contact', 'open_in_new_tab' => false],
                ],
                'seo' => [
                    'meta_title' => 'Aim Charity — Small-Town Charity Association Started by Friends',
                    'meta_description' => 'Aim Charity is a small-town charity association started by a group of close friends in Ethiopia. Through weekly donations, we care for elderly neighbors, support local families, and keep kids in school.',
                    'og_image' => 'branding/logo-dark.svg',
                    'twitter_handle' => '@aimcharity',
                    'analytics_snippet' => null,
                ],
                'footer' => [
                    'nav_heading' => 'Navigation',
                    'contact_heading' => 'Contact Us',
                    'newsletter_heading' => 'Weekly Updates',
                    'newsletter_placeholder' => 'Enter your email address',
                    'newsletter_button' => 'Stay Updated',
                    'about_blurb' => 'Aim Charity was started by a group of friends in our small town to support elderly neighbors, struggling families, and local school children through weekly community donations. 100% direct, zero overhead.',
                    'copyright_text' => '© {year} Aim Charity Association. Started by friends for our town. All rights reserved.',
                    'legal_links' => [
                        ['label' => 'Privacy Policy', 'url' => '#'],
                        ['label' => 'Weekly Giving Transparency', 'url' => '#'],
                        ['label' => 'Community Accountability', 'url' => '#'],
                    ],
                ],
            ]
        );
    }
}
