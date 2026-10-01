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
                'focus_area' => 'Food Support & Urban Relief',
                'founded_year' => '2019',
                'logo' => 'placeholders/groups/group-logo-1.svg',
                'photo' => 'placeholders/groups/group-photo-1.svg',
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
                'focus_area' => 'Elders & Rural Drought Relief',
                'founded_year' => '2018',
                'logo' => 'placeholders/groups/group-logo-2.svg',
                'photo' => 'placeholders/groups/group-photo-2.svg',
                'website_url' => 'https://example.org/oromia-elders',
                'social_links' => [
                    ['platform' => 'Telegram', 'url' => 'https://t.me/example'],
                ],
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Amhara Health & Reconstruction Taskforce',
                'short_description' => 'Civilian rehabilitation coalition rebuilding damaged schools, healthcare posts, and grain storage in northern Ethiopia.',
                'long_description' => '<p>Operating across North Wollo and Gondar, the taskforce mobilizes engineers, teachers, and healthcare professionals to rebuild shattered civilian infrastructure, restock medical supplies, and deliver psychological trauma care to affected youth.</p>',
                'focus_area' => 'Healthcare & Shelter Reconstruction',
                'founded_year' => '2021',
                'logo' => 'placeholders/groups/group-logo-3.svg',
                'photo' => 'placeholders/groups/group-photo-3.svg',
                'website_url' => 'https://example.org/amhara-relief',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tigray Youth Solidarity Network',
                'short_description' => 'Dynamic youth volunteer collective restoring clean water access, solar power hubs, and emergency mother-and-child feeding.',
                'long_description' => '<p>Tigray Youth Solidarity connects hundreds of energized young university graduates and technicians who repair borehole water pumps, install community solar micro-grids at clinics, and organize daily nutritional porridge centers for malnourished infants.</p>',
                'focus_area' => 'Youth Action & Clean Water',
                'founded_year' => '2020',
                'logo' => 'placeholders/groups/group-logo-4.svg',
                'photo' => 'placeholders/groups/group-photo-4.svg',
                'website_url' => 'https://example.org/tigray-youth',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Sidama Women & Family Forum',
                'short_description' => 'Community-led cooperative providing micro-grants, agricultural seed banks, and maternal health transit for women in Hawassa.',
                'long_description' => '<p>Established by female agro-cooperative leaders, the forum ensures that aid directly bolsters women-headed households through zero-interest emergency loans, drought-resistant teff seeds, and dedicated transport for expectant mothers in remote kebeles.</p>',
                'focus_area' => 'Women Micro-Grants & Maternal Care',
                'founded_year' => '2019',
                'logo' => 'placeholders/groups/group-logo-5.svg',
                'photo' => 'placeholders/groups/group-photo-5.svg',
                'website_url' => 'https://example.org/sidama-women',
                'social_links' => [],
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Afar Pastoralist Care Alliance',
                'short_description' => 'Frontline mobile veterinary units, deep well maintenance, and emergency water trucking in the Danakil basin.',
                'long_description' => '<p>Navigating extreme arid conditions, this alliance delivers rapid emergency water tankers, livestock vaccines, and solar water pump parts to keep nomadic communities and their herds resilient during critical dry seasons.</p>',
                'focus_area' => 'Nomadic Resilience & Emergency Water',
                'founded_year' => '2022',
                'logo' => 'placeholders/groups/group-logo-6.svg',
                'photo' => 'placeholders/groups/group-photo-6.svg',
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

        // 2. Programs (4 items)
        $programs = [
            [
                'title' => 'Emergency Nutritional Relief & Grain Reserves',
                'icon' => 'heroicon-o-heart',
                'description' => 'Direct distribution of staple teff, wheat flour, fortified cooking oil, and baby formula to families facing acute food insecurity.',
                'image' => 'placeholders/programs/program-1.svg',
                'link_label' => 'Explore Food Operations',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Community Clean Water Wells & Boreholes',
                'icon' => 'heroicon-o-sparkles',
                'description' => 'Refurbishing broken solar pumps, digging sustainable community wells, and distributing water purification sachets across arid kebeles.',
                'image' => 'placeholders/programs/program-2.svg',
                'link_label' => 'Support Clean Water',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Maternal & Primary Healthcare Outreach',
                'icon' => 'heroicon-o-plus-circle',
                'description' => 'Mobile medical clinics staffed by coalition doctors, delivering vaccinations, prenatal screening, and essential medicines to remote communities.',
                'image' => 'placeholders/programs/program-3.svg',
                'link_label' => 'Learn About Medical Aid',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Rebuilding Schools & Vocational Workshops',
                'icon' => 'heroicon-o-academic-cap',
                'description' => 'Repairing classroom roofs, supplying textbooks, and establishing artisan carpentry and weaving workshops for displaced youth.',
                'image' => 'placeholders/programs/program-4.svg',
                'link_label' => 'Sponsor a Classroom',
                'link_url' => '#donate',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($programs as $pData) {
            Program::updateOrCreate(['title' => $pData['title']], $pData);
        }

        // 3. Impact Stats (4 items)
        $impactStats = [
            [
                'value' => '142,000',
                'suffix' => '+',
                'label' => 'Individuals Provided Direct Emergency Relief',
                'icon' => 'heroicon-o-user-group',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'value' => '6',
                'suffix' => '',
                'label' => 'Grassroots Coalitions Working Together as One',
                'icon' => 'heroicon-o-shield-check',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'value' => '85',
                'suffix' => '+',
                'label' => 'Clean Water Solar Wells Restored & Operational',
                'icon' => 'heroicon-o-sparkles',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'value' => '100',
                'suffix' => '%',
                'label' => 'Direct Grassroots Allocation & Open Auditing',
                'icon' => 'heroicon-o-check-badge',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($impactStats as $stat) {
            ImpactStat::updateOrCreate(['label' => $stat['label']], $stat);
        }

        // 4. How It Works Steps (3 items)
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
        ];

        foreach ($steps as $step) {
            Step::updateOrCreate(['title' => $step['title']], $step);
        }

        // 5. Testimonials (3 items)
        $testimonials = [
            [
                'quote' => 'Before Aim Charity coordinated the groups, different organizations would bring clothes one week and nothing the next. Now our village receives coordinated grain, clean water, and medical checkups reliably every month.',
                'author_name' => 'W/ro Aster Tesfaye',
                'author_role' => 'Community Elder & Mother',
                'author_photo' => 'placeholders/testimonials/testimonial-1.svg',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'quote' => 'Pooling our youth volunteers with the elders committee meant we could fix our broken solar well in three days instead of waiting six months for outside contractors.',
                'author_name' => 'Dr. Dawit Bekele',
                'author_role' => 'Volunteer Physician & Logistics Coordinator',
                'author_photo' => 'placeholders/testimonials/testimonial-2.svg',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'quote' => 'The radical financial transparency made our cooperative trust this initiative completely. We know every single birr sent reaches genuine families in need.',
                'author_name' => 'Fatuma Mohammed',
                'author_role' => 'Agro-Cooperative Leader, Awash Valley',
                'author_photo' => 'placeholders/testimonials/testimonial-3.svg',
                'member_group_id' => $group3,
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $tData) {
            Testimonial::updateOrCreate(['author_name' => $tData['author_name']], $tData);
        }

        // 6. Gallery Items (6 items)
        $galleryItems = [
            [
                'image' => 'placeholders/gallery/gallery-1.svg',
                'caption' => 'Volunteers loading sacks of grain for rural woreda distribution.',
                'alt_text' => 'Community volunteers packing relief grain sacks into trucks.',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'image' => 'placeholders/gallery/gallery-2.svg',
                'caption' => 'Community celebration as clean water begins flowing from restored borehole.',
                'alt_text' => 'Village children gathered around clean running borehole water tap.',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'image' => 'placeholders/gallery/gallery-3.svg',
                'caption' => 'Mobile health clinic offering free maternal and child health screenings.',
                'alt_text' => 'Coalition nurse administering health check for mothers and infants.',
                'member_group_id' => $group5,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'image' => 'placeholders/gallery/gallery-4.svg',
                'caption' => 'Elder council reviewing verified household relief registries.',
                'alt_text' => 'Elders sitting under acacia tree reviewing community beneficiary list.',
                'member_group_id' => $group2,
                'is_visible' => true,
                'sort_order' => 4,
            ],
            [
                'image' => 'placeholders/gallery/gallery-5.svg',
                'caption' => 'Young technicians installing replacement solar panels for water pumping.',
                'alt_text' => 'Youth technicians mounting solar panels on borehole roof.',
                'member_group_id' => $group4,
                'is_visible' => true,
                'sort_order' => 5,
            ],
            [
                'image' => 'placeholders/gallery/gallery-6.svg',
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

        // 7. Partners (4 items)
        $partners = [
            [
                'name' => 'Ethiopian Red Cross Society',
                'logo' => 'placeholders/partners/partner-1.svg',
                'url' => 'https://example.org/red-cross',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Addis Ababa University Community Service',
                'logo' => 'placeholders/partners/partner-2.svg',
                'url' => 'https://example.org/aau',
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Horn Humanitarian Relief Initiative',
                'logo' => 'placeholders/partners/partner-3.svg',
                'url' => 'https://example.org/horn-relief',
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Local Merchants Solidarity Council',
                'logo' => 'placeholders/partners/partner-4.svg',
                'url' => 'https://example.org/merchants',
                'is_visible' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::updateOrCreate(['name' => $partner['name']], $partner);
        }

        // 8. FAQs (5 items)
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
                'answer' => '<p>We support major Ethiopian domestic banking options (Commercial Bank of Ethiopia, Telebirr Mobile Money) with direct account numbers and QR codes, as well as bank transfers for diaspora supporters.</p>',
                'is_visible' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // 9. News Posts (3 items)
        $newsPosts = [
            [
                'title' => 'Emergency Grain Convoy Reaches 2,400 Families in North Wollo',
                'slug' => 'emergency-grain-convoy-reaches-north-wollo',
                'excerpt' => 'A joint coalition convoy coordinated by three member groups safely delivered 120 metric tons of wheat and cooking oil to drought-affected rural woredas.',
                'body' => '<p>In one of the largest collaborative operations since the founding of Aim Charity, three member groups successfully pooled logistics and transport trucks to reach isolated rural kebeles in North Wollo.</p><p>Over the course of 72 hours, verified family registries coordinated by village elders ensured that 2,400 households received essential rations containing wheat flour, split peas, iodized salt, and fortified cooking oil.</p><p>Full delivery receipts and fuel expense vouchers have been audited and uploaded to our public transparency repository.</p>',
                'cover_image' => 'placeholders/news/news-1.svg',
                'published_at' => Carbon::now()->subDays(3),
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Six Solar-Powered Water Wells Commissioned in East Shewa',
                'slug' => 'six-solar-water-wells-commissioned-east-shewa',
                'excerpt' => 'Youth volunteer technicians teamed up with rural elders to replace broken diesel pumps with clean, sustainable solar pumping arrays.',
                'body' => '<p>Clean water access has returned to six rural communities following the completion of our quarterly borehole restoration initiative.</p><p>By transitioning from expensive diesel generators to high-efficiency submersible solar pumps, community operating costs have dropped to near zero, providing permanent clean water to over 14,000 pastoralists and their herds.</p><p>Local youth committees have been trained and equipped with spare parts toolkits to ensure rapid on-site maintenance.</p>',
                'cover_image' => 'placeholders/news/news-2.svg',
                'published_at' => Carbon::now()->subDays(10),
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Q3 Financial Transparency & Community Audit Report Published',
                'slug' => 'q3-financial-transparency-audit-report',
                'excerpt' => 'Our independent audit committee releases the complete itemized revenue and expenditure balance sheet for the third quarter.',
                'body' => '<p>In keeping with our charter requirement of radical openness, the Aim Charity Steering Committee has released the comprehensive financial audit for the third quarter.</p><p>Total contributions from domestic mobile banking, bank deposits, and diaspora supporters totaled 14.8M ETB, with 94.6% deployed directly into goods and direct services on the ground.</p><p>The complete digital spreadsheet and signed auditor certification are freely downloadable for all donors and civic observers.</p>',
                'cover_image' => 'placeholders/news/news-3.svg',
                'published_at' => Carbon::now()->subDays(18),
                'is_visible' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($newsPosts as $post) {
            NewsPost::updateOrCreate(['slug' => $post['slug']], $post);
        }

        // 10. Donation Methods (2 methods with fake demo numbers clearly marked)
        $donationMethods = [
            [
                'label' => 'Commercial Bank of Ethiopia (CBE)',
                'account_name' => 'Aim Charity Relief Coalition [DEMO / PLACEHOLDER]',
                'account_number' => '1000-0000-0000-0000 [DEMO / PLACEHOLDER]',
                'instructions' => 'Finfinee Branch [DEMO]. Include your full name or phone number as transfer reference.',
                'logo' => 'placeholders/donate/cbe-logo.svg',
                'qr_image' => 'placeholders/donate/cbe-qr.svg',
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'label' => 'Telebirr Mobile Money',
                'account_name' => 'Aim Charity Coalition Merchant [DEMO / PLACEHOLDER]',
                'account_number' => '0900-00-00-00 [DEMO / PLACEHOLDER]',
                'instructions' => 'Pay via Merchant ID: AIM12345 [DEMO] or transfer directly via Telebirr SuperApp.',
                'logo' => 'placeholders/donate/telebirr-logo.svg',
                'qr_image' => 'placeholders/donate/telebirr-qr.svg',
                'is_visible' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($donationMethods as $method) {
            DonationMethod::updateOrCreate(['label' => $method['label']], $method);
        }

        // 11. Team Members (4 items)
        $team = [
            [
                'name' => 'Ato Yohannes Hailemariam',
                'role' => 'Steering Committee Chairperson',
                'photo' => 'placeholders/team/team-1.svg',
                'bio' => 'Over 20 years leading humanitarian civil society initiatives and coordinating community alliances across regional Ethiopia.',
                'member_group_id' => $group1,
                'is_visible' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sister Rahel Tadesse',
                'role' => 'Logistics & Distribution Lead',
                'photo' => 'placeholders/team/team-2.svg',
                'bio' => 'Logistics strategist specializing in rapid convoy routing, warehouse security, and disaster response supply chains.',
                'member_group_id' => $group3,
                'is_visible' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Dr. Elias Gidey',
                'role' => 'Director of Healthcare Operations',
                'photo' => 'placeholders/team/team-3.svg',
                'bio' => 'Physician and public health practitioner managing mobile vaccination teams and maternal health caravans.',
                'member_group_id' => $group2,
                'is_visible' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'W/ro Selamawit Desta',
                'role' => 'Community Liaison & Audit Lead',
                'photo' => 'placeholders/team/team-4.svg',
                'bio' => 'Dedicated advocate ensuring open ledger reconciliations, elder consultations, and community accountability.',
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
