<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\SiteSettingSeeder;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_returns_successful_response(): void
    {
        $this->seed(SiteSettingSeeder::class);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Aim Charity');
    }

    public function test_admin_login_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('Aim Charity');
    }

    public function test_admin_user_can_authenticate_into_admin_panel(): void
    {
        $this->seed(AdminUserSeeder::class);

        $adminEmail = (string) env('ADMIN_EMAIL', 'admin@aimcharity.org');

        $user = User::query()->where('email', $adminEmail)->firstOrFail();

        $this->actingAs($user);

        $response = $this->get('/admin');

        $response->assertOk();
    }

    public function test_admin_user_can_log_in_via_login_form(): void
    {
        $this->seed(AdminUserSeeder::class);

        $adminEmail = (string) env('ADMIN_EMAIL', 'admin@aimcharity.org');
        $adminPassword = (string) env('ADMIN_PASSWORD', 'password');

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $adminEmail,
                'password' => $adminPassword,
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticated();
    }
}
