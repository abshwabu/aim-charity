<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'type' => 'hero',
                'sort_order' => 1,
                'is_visible' => true,
                'nav_label' => 'Home',
                'anchor' => 'hero',
                'content' => [
                    'eyebrow' => 'Platform Initialized & Active',
                    'heading' => 'Empowering Communities, Transforming Lives Together',
                    'subheading' => 'A coalition of grassroots community organizations in Ethiopia united to deliver mutual aid, relief, and sustainable impact to families and communities in need.',
                    'body' => null,
                    'images' => [],
                    'buttons' => [
                        [
                            'label' => 'Access Admin Panel',
                            'url' => '/admin',
                            'style' => 'primary',
                        ],
                    ],
                ],
                'style' => [
                    'background_color' => '#ffffff',
                    'text_theme' => 'light',
                    'padding_size' => 'large',
                ],
            ],
            [
                'key' => 'about',
                'type' => 'about',
                'sort_order' => 2,
                'is_visible' => true,
                'nav_label' => 'About Us',
                'anchor' => 'about',
                'content' => [
                    'eyebrow' => 'About the Coalition',
                    'heading' => 'A Unified Front for Community Relief',
                    'subheading' => 'Aim Charity brings together local Ethiopian groups to coordinate resources and deliver direct assistance.',
                    'body' => '<p>By acting as a group of groups, Aim Charity eliminates duplicated efforts and reaches those most in need across Ethiopia.</p>',
                    'images' => [],
                    'buttons' => [],
                ],
                'style' => [
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'padding_size' => 'default',
                ],
            ],
        ];

        foreach ($sections as $section) {
            PageSection::query()->updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
