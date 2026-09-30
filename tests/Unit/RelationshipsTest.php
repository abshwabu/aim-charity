<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\GalleryItem;
use App\Models\MemberGroup;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_group_has_many_relationships(): void
    {
        $group = MemberGroup::factory()->create();

        $testimonial = Testimonial::factory()->create(['member_group_id' => $group->id]);
        $galleryItem = GalleryItem::factory()->create(['member_group_id' => $group->id]);
        $teamMember = TeamMember::factory()->create(['member_group_id' => $group->id]);
        $volunteer = VolunteerApplication::factory()->create(['member_group_id' => $group->id]);

        $this->assertTrue($group->testimonials->contains($testimonial));
        $this->assertTrue($group->galleryItems->contains($galleryItem));
        $this->assertTrue($group->teamMembers->contains($teamMember));
        $this->assertTrue($group->volunteerApplications->contains($volunteer));

        $this->assertSame($group->id, $testimonial->memberGroup->id);
        $this->assertSame($group->id, $galleryItem->memberGroup->id);
        $this->assertSame($group->id, $teamMember->memberGroup->id);
        $this->assertSame($group->id, $volunteer->memberGroup->id);
    }
}
