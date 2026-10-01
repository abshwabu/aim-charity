<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageSection;
use App\Support\SectionTypes;
use App\Support\Site;
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
                'content' => array_merge(SectionTypes::defaultContent('hero'), [
                    'eyebrow' => 'Coalition of Grassroots Organizations',
                    'heading' => 'Empowering Communities, Transforming Lives Together',
                    'subheading' => 'A coalition of grassroots community organizations in Ethiopia united to deliver mutual aid, relief, and sustainable impact to families in need.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'l',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'about',
                'type' => 'about',
                'sort_order' => 2,
                'is_visible' => true,
                'nav_label' => 'About',
                'anchor' => 'about',
                'content' => array_merge(SectionTypes::defaultContent('about'), [
                    'eyebrow' => 'Who We Are',
                    'heading' => 'A Unified Front for Community Relief',
                    'subheading' => 'Aim Charity brings together local Ethiopian groups to coordinate resources, eliminate duplication, and deliver direct assistance.',
                    'body' => '<p>By acting as a "group of groups," Aim Charity combines logistics, verifies community needs, and ensures complete transparency in aid distribution across Ethiopia.</p>',
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'left',
                ],
            ],
            [
                'key' => 'member_groups',
                'type' => 'member_groups',
                'sort_order' => 3,
                'is_visible' => true,
                'nav_label' => 'Members',
                'anchor' => 'members',
                'content' => array_merge(SectionTypes::defaultContent('member_groups'), [
                    'eyebrow' => 'Our Coalition Members',
                    'heading' => 'The Community Groups Behind Aim Charity',
                    'subheading' => 'Rooted in neighborhoods, towns, and rural woredas across Ethiopia, our member organizations understand local realities best.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'programs',
                'type' => 'programs',
                'sort_order' => 4,
                'is_visible' => true,
                'nav_label' => 'Programs',
                'anchor' => 'programs',
                'content' => array_merge(SectionTypes::defaultContent('programs'), [
                    'eyebrow' => 'Our Focus Areas',
                    'heading' => 'Targeted Relief & Sustainable Development',
                    'subheading' => 'From emergency nutrition and medical clinics to clean water access and vocational training for youth and women.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'impact_stats',
                'type' => 'impact_stats',
                'sort_order' => 5,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'impact',
                'content' => array_merge(SectionTypes::defaultContent('impact_stats'), [
                    'eyebrow' => 'Measurable Change',
                    'heading' => 'Our Collective Impact in Numbers',
                    'subheading' => 'Every contribution creates verifiable improvements in the daily lives of Ethiopian families.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'how_it_works',
                'type' => 'how_it_works',
                'sort_order' => 6,
                'is_visible' => true,
                'nav_label' => 'How It Works',
                'anchor' => 'how-it-works',
                'content' => array_merge(SectionTypes::defaultContent('how_it_works'), [
                    'eyebrow' => 'The Coalition Model',
                    'heading' => 'How We Coordinate Relief & Assistance',
                    'subheading' => 'A clear, four-step cycle from grassroots need identification to audited delivery.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'testimonials',
                'type' => 'testimonials',
                'sort_order' => 7,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'testimonials',
                'content' => array_merge(SectionTypes::defaultContent('testimonials'), [
                    'eyebrow' => 'Voices from the Field',
                    'heading' => 'Stories of Hope & Community Strength',
                    'subheading' => 'Listen to the direct testimonies of community elders, women entrepreneurs, and aid recipients.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'gallery',
                'type' => 'gallery',
                'sort_order' => 8,
                'is_visible' => true,
                'nav_label' => 'Gallery',
                'anchor' => 'gallery',
                'content' => array_merge(SectionTypes::defaultContent('gallery'), [
                    'eyebrow' => 'Field Photographs',
                    'heading' => 'Moments of Community Action',
                    'subheading' => 'Documenting distribution days, medical checkups, and volunteer mobilization across the country.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'donate',
                'type' => 'donate',
                'sort_order' => 9,
                'is_visible' => true,
                'nav_label' => 'Donate',
                'anchor' => 'donate',
                'content' => array_merge(SectionTypes::defaultContent('donate'), [
                    'eyebrow' => 'Direct Support',
                    'heading' => 'Support Our Community Relief Efforts',
                    'subheading' => 'Contribute securely via local bank transfer, Telebirr, or direct account transfer.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'l',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'volunteer',
                'type' => 'volunteer',
                'sort_order' => 10,
                'is_visible' => true,
                'nav_label' => 'Volunteer',
                'anchor' => 'volunteer',
                'content' => array_merge(SectionTypes::defaultContent('volunteer'), [
                    'eyebrow' => 'Get Involved',
                    'heading' => 'Volunteer Your Time and Skills',
                    'subheading' => 'Join our active volunteer registry to assist in local distributions, medical outreach, and logistical operations.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'team',
                'type' => 'team',
                'sort_order' => 11,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'team',
                'content' => array_merge(SectionTypes::defaultContent('team'), [
                    'eyebrow' => 'Leadership',
                    'heading' => 'The Steering Committee & Coordinators',
                    'subheading' => 'Meet the dedicated organizers facilitating coordination and accountability across our member groups.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'partners',
                'type' => 'partners',
                'sort_order' => 12,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'partners',
                'content' => array_merge(SectionTypes::defaultContent('partners'), [
                    'eyebrow' => 'Institutional Allies',
                    'heading' => 'Our Partners in Relief and Resilience',
                    'subheading' => 'Working together with civic institutions, humanitarian foundations, and diaspora associations.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 's',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'news',
                'type' => 'news',
                'sort_order' => 13,
                'is_visible' => true,
                'nav_label' => 'News',
                'anchor' => 'news',
                'content' => array_merge(SectionTypes::defaultContent('news'), [
                    'eyebrow' => 'Dispatches & Reports',
                    'heading' => 'Latest News & Field Updates',
                    'subheading' => 'Read our monthly accountability reports, emergency alerts, and program milestones.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'faq',
                'type' => 'faq',
                'sort_order' => 14,
                'is_visible' => true,
                'nav_label' => 'FAQ',
                'anchor' => 'faq',
                'content' => array_merge(SectionTypes::defaultContent('faq'), [
                    'eyebrow' => 'Questions Answered',
                    'heading' => 'Frequently Asked Questions',
                    'subheading' => 'Clear details on donation handling, coalition membership criteria, and community verification.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'none',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'contact',
                'type' => 'contact',
                'sort_order' => 15,
                'is_visible' => true,
                'nav_label' => 'Contact',
                'anchor' => 'contact',
                'content' => array_merge(SectionTypes::defaultContent('contact'), [
                    'eyebrow' => 'Reach Out',
                    'heading' => 'Get in Touch with Our Coordination Office',
                    'subheading' => 'We are here to answer questions, explore collaboration, or assist with community requests.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
                    'text_theme' => 'light',
                    'vertical_padding' => 'm',
                    'alignment' => 'center',
                ],
            ],
            [
                'key' => 'cta_banner',
                'type' => 'cta_banner',
                'sort_order' => 16,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'cta',
                'content' => array_merge(SectionTypes::defaultContent('cta_banner'), [
                    'eyebrow' => null,
                    'heading' => 'Together, We Can Deliver Hope and Relief Across Ethiopia',
                    'subheading' => 'Join hundreds of donors, volunteers, and grassroots community organizers standing united.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#064e3b',
                    'text_theme' => 'dark',
                    'vertical_padding' => 'l',
                    'alignment' => 'center',
                ],
            ],
        ];

        foreach ($sections as $section) {
            PageSection::query()->updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }

        Site::flushCache();
    }
}
