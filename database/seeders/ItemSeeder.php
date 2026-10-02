<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DonationMethod;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\MemberGroup;
use App\Models\NewsPost;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Friend Circles & Volunteer Teams (Giving Circles)
        $groups = [
            [
                'name' => 'Founding Friends Circle',
                'short_description' => 'The childhood friends who started pooling weekly donations around a coffee table to care for neighbors.',
                'long_description' => '<p>Started by a close group of childhood friends who grew up on the same town streets, this circle coordinates weekly collections, reviews urgent neighbor requests, and leads Sunday morning visits to ensure every elder and struggling household receives personal care.</p>',
                'focus_area' => 'Weekly Direct Aid & Elder Visits',
                'founded_year' => '2021',
                'logo' => 'placeholders/groups/group-logo-1.svg',
                'photo' => 'placeholders/groups/group-photo-1.svg',
                'website_url' => 'https://example.org/founding-friends',
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/example'],
                ],
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Town Youth Volunteers',
                'short_description' => 'Energetic young graduates and high school alumni organizing weekend grocery deliveries and free tutoring.',
                'long_description' => '<p>Composed of energetic town youth, university students on break, and young professionals who give their weekends to package food baskets, deliver flour and oil to elders on foot, and tutor town children in basic literacy and numeracy.</p>',
                'focus_area' => 'Food Deliveries & Youth Tutoring',
                'founded_year' => '2022',
                'logo' => 'placeholders/groups/group-logo-2.svg',
                'photo' => 'placeholders/groups/group-photo-2.svg',
                'website_url' => 'https://example.org/town-youth',
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/example'],
                ],
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Neighborhood Mothers & Elders Circle',
                'short_description' => 'Respected mothers and elders who identify vulnerable households and prepare nourishing food care packages.',
                'long_description' => '<p>With deep knowledge of every street and compound in our town, the mothers and elders ensure that aid is distributed fairly, discreetly, and with unconditional dignity. They oversee food packaging and personally check on sick neighbors.</p>',
                'focus_area' => 'Family Welfare & Care Packages',
                'founded_year' => '2021',
                'logo' => 'placeholders/groups/group-logo-3.svg',
                'photo' => 'placeholders/groups/group-photo-3.svg',
                'website_url' => 'https://example.org/mothers-circle',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Town Teachers & Education Circle',
                'short_description' => 'Dedicated local school teachers who identify children needing uniforms, exercise books, and meal support.',
                'long_description' => '<p>Teachers from our primary and secondary schools ensure that no child in town drops out of school due to lack of uniforms, pencils, or notebooks. They coordinate student sponsorships and monitor classroom attendance.</p>',
                'focus_area' => 'Education & Student Supplies',
                'founded_year' => '2022',
                'logo' => 'placeholders/groups/group-logo-4.svg',
                'photo' => 'placeholders/groups/group-photo-4.svg',
                'website_url' => 'https://example.org/teachers-circle',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Town Merchants & Grocers Circle',
                'short_description' => 'Local shopkeepers and grocers providing food items at cost price and contributing weekly supplies.',
                'long_description' => '<p>Local millers, grain merchants, and grocers who partner with our association by waiving profit margins on relief purchases and donating sacks of teff, cooking oil, and soap each week.</p>',
                'focus_area' => 'At-Cost Groceries & Local Supply',
                'founded_year' => '2022',
                'logo' => 'placeholders/groups/group-logo-5.svg',
                'photo' => 'placeholders/groups/group-photo-5.svg',
                'website_url' => 'https://example.org/merchants-circle',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Diaspora Friends of the Town',
                'short_description' => 'Former residents living across Ethiopia and abroad who send regular weekly and monthly gifts home.',
                'long_description' => '<p>Friends who were born and raised in our small town but now live in larger cities or abroad, staying connected to their roots by faithfully contributing to our weekly pool for local families.</p>',
                'focus_area' => 'Remote Giving & Solidarity',
                'founded_year' => '2023',
                'logo' => 'placeholders/groups/group-logo-6.svg',
                'photo' => 'placeholders/groups/group-photo-6.svg',
                'website_url' => 'https://example.org/diaspora-friends',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($groups as $group) {
            MemberGroup::query()->updateOrCreate(['name' => $group['name']], $group);
        }

        $friendGroup = MemberGroup::query()->where('name', 'Founding Friends Circle')->first();

        // 2. Programs (Town Initiatives)
        $programs = [
            [
                'title' => 'Weekly Elder Care & Food Baskets',
                'description' => '<p>Many elderly town residents live without family support or pension. Every Sunday morning, our volunteer team delivers nutritious food baskets containing teff, wheat flour, lentils, cooking oil, and soap directly to their homes, spending time visiting and checking on their health.</p>',
                'icon' => 'heroicon-o-heart',
                'image' => 'placeholders/programs/program-1.svg',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Back-to-School Supplies for Town Kids',
                'description' => '<p>Working hand-in-hand with town teachers, we provide complete sets of exercise books, pens, backpacks, and tailored uniforms before each academic semester, ensuring every child in our town can attend school with pride and confidence.</p>',
                'icon' => 'heroicon-o-academic-cap',
                'image' => 'placeholders/programs/program-2.svg',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Emergency Neighbor Medical Fund',
                'description' => '<p>A sudden illness should never impoverish a family. When a neighbor faces acute medical costs, our emergency fund immediately covers hospital pharmacy prescriptions, diagnostic tests, and emergency ambulance or transit fees.</p>',
                'icon' => 'heroicon-o-shield-check',
                'image' => 'placeholders/programs/program-3.svg',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Home Warmth & Rainy Season Repairs',
                'description' => '<p>Before the heavy seasonal rains arrive, our volunteer youth inspect compounds of elderly and widowed neighbors to replace damaged iron roofing sheets, seal windows, and provide clean warm blankets and mattresses.</p>',
                'icon' => 'heroicon-o-home-modern',
                'image' => 'placeholders/programs/program-4.svg',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($programs as $program) {
            Program::query()->updateOrCreate(['title' => $program['title']], $program);
        }

        // 3. Impact Stats
        $stats = [
            [
                'value' => '120+',
                'label' => 'Contributing Friends & Neighbors',
                'icon' => 'heroicon-o-user-group',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'value' => '85+',
                'label' => 'Local Families Supported Monthly',
                'icon' => 'heroicon-o-heart',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'value' => '250+',
                'label' => 'School Children Equipped with Supplies',
                'icon' => 'heroicon-o-academic-cap',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'value' => '100%',
                'label' => 'Direct to Neighbors (Zero Overhead)',
                'icon' => 'heroicon-o-check-badge',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($stats as $stat) {
            ImpactStat::query()->updateOrCreate(['label' => $stat['label']], $stat);
        }

        // 4. How It Works Steps
        $steps = [
            [
                'title' => 'Friends Pool Weekly Donations',
                'description' => 'Friends, neighbors, and diaspora contributors send small weekly amounts via Telebirr or CBE into a dedicated community pool.',
                'icon' => 'heroicon-o-banknotes',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Personal Neighbor Visits',
                'description' => 'Our committee visits families in town, consults neighborhood elders and teachers, and verifies genuine needs with dignity.',
                'icon' => 'heroicon-o-user-plus',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Direct Local Purchase',
                'description' => 'We buy groceries, medicines, and school books directly from town grocers and merchants at wholesale/cost prices.',
                'icon' => 'heroicon-o-shopping-bag',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Handover with Open Books',
                'description' => 'Aid is handed directly to families on Sunday mornings, and complete expense receipts are shared in our friend Telegram group.',
                'icon' => 'heroicon-o-document-check',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($steps as $step) {
            Step::query()->updateOrCreate(['title' => $step['title']], $step);
        }

        // 5. Testimonials
        $testimonials = [
            [
                'quote' => 'When my health weakened, these young people who grew up on our street came to my door every Sunday with flour, oil, and warm smiles. They treat me like their own mother.',
                'author_name' => 'Emaye Almaz Gebre',
                'author_role' => 'Town Resident & Elder',
                'author_photo' => 'placeholders/testimonials/testimonial-1.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'quote' => 'Because of our friends circle, 42 of my fifth-grade students received uniforms, backpacks, and books this term. None of them had to drop out.',
                'author_name' => 'Teacher Solomon Haile',
                'author_role' => 'Primary School Instructor',
                'author_photo' => 'placeholders/testimonials/testimonial-2.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'quote' => 'We started with just five friends giving whatever we had in our pockets each Friday. Seeing our whole town rally around our neighbors is the greatest blessing.',
                'author_name' => 'Dawit Tesfaye',
                'author_role' => 'Founding Friend & Donor',
                'author_photo' => 'placeholders/testimonials/testimonial-3.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::query()->updateOrCreate(['author_name' => $testimonial['author_name']], $testimonial);
        }

        // 6. Gallery Items
        $gallery = [
            [
                'alt_text' => 'Volunteer friends packing teff flour, oil, and lentils for elderly town neighbors.',
                'caption' => 'Volunteers packing weekly food care baskets at the town community center.',
                'image' => 'placeholders/gallery/gallery-1.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'alt_text' => 'Children smiling with new exercise books and school bags.',
                'caption' => 'Local students receiving new exercise books and tailored uniforms before the school term.',
                'image' => 'placeholders/gallery/gallery-2.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'alt_text' => 'Volunteer visiting an elderly town resident at home.',
                'caption' => 'Weekend home visits bringing companionship, food supplies, and medical checkups.',
                'image' => 'placeholders/gallery/gallery-3.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'alt_text' => 'Friends and community members gathering to review weekly giving.',
                'caption' => 'Weekly transparent review meeting where all receipts and plans are shared openly.',
                'image' => 'placeholders/gallery/gallery-4.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($gallery as $item) {
            GalleryItem::query()->updateOrCreate(['caption' => $item['caption']], $item);
        }

        // 7. FAQs
        $faqs = [
            [
                'question' => 'What is Aim Charity and how did it start?',
                'answer' => '<p>Aim Charity is a small-town charity association started by a group of close childhood friends in Ethiopia. We started by pooling small weekly donations to care for elderly neighbors and struggling families in our hometown. We are not a large corporate establishment; we are friends and neighbors volunteering our time.</p>',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'How does the weekly donation model work?',
                'answer' => '<p>Anyone can give weekly — whether it is 50, 100, or 500 Birr. Donors transfer directly via Telebirr or CBE. Every weekend, the pooled contributions are used to buy food, medicine, and school supplies for verified neighbors in town.</p>',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'Does Aim Charity take administrative fees or salaries?',
                'answer' => '<p>Zero percent. None of the founding friends or volunteers receive salaries or administrative compensation. 100% of every birr donated goes directly into purchasing aid for town residents.</p>',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'How do you choose which neighbors receive assistance?',
                'answer' => '<p>Because we live in the town and know our community, we work with trusted neighborhood elders and teachers to identify those in greatest need. Friends personally visit every household to assess situations with warmth, privacy, and respect.</p>',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'Can friends outside our town or in the diaspora join?',
                'answer' => '<p>Yes, warmly! Many of our regular weekly contributors are friends who grew up in our town and now live in other cities or abroad, keeping their hometown connection alive through regular giving.</p>',
                'is_visible' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate(['question' => $faq['question']], $faq);
        }

        // 8. Donation Methods
        $methods = [
            [
                'label' => 'Commercial Bank of Ethiopia (CBE)',
                'account_name' => 'Aim Charity Association [Small-Town Giving Circle]',
                'account_number' => '1000-0000-0000-0000',
                'instructions' => 'Add "Weekly Friend Donation" and your name in the transfer reason. 100% directly purchases food and medical aid for town neighbors.',
                'logo' => 'placeholders/donation/cbe-logo.svg',
                'qr_image' => 'placeholders/donation/qr-cbe.svg',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Telebirr Mobile Money',
                'account_name' => 'Aim Charity Town Circle',
                'account_number' => '+251 91 123 4567',
                'instructions' => 'Send your weekly gift directly via Telebirr with note "Town Support". Instant confirmation sent to our coordinator.',
                'logo' => 'placeholders/donation/telebirr-logo.svg',
                'qr_image' => 'placeholders/donation/qr-telebirr.svg',
                'is_visible' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($methods as $method) {
            DonationMethod::query()->updateOrCreate(['label' => $method['label']], $method);
        }

        // 9. Partners (Local Pillars)
        $partners = [
            [
                'name' => 'Town Community Elders Council',
                'logo' => 'placeholders/partners/partner-1.svg',
                'url' => 'https://example.org/elders-council',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Local Kebele Health Post',
                'logo' => 'placeholders/partners/partner-2.svg',
                'url' => 'https://example.org/health-post',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Town Primary & Secondary School Committee',
                'logo' => 'placeholders/partners/partner-3.svg',
                'url' => 'https://example.org/school-committee',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Local Merchants & Grocers Cooperative',
                'logo' => 'placeholders/partners/partner-4.svg',
                'url' => 'https://example.org/grocers-coop',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::query()->updateOrCreate(['name' => $partner['name']], $partner);
        }

        // 10. News Posts
        $news = [
            [
                'title' => 'Celebrating Our 50th Consecutive Weekly Food Basket Delivery',
                'slug' => '50th-weekly-food-basket-delivery',
                'excerpt' => 'What began as five friends pooling a few birr has now delivered 50 uninterrupted weeks of fresh food support to 85 town households.',
                'body' => '<p>This Sunday marks a humble but meaningful milestone for Aim Charity: 50 consecutive weeks of food basket deliveries. Every single Sunday for almost a full year, our volunteer youth and friends have visited the homes of elderly neighbors and struggling families with bags of teff flour, cooking oil, lentils, and basic hygiene supplies.</p><p>We want to thank every friend, neighbor, and diaspora contributor whose weekly 50, 100, or 500 Birr gifts made this consistency possible. In a small town, consistency is everything. Our elders know that every Sunday morning, a knock on their door means breakfast, respect, and love from the community.</p>',
                'cover_image' => 'placeholders/news/news-1.svg',
                'published_at' => Carbon::now()->subDays(3),
                'is_visible' => true,
            ],
            [
                'title' => 'Back to School: 250 Town Children Equipped for the New Term',
                'slug' => 'back-to-school-250-children-equipped',
                'excerpt' => 'Thanks to our weekly education pool, 250 students across three town schools received tailored uniforms and complete stationery sets.',
                'body' => '<p>Education is the best gift our community can give its children. Ahead of the new school term, our Town Teachers Circle coordinated with local tailors and stationery shops to provide 250 children with durable uniforms, backpacks, pencils, and exercise books.</p><p>Seeing these young students walk into school with smiles on their faces and books in hand is a testament to what friends can do when we pool our efforts. Thank you to everyone who contributed to our Back-to-School drive.</p>',
                'cover_image' => 'placeholders/news/news-2.svg',
                'published_at' => Carbon::now()->subDays(12),
                'is_visible' => true,
            ],
        ];

        foreach ($news as $post) {
            NewsPost::query()->updateOrCreate(['slug' => $post['slug']], $post);
        }

        // 11. Team Members (Founding Friends & Coordinators)
        $team = [
            [
                'name' => 'Dawit Mekonnen',
                'role' => 'Founding Friend & Coordinator',
                'photo' => 'placeholders/team/team-1.svg',
                'bio' => 'One of the childhood friends who initiated our weekly giving circle. Coordinates Sunday deliveries and volunteers.',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bethlehem Tadesse',
                'role' => 'Elder Welfare & Family Visits',
                'photo' => 'placeholders/team/team-2.svg',
                'bio' => 'Leads our home visits team, ensuring elderly neighbors and single mothers receive food baskets and personal care.',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Yonas Girma',
                'role' => 'Weekly Accounts & Transparency',
                'photo' => 'placeholders/team/team-3.svg',
                'bio' => 'Reconciles weekly Telebirr and bank donations, verifies merchant receipts, and publishes open records to our donor group.',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Selamawit Abebe',
                'role' => 'Education & Youth Volunteer Lead',
                'photo' => 'placeholders/team/team-4.svg',
                'bio' => 'Works directly with town school teachers to identify students in need and manages our weekend youth tutoring sessions.',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($team as $member) {
            TeamMember::query()->updateOrCreate(['name' => $member['name']], $member);
        }

        Site::flushCache();
    }
}
