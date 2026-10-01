<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteAuditCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_audit_command_passes_successfully(): void
    {
        $this->seed();

        $this->artisan('site:audit')
            ->expectsOutputToContain('AIM CHARITY FRONTEND EDITABILITY AUDIT')
            ->expectsOutputToContain('Zero literal text nodes detected across all landing & layout views.')
            ->expectsOutputToContain('ALL EDITABILITY AUDIT CHECKS PASSED: 100% DB-DRIVEN SITE')
            ->assertSuccessful();
    }

    public function test_site_audit_command_accepts_detail_flag(): void
    {
        $this->seed();

        $this->artisan('site:audit', ['--detail' => true])
            ->expectsOutputToContain('Model: MemberGroup')
            ->expectsOutputToContain('Model: Program')
            ->assertSuccessful();
    }
}
