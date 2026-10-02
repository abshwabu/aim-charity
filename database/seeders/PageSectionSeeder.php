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
                    'eyebrow' => 'Small-Town Mutual Aid',
                    'heading' => 'Started by Friends. Sustained by Weekly Kindness.',
                    'subheading' => 'A small-town charity association of 25 close friends. We pool weekly donations to provide food, clothes, and direct local support to neighbors in need.',
                    'collage_images' => [
                        'placeholders/hero/hero-collage-1.svg',
                        'placeholders/hero/hero-collage-2.svg',
                        'placeholders/hero/hero-collage-3.svg',
                    ],
                    'stat_chips' => [
                        ['value' => '25', 'label' => 'Dedicated Members', 'icon' => 'heroicon-o-user-group'],
                        ['value' => '85+', 'label' => 'Town Families Supported', 'icon' => 'heroicon-o-heart'],
                        ['value' => '100%', 'label' => 'Direct to Neighbors', 'icon' => 'heroicon-o-check-badge'],
                    ],
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
                    'eyebrow' => 'Our Story',
                    'heading' => 'How 25 Friends Started a Small-Town Association',
                    'subheading' => 'We are not an NGO, not a country-wide program, and not a coalition — just 25 friends and neighbors taking care of our hometown.',
                    'coalition_explainer' => 'Aim Charity was started by 25 childhood friends in our small town. Every week, we pool whatever we can from our own pockets to purchase and deliver food baskets, warm clothes, and local emergency support to neighbors in need — 100% direct with zero administrative overhead.',
                    'body' => '<p>It all began when a close group of childhood friends met for coffee in our town. Looking around our neighborhood, we saw elderly neighbors living alone without enough food to eat, children shivering on cold mornings without warm clothes, and families struggling with sudden clinic bills. We did not have institutional funding or government grants, but we had our friendship and our love for our town.</p><p>We decided to start pooling small weekly donations — whatever each of us 25 friends could spare each week. What began as informal Sunday morning visits has grown into <strong>Aim Charity</strong>: a small-town charity association where our 25 members ensure that 100% of every contribution buys food, clothes, and local essentials for our neighbors.</p>',
                    'photos' => [
                        'placeholders/gallery/gallery-1.svg',
                        'placeholders/gallery/gallery-2.svg',
                    ],
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
                'nav_label' => 'Our Group',
                'anchor' => 'members',
                'content' => array_merge(SectionTypes::defaultContent('member_groups'), [
                    'eyebrow' => 'Our Association',
                    'heading' => 'One Group of 25 Friends',
                    'subheading' => 'We are not a coalition of multiple organizations. We are a single group of 25 childhood friends and town neighbors.',
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
                    'eyebrow' => 'Local Town Programs',
                    'heading' => 'Food, Clothing & Local Neighbor Support',
                    'subheading' => 'Practical, direct initiatives for our town: weekly food baskets, warm clothing and blankets, and emergency local assistance.',
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
                    'eyebrow' => 'Local Impact',
                    'heading' => 'What Small Weekly Gifts Accomplish Together',
                    'subheading' => 'When friends pool consistent weekly contributions, small gifts add up to life-changing support for our town.',
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
                    'eyebrow' => 'Simple & Transparent',
                    'heading' => 'How Our Weekly Giving Works',
                    'subheading' => 'From weekly contributions among friends to direct handovers in our neighborhood.',
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
                    'eyebrow' => 'Words from Neighbors',
                    'heading' => 'Stories of Warmth, Dignity & Gratitude',
                    'subheading' => 'Hear from elderly town residents, dedicated teachers, and participating friends.',
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
                    'eyebrow' => 'Moments in Our Town',
                    'heading' => 'Our Weekly Deliveries in Pictures',
                    'subheading' => 'Photographs from our Sunday morning food distributions, school supply handovers, and elder home visits.',
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
                    'eyebrow' => 'Weekly Giving',
                    'heading' => 'Support Our Town Giving Circle',
                    'subheading' => 'Give weekly or monthly via Telebirr or CBE. 100% of your contribution directly purchases essentials for neighbors in our town.',
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
                    'heading' => 'Join Us on Weekend Visits',
                    'subheading' => 'Help package food baskets, tutor neighborhood students, or visit elderly town residents with our volunteer team.',
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
                    'eyebrow' => 'Founding Friends',
                    'heading' => 'The Friends Behind Aim Charity',
                    'subheading' => 'Meet the childhood friends and town neighbors who volunteer their time to coordinate weekly giving.',
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
                    'eyebrow' => 'Local Partners',
                    'heading' => 'Working Hand-in-Hand with Town Pillars',
                    'subheading' => 'We collaborate with local elders, neighborhood health clinics, and schools to identify families in need.',
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#f8fafc',
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
                    'eyebrow' => 'Town Updates',
                    'heading' => 'Stories & Milestones from Our Town',
                    'subheading' => 'Follow our weekly distribution reports, school preparation drives, and community milestones.',
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
                'key' => 'faq',
                'type' => 'faq',
                'sort_order' => 14,
                'is_visible' => true,
                'nav_label' => 'FAQ',
                'anchor' => 'faq',
                'content' => array_merge(SectionTypes::defaultContent('faq'), [
                    'eyebrow' => 'Common Questions',
                    'heading' => 'Frequently Asked Questions',
                    'subheading' => 'Everything you need to know about our friend-founded association, weekly giving, and direct neighbor support.',
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
                'key' => 'contact',
                'type' => 'contact',
                'sort_order' => 15,
                'is_visible' => true,
                'nav_label' => 'Contact',
                'anchor' => 'contact',
                'content' => array_merge(SectionTypes::defaultContent('contact'), [
                    'eyebrow' => 'Get in Touch',
                    'heading' => 'Reach Out to Our Friends Committee',
                    'subheading' => 'Have questions about joining our weekly donor circle or know a neighbor in need? Send us a message.',
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
                'key' => 'cta_banner',
                'type' => 'cta_banner',
                'sort_order' => 16,
                'is_visible' => true,
                'nav_label' => null,
                'anchor' => 'cta',
                'content' => array_merge(SectionTypes::defaultContent('cta_banner'), [
                    'badge_text' => 'Join Our Circle',
                    'eyebrow' => 'Join Our Circle',
                    'heading' => 'Small Town, Big Heart. Stand With Our Neighbors.',
                    'text' => 'Even a small weekly gift of 50 or 100 Birr provides warmth, food, and dignity to a family in our town.',
                    'subheading' => 'Even a small weekly gift of 50 or 100 Birr provides warmth, food, and dignity to a family in our town.',
                    'buttons' => [
                        ['label' => 'Start Weekly Giving', 'link_type' => 'section', 'target' => 'donate', 'url' => null, 'style' => 'primary'],
                        ['label' => 'Volunteer on Sundays', 'link_type' => 'section', 'target' => 'volunteer', 'url' => null, 'style' => 'outline'],
                    ],
                    'body' => null,
                ]),
                'style' => [
                    'background_type' => 'color',
                    'background_color' => '#1b4332',
                    'text_theme' => 'dark',
                    'vertical_padding' => 'l',
                    'alignment' => 'center',
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            PageSection::query()->updateOrCreate(
                ['key' => $sectionData['key']],
                $sectionData
            );
        }

        Site::flushCache();
    }
}
