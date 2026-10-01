<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\MemberGroups\Pages\CreateMemberGroup;
use App\Filament\Resources\MemberGroups\Pages\EditMemberGroup;
use App\Filament\Resources\MemberGroups\Pages\ListMemberGroups;
use App\Filament\Resources\NewsPosts\Pages\CreateNewsPost;
use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\MemberGroup;
use App\Models\NewsletterSubscriber;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\VolunteerApplication;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class RepeatableContentResourcesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminUserSeeder::class);
        $this->admin = User::query()->firstOrFail();
    }

    public function test_guests_cannot_access_content_resources(): void
    {
        $endpoints = [
            '/admin/member-groups',
            '/admin/programs',
            '/admin/impact-stats',
            '/admin/testimonials',
            '/admin/gallery-items',
            '/admin/partners',
            '/admin/faqs',
            '/admin/news-posts',
            '/admin/steps',
            '/admin/donation-methods',
            '/admin/team-members',
        ];

        foreach ($endpoints as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
    }

    public function test_authenticated_admin_can_view_all_11_resource_index_pages(): void
    {
        $this->actingAs($this->admin);

        $resources = [
            '/admin/member-groups' => 'Member Groups',
            '/admin/programs' => 'Programs',
            '/admin/impact-stats' => 'Impact Stats',
            '/admin/testimonials' => 'Testimonials',
            '/admin/gallery-items' => 'Gallery Items',
            '/admin/partners' => 'Partners',
            '/admin/faqs' => 'FAQs',
            '/admin/news-posts' => 'News Posts',
            '/admin/steps' => 'Process Steps',
            '/admin/donation-methods' => 'Donation Methods',
            '/admin/team-members' => 'Team Members',
        ];

        foreach ($resources as $url => $label) {
            $response = $this->get($url);
            $response->assertOk();
            $response->assertSee($label);
        }
    }

    public function test_member_group_resource_can_create_record(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateMemberGroup::class)
            ->fillForm([
                'name' => 'Addis Food Aid Initiative',
                'focus_area' => 'Food Security & Emergency Relief',
                'founded_year' => '2019',
                'website_url' => 'https://addisfoodaid.org',
                'short_description' => 'Providing daily meals to families across Addis Ababa.',
                'long_description' => '<p>A grassroot collective formed by community leaders in 2019.</p>',
                'is_visible' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('member_groups', [
            'name' => 'Addis Food Aid Initiative',
            'focus_area' => 'Food Security & Emergency Relief',
            'founded_year' => '2019',
            'is_visible' => true,
        ]);
    }

    public function test_member_group_resource_can_edit_record(): void
    {
        $this->actingAs($this->admin);

        $group = MemberGroup::factory()->create([
            'name' => 'Original Name',
            'focus_area' => 'Old Focus',
        ]);

        Livewire::test(EditMemberGroup::class, ['record' => $group->id])
            ->fillForm([
                'name' => 'Updated Coalition Partner',
                'focus_area' => 'Healthcare Access',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $group->refresh();
        $this->assertSame('Updated Coalition Partner', $group->name);
        $this->assertSame('Healthcare Access', $group->focus_area);
    }

    public function test_member_group_table_displays_related_counts(): void
    {
        $this->actingAs($this->admin);

        $group = MemberGroup::factory()->create(['name' => 'Shelter Foundation Ethiopia']);

        Testimonial::factory()->count(2)->create(['member_group_id' => $group->id]);
        GalleryItem::factory()->count(3)->create(['member_group_id' => $group->id]);

        $response = $this->get('/admin/member-groups');
        $response->assertOk();
        $response->assertSee('Shelter Foundation Ethiopia');
        $response->assertSee('2'); // Testimonials count badge
        $response->assertSee('3'); // Gallery count badge
    }

    public function test_member_group_can_be_reordered(): void
    {
        $this->actingAs($this->admin);

        $groupA = MemberGroup::factory()->create(['name' => 'Group A', 'sort_order' => 1]);
        $groupB = MemberGroup::factory()->create(['name' => 'Group B', 'sort_order' => 2]);

        Livewire::test(ListMemberGroups::class)
            ->call('reorderTable', [(string) $groupB->id, (string) $groupA->id]);

        $groupA->refresh();
        $groupB->refresh();

        $this->assertSame(1, $groupB->sort_order);
        $this->assertSame(2, $groupA->sort_order);
    }

    public function test_testimonial_resource_can_create_and_link_to_member_group(): void
    {
        $this->actingAs($this->admin);

        $group = MemberGroup::factory()->create(['name' => 'Mekelle Youth Association']);

        Livewire::test(CreateTestimonial::class)
            ->fillForm([
                'quote' => 'Aim Charity has been a vital catalyst for our local feeding program.',
                'author_name' => 'Abebe Bikila',
                'author_role' => 'Community Leader',
                'member_group_id' => $group->id,
                'is_visible' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('testimonials', [
            'author_name' => 'Abebe Bikila',
            'author_role' => 'Community Leader',
            'member_group_id' => $group->id,
        ]);
    }

    public function test_testimonial_resource_can_edit_record(): void
    {
        $this->actingAs($this->admin);

        $testimonial = Testimonial::factory()->create([
            'author_name' => 'Initial Name',
            'quote' => 'Initial quote',
        ]);

        Livewire::test(EditTestimonial::class, ['record' => $testimonial->id])
            ->fillForm([
                'author_name' => 'Revised Author Name',
                'quote' => 'Updated quote text for the website.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $testimonial->refresh();
        $this->assertSame('Revised Author Name', $testimonial->author_name);
        $this->assertSame('Updated quote text for the website.', $testimonial->quote);
    }

    public function test_bulk_actions_show_and_hide_records(): void
    {
        $this->actingAs($this->admin);

        $item1 = Testimonial::factory()->create(['is_visible' => true]);
        $item2 = Testimonial::factory()->create(['is_visible' => true]);

        Livewire::test(ListTestimonials::class)
            ->call('mountTableBulkAction', 'hide', [(string) $item1->id, (string) $item2->id])
            ->call('callMountedTableBulkAction');

        $item1->refresh();
        $item2->refresh();

        $this->assertFalse($item1->is_visible);
        $this->assertFalse($item2->is_visible);

        Livewire::test(ListTestimonials::class)
            ->call('mountTableBulkAction', 'show', [(string) $item1->id, (string) $item2->id])
            ->call('callMountedTableBulkAction');

        $item1->refresh();
        $item2->refresh();

        $this->assertTrue($item1->is_visible);
        $this->assertTrue($item2->is_visible);
    }

    public function test_media_cleanup_on_replace_and_delete(): void
    {
        Storage::fake('public');

        $oldFile = 'member-groups/logos/old-logo.webp';
        $newFile = 'member-groups/logos/new-logo.webp';

        Storage::disk('public')->put($oldFile, 'fake-old-image-bytes');
        Storage::disk('public')->put($newFile, 'fake-new-image-bytes');

        $this->assertTrue(Storage::disk('public')->exists($oldFile));
        $this->assertTrue(Storage::disk('public')->exists($newFile));

        $group = MemberGroup::factory()->create(['logo' => $oldFile]);

        // Updating logo should delete old file from storage
        $group->update(['logo' => $newFile]);

        $this->assertFalse(Storage::disk('public')->exists($oldFile));
        $this->assertTrue(Storage::disk('public')->exists($newFile));

        // Deleting record should delete current file from storage
        $group->delete();

        $this->assertFalse(Storage::disk('public')->exists($newFile));
    }

    public function test_news_post_auto_slugs_title(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateNewsPost::class)
            ->fillForm([
                'title' => 'New Regional Distribution Hub Launched',
            ])
            ->assertSchemaStateSet([
                'slug' => 'new-regional-distribution-hub-launched',
            ]);
    }

    public function test_submission_stats_widget_calculates_counts(): void
    {
        ContactMessage::factory()->create(['read_at' => null]);
        ContactMessage::factory()->create(['read_at' => null]);
        ContactMessage::factory()->create(['read_at' => now()]);

        VolunteerApplication::factory()->create(['status' => 'pending']);
        VolunteerApplication::factory()->create(['status' => 'approved']);

        NewsletterSubscriber::factory()->count(5)->create();
        MemberGroup::factory()->count(4)->create(['is_visible' => true]);

        $this->actingAs($this->admin);

        $response = $this->get('/admin');
        $response->assertOk();
        $response->assertSee('Unread Inquiries');
        $response->assertSee('Volunteer Applications');
        $response->assertSee('Newsletter Subscribers');
        $response->assertSee('Coalition Members');
    }
}
