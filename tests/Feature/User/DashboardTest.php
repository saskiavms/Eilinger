<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Tests\Traits\WithAuthUser;
use Tests\Traits\LocalizedTestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, LocalizedTestTrait;

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get($this->getLocalizedRoute('user_dashboard'));
        $response->assertRedirect($this->getLocalizedRoute('login'));
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $this->createAndAuthenticateUser();

        $response = $this->get($this->getLocalizedRoute('user_dashboard'));
        $response->assertStatus(200);
        $response->assertSeeLivewire('user.uebersicht');
    }

    public function test_unverified_user_cannot_access_dashboard()
    {
        $this->createAndAuthenticateUser(['email_verified_at' => null]);

        $response = $this->get($this->getLocalizedRoute('user_dashboard'));
        $response->assertRedirect($this->getLocalizedRoute('verification.notice'));
    }

    public function test_user_cannot_access_admin_dashboard()
{
    $this->setTestLocale('de');

    // Add debug assertions
    $this->assertEquals('de', app()->getLocale());

    $this->createAndAuthenticateUser();

    $response = $this->get($this->getLocalizedRoute('admin_dashboard'));

    $response->assertRedirect($this->getLocalizedRoute('index'));
    $response->assertLocation($this->getLocalizedRoute('index'));
}
}
