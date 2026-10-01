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
        // 1. Member Groups (The Coalition - "Group of Groups")
        $groups = [
            [
                'name' => 'Addis Mutual Aid Association',
                'short_description' => 'Grassroots mutual assistance providing rapid food distribution, emergency elder support, and neighborhood solidarity in Addis Ababa.',
                'long_description' => '<p>Founded by neighborhood community organizers, Addis Mutual Aid brings together volunteers and local merchants across multiple sub-cities to provide dignified, immediate relief to families facing acute hardship, medical emergencies, and job displacement.</p>',
                'focus_area' => 'Urban Emergency Relief & Food Support',
                'founded_year' => '2019',
                'photo' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/addis-mutual-aid',
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/example'],
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com/example'],
                ],
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Oromia Community Elders Committee',
                'short_description' => 'Traditional Gadaa-inspired community councils coordinating rural drought assistance, livestock protection, and family sustenance.',
                'long_description' => '<p>Guided by revered community elders and Gadaa principles of collective responsibility, this initiative manages pastoralist emergency supplies, protects communal grazing safety nets, and facilitates conflict-sensitive aid distribution throughout East Shewa and Arsi.</p>',
                'focus_area' => 'Rural Drought & Pastoralist Relief',
                'founded_year' => '2018',
                'photo' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/oromia-elders',
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/example'],
                ],
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Amhara Relief & Reconstruction Taskforce',
                'short_description' => 'Civilian rehabilitation coalition rebuilding damaged schools, healthcare posts, and grain storage in northern Ethiopia.',
                'long_description' => '<p>Operating across North Wollo and Gondar, the taskforce mobilizes engineers, teachers, and healthcare professionals to rebuild shattered civilian infrastructure, restock medical supplies, and deliver psychological trauma care to affected youth.</p>',
                'focus_area' => 'Reconstruction & Healthcare Rehabilitation',
                'founded_year' => '2021',
                'photo' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/amhara-relief',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tigray Youth Solidarity Network',
                'short_description' => 'Dynamic youth volunteer collective restoring clean water access, solar power hubs, and emergency mother-and-child feeding.',
                'long_description' => '<p>Tigray Youth Solidarity connects hundreds of energized young university graduates and technicians who repair borehole water pumps, install community solar micro-grids at clinics, and organize daily nutritional porridge centers for malnourished infants.</p>',
                'focus_area' => 'Water Access & Infant Nutrition',
                'founded_year' => '2020',
                'photo' => 'https://images.unsplash.com/photo-1541802645635-11f2286a7482?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/tigray-youth',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sidama Women Empowerment Forum',
                'short_description' => 'Community-led cooperative providing micro-grants, agricultural seed banks, and maternal health transit for women in Hawassa.',
                'long_description' => '<p>Established by female agro-cooperative leaders, the forum ensures that aid directly bolsters women-headed households through zero-interest emergency loans, drought-resistant teff seeds, and dedicated transport for expectant mothers in remote kebeles.</p>',
                'focus_area' => 'Maternal Care & Economic Resilience',
                'founded_year' => '2019',
                'photo' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/sidama-women',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Afar Pastoralist Care Alliance',
                'short_description' => 'Frontline mobile veterinary units, deep well maintenance, and emergency water trucking in the Danakil basin.',
                'long_description' => '<p>Navigating extreme arid conditions, this alliance delivers rapid emergency water tankers, livestock vaccines, and solar water pump parts to keep nomadic communities and their herds resilient during critical dry seasons.</p>',
                'focus_area' => 'Water Trucking & Nomadic Support',
                'founded_year' => '2022',
                'photo' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=800&q=80',
                'website_url' => 'https://example.org/afar-care',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        $createdGroups = [];
        foreach ($groups as $gData) {
            $createdGroups[] = MemberGroup::updateOrCreate(
                ['name' => $gData['name']],
                $gData
            );
        }

        $group1 = $createdGroups[0]->id;
        $group2 = $createdGroups[1]->id;
        $group3 = $createdGroups[2]->id;
        $group4 = $createdGroups[3]->id;
        $group5 = $createdGroups[4]->id;

        // 2. Programs
        $programs = [
            [
                'title' => 'Emergency Nutritional Relief & Grain Reserves',
                'icon' => 'heroicon-o-heart',
                'description' => 'Direct distribution of staple teff, wheat flour, fortified cooking oil, and baby formula to families facing acute food insecurity.',
                'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Explore Food Operations',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Community Clean Water Wells & Boreholes',
                'icon' => 'heroicon-o-sparkles',
                'description' => 'Refurbishing broken solar pumps, digging sustainable community wells, and distributing water purification sachets across arid kebeles.',
                'image' => 'https://images.unsplash.com/photo-1541802645635-11f2286a7482?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Support Clean Water',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Maternal & Primary Healthcare Outreach',
                'icon' => 'heroicon-o-plus-circle',
                'description' => 'Mobile medical clinics staffed by coalition doctors, delivering vaccinations, prenatal screening, and essential medicines to remote communities.',
                'image' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Learn About Medical Aid',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Rebuilding Schools & Vocational Workshops',
                'icon' => 'heroicon-o-academic-cap',
                'description' => 'Repairing classroom roofs, supplying textbooks, and establishing artisan carpentry and weaving workshops for displaced youth.',
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Sponsor a Classroom',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Grassroots Micro-Grants for Women Farmers',
                'icon' => 'heroicon-o-banknotes',
                'description' => 'Providing seed capital, climate-resilient teff seeds, and irrigation tools directly to women-headed agrarian cooperatives.',
                'image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Invest in Women',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'title' => 'Rapid Winter Clothing & Shelter Kits',
                'icon' => 'heroicon-o-home-modern',
                'description' => 'Emergency weather-proof tents, wool blankets, and sleeping mats distributed within 48 hours of sudden displacement or weather shocks.',
                'image' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?auto=format&fit=crop&w=800&q=80',
                'link_label' => 'Fund Shelter Kits',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($programs as $pData) {
            Program::updateOrCreate(['title' => $pData['title']], $pData);
        }

        // 3. Impact Stats
        $impactStats = [
            [
                'value' => '65,000',
                'suffix' => '+',
                'label' => 'Individuals Provided Direct Emergency Relief',
                'icon' => 'heroicon-o-user-group',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'value' => '100',
                'suffix' => '%',
                'label' => 'Grassroots Allocation Directly Reaching Communities',
                'icon' => 'heroicon-o-shield-check',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'value' => '42',
                'suffix' => '',
                'label' => 'Clean Water Solar Wells Restored & Operational',
                'icon' => 'heroicon-o-sparkles',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'value' => '12',
                'suffix' => ' Woredas',
                'label' => 'Active Coordination Across Urban & Rural Regions',
                'icon' => 'heroicon-o-map-pin',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($impactStats as $stat) {
            ImpactStat::updateOrCreate(['label' => $stat['label']], $stat);
        }

        // 4. How It Works Steps
        $steps = [
            [
                'title' => 'Grassroots Need Verification',
                'description' => 'Member groups embedded in local kebeles evaluate and audit urgent needs directly with community elders, preventing duplication and political bias.',
                'icon' => 'heroicon-o-magnifying-glass',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Coalition Resource Pooling',
                'description' => 'Donations, transport trucks, medical supplies, and grain reserves are centrally tracked on our shared ledger for maximum cost efficiency.',
                'icon' => 'heroicon-o-arrows-pointing-in',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Dignified Direct Distribution',
                'description' => 'Aid is distributed directly to registered family heads without middlemen, ensuring respect, security, and equal treatment.',
                'icon' => 'heroicon-o-truck',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Public Ledger & Impact Audits',
                'description' => 'Every single Ethiopian Birr collected is reconciled against signed receipts and published openly in monthly transparent reports.',
                'icon' => 'heroicon-o-document-check',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($steps as $step) {
            Step::updateOrCreate(['title' => $step['title']], $step);
        }

        // 5. Testimonials
        $testimonials = [
            [
                'quote' => 'Before Aim Charity coordinated the groups, different organizations would bring clothes one week and nothing the next. Now our village receives coordinated grain, clean water, and medical checkups reliably every month.',
                'author_name' => 'W/ro Almaz Tadesse',
                'author_role' => 'Community Elder & Grandmother',
                'author_photo' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'quote' => 'Pooling our youth volunteers with the elders committee meant we could fix our broken solar well in three days instead of waiting six months for outside contractors.',
                'author_name' => 'Dawit Mengistu',
                'author_role' => 'Youth Field Logistics Coordinator',
                'author_photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'quote' => 'The radical financial transparency made our diaspora association trust this initiative completely. We know every single dollar sent reaches genuine families in need.',
                'author_name' => 'Dr. Selamawit Bekele',
                'author_role' => 'Diaspora Medical Aid Supporter',
                'author_photo' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=300&q=80',
                'member_group_id' => $group3,
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $tData) {
            Testimonial::updateOrCreate(['author_name' => $tData['author_name']], $tData);
        }

        // 6. Gallery Items
        $galleryItems = [
            [
                'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Volunteers loading sacks of grain for rural woreda distribution.',
                'alt_text' => 'Community volunteers packing relief grain sacks into trucks.',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1541802645635-11f2286a7482?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Community celebration as clean water begins flowing from restored borehole.',
                'alt_text' => 'Village children gathered around clean running borehole water tap.',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Mobile health clinic offering free maternal and child health screenings.',
                'alt_text' => 'Coalition nurse administering health check for mothers and infants.',
                'member_group_id' => $group5,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Elder council reviewing verified household relief registries.',
                'alt_text' => 'Elders sitting under acacia tree reviewing community beneficiary list.',
                'member_group_id' => $group2,
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Young technicians installing replacement solar panels for water pumping.',
                'alt_text' => 'Youth technicians mounting solar panels on borehole roof.',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                'caption' => 'Distributing school materials and notebooks to primary students.',
                'alt_text' => 'Schoolchildren proudly holding new notebooks and pencils.',
                'member_group_id' => $group3,
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($galleryItems as $item) {
            GalleryItem::updateOrCreate(['caption' => $item['caption']], $item);
        }

        // 7. Partners
        $partners = [
            [
                'name' => 'Ethiopian Red Cross Society',
                'url' => 'https://example.org/red-cross',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Addis Ababa University Community Service Office',
                'url' => 'https://example.org/aau',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Ethiopian Medical Association',
                'url' => 'https://example.org/ema',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'WaterAid Ethiopia',
                'url' => 'https://example.org/wateraid',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Association of Ethiopian Civil Society Organizations',
                'url' => 'https://example.org/aecso',
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Ethiopian Diaspora Trust Fund Network',
                'url' => 'https://example.org/edtf',
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(['name' => $partner['name']], $partner);
        }

        // 8. FAQs
        $faqs = [
            [
                'question' => 'What makes Aim Charity different from conventional charities?',
                'answer' => '<p>Aim Charity is not a top-down bureaucracy. We are a <strong>coalition of local, independent grassroots organizations</strong> already operating in Ethiopian neighborhoods and rural woredas. By sharing logistics, auditing needs together, and coordinating deliveries, we eliminate duplicate overhead and guarantee that every birr reaches people directly.</p>',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'How can donors verify that funds are spent transparently?',
                'answer' => '<p>We publish itemized monthly financial reports detailing every expenditure, from grain procurement to logistics fuel. In addition, independent steering committee auditors and community elders co-sign every distribution log before reports are posted publicly.</p>',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'How are beneficiary families selected and registered?',
                'answer' => '<p>Recipients are identified directly by local neighborhood committees and community elders based solely on verifiable vulnerability criteria, such as displaced households, female-headed families, disabled elders, and severely malnourished children.</p>',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'Can a community organization join the Aim Charity coalition?',
                'answer' => '<p>Yes. Registered grassroots civil society associations, mutual aid cooperatives, and elder committees can apply through our contact form. Our steering committee conducts on-site due diligence and reviews community references before membership approval.</p>',
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'What payment methods can I use to donate?',
                'answer' => '<p>We support all major Ethiopian domestic banking options (Commercial Bank of Ethiopia, Awash Bank, Bank of Abyssinia, Telebirr) with direct account numbers and QR codes, as well as SWIFT international wire transfers for our diaspora supporters.</p>',
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'question' => 'Can I volunteer skills other than manual labor?',
                'answer' => '<p>Absolutely. We continuously seek medical doctors, civil engineers, accountants, graphic designers, software developers, and translators. Fill out our volunteer application below to specify your availability and skills.</p>',
                'is_visible' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // 9. News Posts
        $newsPosts = [
            [
                'title' => 'Emergency Grain Convoy Reaches 2,400 Families in North Wollo',
                'slug' => 'emergency-grain-convoy-reaches-north-wollo',
                'excerpt' => 'A joint coalition convoy coordinated by three member groups safely delivered 120 metric tons of wheat and cooking oil to drought-affected rural woredas.',
                'body' => '<p>In one of the largest collaborative operations since the founding of Aim Charity, three member groups successfully pooled logistics and transport trucks to reach isolated rural kebeles in North Wollo.</p><p>Over the course of 72 hours, verified family registries coordinated by village elders ensured that 2,400 households received essential rations containing wheat flour, split peas, iodized salt, and fortified cooking oil.</p><p>Full delivery receipts and fuel expense vouchers have been audited and uploaded to our public transparency repository.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
                'published_at' => Carbon::now()->subDays(3),
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Six Solar-Powered Water Wells Commissioned in East Shewa',
                'slug' => 'six-solar-water-wells-commissioned-east-shewa',
                'excerpt' => 'Youth volunteer technicians teamed up with rural elders to replace broken diesel pumps with clean, sustainable solar pumping arrays.',
                'body' => '<p>Clean water access has returned to six rural communities following the completion of our quarterly borehole restoration initiative.</p><p>By transitioning from expensive diesel generators to high-efficiency submersible solar pumps, community operating costs have dropped to near zero, providing permanent clean water to over 14,000 pastoralists and their herds.</p><p>Local youth committees have been trained and equipped with spare parts toolkits to ensure rapid on-site maintenance.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1541802645635-11f2286a7482?auto=format&fit=crop&w=800&q=80',
                'published_at' => Carbon::now()->subDays(10),
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Q3 Financial Transparency & Community Audit Report Published',
                'slug' => 'q3-financial-transparency-audit-report',
                'excerpt' => 'Our independent audit committee releases the complete itemized revenue and expenditure balance sheet for the third quarter.',
                'body' => '<p>In keeping with our charter requirement of radical openness, the Aim Charity Steering Committee has released the comprehensive financial audit for the third quarter.</p><p>Total contributions from domestic mobile banking, bank deposits, and diaspora supporters totaled 14.8M ETB, with 94.6% deployed directly into goods and direct services on the ground.</p><p>The complete digital spreadsheet and signed auditor certification are freely downloadable for all donors and civic observers.</p>',
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80',
                'published_at' => Carbon::now()->subDays(18),
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($newsPosts as $post) {
            NewsPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        // 10. Donation Methods
        $donationMethods = [
            [
                'label' => 'Commercial Bank of Ethiopia (CBE)',
                'account_name' => 'Aim Charity Coalition Relief Account',
                'account_number' => '1000456789123',
                'instructions' => 'Include your full name and phone number as reference for an instant SMS receipt verification.',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Telebirr SuperApp',
                'account_name' => 'Aim Charity Coalition Merchant',
                'account_number' => '+251911002233',
                'instructions' => 'Pay via Merchant ID or transfer directly from your Telebirr wallet with zero transaction fees.',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'label' => 'Awash Bank',
                'account_name' => 'Aim Charity Emergency Fund',
                'account_number' => '0132087654321',
                'instructions' => 'Available across all Awash Bank branches nationwide or via Awash Mobile Banking transfer.',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'label' => 'Bank of Abyssinia',
                'account_name' => 'Aim Charity Grassroots Solidarity',
                'account_number' => '894512340001',
                'instructions' => 'Direct transfer or mobile BoA App deposit. SWIFT transfers accepted for diaspora contributions.',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($donationMethods as $method) {
            DonationMethod::updateOrCreate(['account_number' => $method['account_number']], $method);
        }

        // 11. Team Members
        $team = [
            [
                'name' => 'Ato Yohannes Hailemariam',
                'role' => 'Steering Committee Chairperson',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Over 20 years leading humanitarian civil society initiatives and coordinating community alliances across regional Ethiopia.',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'W/ro Bethlehem Girma',
                'role' => 'Head of Field Logistics & Operations',
                'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Logistics strategist specializing in rapid convoy routing, warehouse security, and disaster response supply chains.',
                'member_group_id' => $group3,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Dr. Abdi Mohammed',
                'role' => 'Director of Healthcare Outreach',
                'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Physician and public health practitioner managing mobile vaccination teams and maternal health caravans.',
                'member_group_id' => $group2,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tewodros Kassaye',
                'role' => 'Chief Financial Auditor & Transparency Lead',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'bio' => 'Certified public accountant dedicated to ensuring open ledger reconciliations and community expenditure audits.',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($team as $member) {
            TeamMember::updateOrCreate(['name' => $member['name']], $member);
        }

        Site::flushCache();
    }
}
