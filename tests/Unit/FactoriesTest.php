<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\ContactMessage;
use App\Models\DonationMethod;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImpactStat;
use App\Models\MemberGroup;
use App\Models\NewsletterSubscriber;
use App\Models\NewsPost;
use App\Models\PageSection;
use App\Models\Partner;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\Step;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_model_factories_create_valid_records(): void
    {
        $group = MemberGroup::factory()->create();
        $this->assertDatabaseHas('member_groups', ['id' => $group->id]);

        $factories = [
            User::factory(),
            SiteSetting::factory(),
            PageSection::factory(),
            Program::factory(),
            ImpactStat::factory(),
            Testimonial::factory()->for($group),
            GalleryItem::factory()->for($group),
            Partner::factory(),
            Faq::factory(),
            NewsPost::factory(),
            Step::factory(),
            DonationMethod::factory(),
            TeamMember::factory()->for($group),
            ContactMessage::factory(),
            VolunteerApplication::factory()->for($group),
            NewsletterSubscriber::factory(),
        ];

        foreach ($factories as $factory) {
            $model = $factory->create();
            $this->assertNotNull($model->getKey());
            $this->assertTrue($model->exists);
        }
    }
}
