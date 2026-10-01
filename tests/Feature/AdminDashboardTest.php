<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_view_custom_dashboard_with_quick_links_and_help_widget(): void
    {
        $this->seed();

        $admin = User::first() ?? User::factory()->create([
            'email' => 'admin@aimcharity.org',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('Quick Actions');
        $response->assertSee('Edit Hero');
        $response->assertSee('Add Member Group');
        $response->assertSee('View Live Site');
        $response->assertSee('Page Sections');
        $response->assertSee('Site Settings');
        $response->assertSee('How to Edit the Aim Charity Website');
        $response->assertSee('Global Settings');
        $response->assertSee('Repeatable Content');
    }
}
