<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Tests\Traits\WithAuthUser;
use Tests\Traits\LocalizedTestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase, WithAuthUser, LocalizedTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->createAndAuthenticateUser();
    }

    public function test_user_can_view_profile()
    {
        $response = $this->get($this->getLocalizedRoute('profile.edit'));
        $response->assertStatus(200);
    }

    public function test_user_can_update_profile()
    {
        $response = $this->patch($this->getLocalizedRoute('profile.update'), [
            'firstname' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->authUser->id,
            'firstname' => 'New Name',
            'email' => 'newemail@example.com',
        ]);
    }

    public function test_user_can_update_password()
    {
        $response = $this->put($this->getLocalizedRoute('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect();
    }
}
