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
        // 1. Single Association Group (Aim Charity: 25 Friends)
        $groups = [
            [
                'name' => 'Aim Charity (25 Member Friends)',
                'short_description' => 'Our single town charity group of 25 close friends pooling weekly funds to care for local neighbors.',
                'long_description' => '<p>Aim Charity is not a federation, coalition, or corporate NGO. We are a single, tight-knit group of 25 childhood friends and town neighbors. Every week, our 25 members contribute whatever we can from our personal earnings to purchase and hand-deliver food, warm clothing, and emergency local aid to elderly neighbors and struggling families in our town.</p>',
                'focus_area' => 'Food, Clothing & Local Town Aid',
                'founded_year' => '2021',
                'logo' => 'placeholders/groups/group-logo-1.svg',
                'photo' => 'placeholders/groups/group-photo-1.svg',
                'website_url' => null,
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/aimcharity_et'],
                ],
                'is_visible' => true,
                'sort_order' => 1,
            ],
        ];

        MemberGroup::query()->delete();
        foreach ($groups as $group) {
            MemberGroup::query()->create($group);
        }

        $friendGroup = MemberGroup::query()->first();

        // 2. Programs (Food, Clothing & Local Support — Local Town Only)
        $programs = [
            [
                'title' => 'Food Support for Local Families & Elders',
                'description' => '<p>Every Sunday morning, our 25 members pack and personally deliver nourishing food baskets containing teff, wheat flour, edible cooking oil, lentils, and sugar directly to elderly neighbors and struggling families in our town who cannot afford daily meals.</p>',
                'icon' => 'heroicon-o-shopping-bag',
                'image' => 'placeholders/programs/program-1.svg',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Clothing, Blankets & Warmth Support',
                'description' => '<p>We collect and purchase warm winter jackets, sweaters, shoes, and heavy blankets for town elders, bedridden neighbors, and children facing cold highland nights, as well as school uniforms for kids in need.</p>',
                'icon' => 'heroicon-o-sparkles',
                'image' => 'placeholders/programs/program-2.svg',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Local Town Needs & Emergency Aid',
                'description' => '<p>We take care of other urgent local needs in our neighborhood: buying school exercise books and pens so town children stay in school, paying for urgent clinic prescriptions and medicine for sick neighbors, and doing quick compound or roof repairs before the rainy season.</p>',
                'icon' => 'heroicon-o-heart',
                'image' => 'placeholders/programs/program-3.svg',
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        Program::query()->delete();
        foreach ($programs as $program) {
            Program::query()->create($program);
        }

        // 3. Impact Stats
        $stats = [
            [
                'value' => '25',
                'label' => 'Member Friends in Our Group',
                'icon' => 'heroicon-o-user-group',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'value' => '85+',
                'label' => 'Town Families Supported with Food & Clothes',
                'icon' => 'heroicon-o-heart',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'value' => '150+',
                'label' => 'Warm Blankets & Clothes Distributed',
                'icon' => 'heroicon-o-sparkles',
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

        ImpactStat::query()->delete();
        foreach ($stats as $stat) {
            ImpactStat::query()->create($stat);
        }

        // 4. How It Works Steps
        $steps = [
            [
                'title' => '25 Friends Pool Weekly Donations',
                'description' => 'Every Sunday, our 25 members contribute whatever we can afford into our dedicated pool via Telebirr or CBE.',
                'icon' => 'heroicon-o-banknotes',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Personal Neighbor Visits',
                'description' => 'We personally visit homes in our town, consult neighbors, and check who urgently needs food, warm clothes, or medicine.',
                'icon' => 'heroicon-o-user-plus',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Direct Local Purchases',
                'description' => 'We buy teff, cooking oil, blankets, and school supplies directly from local town merchants at cost price.',
                'icon' => 'heroicon-o-shopping-bag',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'In-Person Handover with Open Receipts',
                'description' => 'We hand-deliver food and clothes directly to our neighbors every Sunday, sharing all receipts openly with zero admin deductions.',
                'icon' => 'heroicon-o-document-check',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        Step::query()->delete();
        foreach ($steps as $step) {
            Step::query()->create($step);
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
                'quote' => 'Because of the 25 friends, our students received warm school sweaters, backpacks, and exercise books this term. None of them had to drop out.',
                'author_name' => 'Teacher Solomon Haile',
                'author_role' => 'Local Primary School Instructor',
                'author_photo' => 'placeholders/testimonials/testimonial-2.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'quote' => 'We started with just friends giving whatever pocket money we had each week. Seeing our whole neighborhood cared for with food and warmth is the greatest blessing.',
                'author_name' => 'Dawit Tesfaye',
                'author_role' => 'Founding Friend & Donor',
                'author_photo' => 'placeholders/testimonials/testimonial-3.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        Testimonial::query()->delete();
        foreach ($testimonials as $testimonial) {
            Testimonial::query()->create($testimonial);
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
                'alt_text' => 'Children smiling with new warm clothes, shoes, and exercise books.',
                'caption' => 'Local students receiving warm clothing, shoes, and exercise books for school.',
                'image' => 'placeholders/gallery/gallery-2.svg',
                'member_group_id' => $friendGroup?->id,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'alt_text' => 'Friends visiting an elderly town resident at home with food supplies.',
                'caption' => 'Weekend home visits bringing companionship, food supplies, and essential care.',
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

        GalleryItem::query()->delete();
        foreach ($gallery as $item) {
            GalleryItem::query()->create($item);
        }

        // 7. FAQs
        $faqs = [
            [
                'question' => 'What is Aim Charity and how did it start?',
                'answer' => '<p>Aim Charity is a small-town charity association started by a group of 25 close childhood friends in Ethiopia. We started by pooling small weekly donations to care for elderly neighbors and struggling families in our hometown. We are not a large corporate establishment; we are 25 friends and neighbors volunteering our own time.</p>',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'Are there other groups, chapters, or branches?',
                'answer' => '<p>No. There is only our single group of 25 members. We focus 100% on our own local town and neighborhood rather than running broad country-wide programs.</p>',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'What kinds of support do you provide?',
                'answer' => '<p>Our primary focus is weekly food support (teff, flour, cooking oil, grains) and clothing support (jackets, sweaters, blankets, shoes, school uniforms). We also assist with local urgent needs like clinic medicine and school supplies for children.</p>',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'Does Aim Charity take administrative fees or salaries?',
                'answer' => '<p>Zero percent. None of the 25 friends receive salaries or administrative compensation. 100% of every birr donated goes directly into purchasing food, clothes, and supplies for town residents.</p>',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'How can someone join or support the weekly giving?',
                'answer' => '<p>Anyone who wants to support our town can contribute weekly or monthly via our CBE bank account or Telebirr number. All contributions go straight to our neighbor aid pool.</p>',
                'is_visible' => true,
                'sort_order' => 5,
            ],
        ];

        Faq::query()->delete();
        foreach ($faqs as $faq) {
            Faq::query()->create($faq);
        }

        // 8. Donation Methods
        $methods = [
            [
                'label' => 'Commercial Bank of Ethiopia (CBE)',
                'account_name' => 'Aim Charity Association (25 Friends Town Group)',
                'account_number' => '1000-0000-0000-0000',
                'instructions' => 'Add "Weekly Friend Donation" and your name in the transfer reason. 100% directly purchases food, clothes, and medical aid for town neighbors.',
                'logo' => 'placeholders/donation/cbe-logo.svg',
                'qr_image' => 'placeholders/donation/qr-cbe.svg',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Telebirr Mobile Money',
                'account_name' => 'Aim Charity Town Friends',
                'account_number' => '+251 91 123 4567',
                'instructions' => 'Send your weekly gift directly via Telebirr with note "Town Food & Clothes". Instant confirmation sent to our coordinator.',
                'logo' => 'placeholders/donation/telebirr-logo.svg',
                'qr_image' => 'placeholders/donation/qr-telebirr.svg',
                'is_visible' => true,
                'sort_order' => 2,
            ],
        ];

        DonationMethod::query()->delete();
        foreach ($methods as $method) {
            DonationMethod::query()->create($method);
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

        Partner::query()->delete();
        foreach ($partners as $partner) {
            Partner::query()->create($partner);
        }

        // 10. News Posts
        $news = [
            [
                'title' => 'Delivered Weekly Food Baskets to 42 Town Elder Households',
                'slug' => 'delivered-weekly-food-baskets-to-town-elders',
                'excerpt' => 'Our 25 members completed Sunday deliveries of teff, cooking oil, and flour to elderly neighbors across town.',
                'body' => '<p>This Sunday marks another week of dedicated service for Aim Charity. Every Sunday, our group of 25 friends visits the homes of elderly neighbors and struggling families with bags of teff flour, cooking oil, lentils, and basic necessities.</p><p>We want to thank every friend and neighbor whose weekly contributions made this consistency possible. In our small town, consistency is everything. Our elders know that every Sunday morning, a knock on their door means breakfast, respect, and love from their community.</p>',
                'cover_image' => 'placeholders/news/news-1.svg',
                'published_at' => Carbon::now()->subDays(3),
                'is_visible' => true,
            ],
            [
                'title' => 'Winter Clothing & Blanket Drive Warms Local Children and Elders',
                'slug' => 'winter-clothing-blanket-drive-warms-town',
                'excerpt' => 'Thanks to weekly pooled contributions, we distributed warm sweaters, jackets, and blankets to town residents before the cold rains.',
                'body' => '<p>Cold highland nights can be difficult for our elders and young students. Ahead of the cold rainy season, our 25 members collected and purchased warm sweaters, durable shoes, and heavy blankets for town families in need.</p><p>Seeing these children and elders warm and comfortable is a testament to what friends can do when we pool our efforts. Thank you to everyone who contributed to our clothing drive.</p>',
                'cover_image' => 'placeholders/news/news-2.svg',
                'published_at' => Carbon::now()->subDays(12),
                'is_visible' => true,
            ],
        ];

        NewsPost::query()->delete();
        foreach ($news as $post) {
            NewsPost::query()->create($post);
        }

        // 11. Team Members (Founding Friends Among the 25 Members)
        $team = [
            [
                'name' => 'Dawit Mekonnen',
                'role' => 'Founding Friend & Food Logistics',
                'photo' => 'placeholders/team/team-1.svg',
                'bio' => 'One of the 25 friends who initiated our weekly giving. Coordinates weekly food purchases and Sunday grocery deliveries.',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bethlehem Tadesse',
                'role' => 'Elder Welfare & Family Visits',
                'photo' => 'placeholders/team/team-2.svg',
                'bio' => 'Coordinates neighbor visits among our 25 members, ensuring elderly neighbors and single mothers receive food and clothing support.',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Yonas Girma',
                'role' => 'Weekly Accounts & Transparency',
                'photo' => 'placeholders/team/team-3.svg',
                'bio' => 'Reconciles weekly Telebirr and bank donations, verifies merchant receipts, and publishes open records to our 25 members group chat.',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Selamawit Abebe',
                'role' => 'Clothing & Local Supplies Lead',
                'photo' => 'placeholders/team/team-4.svg',
                'bio' => 'Manages seasonal clothing and blanket drives, student uniform fittings, and emergency local medicine support.',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        TeamMember::query()->delete();
        foreach ($team as $member) {
            TeamMember::query()->create($member);
        }

        Site::flushCache();
    }
}
