<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\LocalizedTestTrait;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use LocalizedTestTrait;

    public function test_user_can_view_a_login_form()
    {
        $response = $this->get($this->getLocalizedRoute('login'));

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function test_user_dashboard_shown_when_logged_in()
    {
        $user = User::factory()->create([
            'is_admin' => 0,
        ]);
        $response = $this->actingAs($user)->get($this->getLocalizedRoute('user_dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_dashboard_shown_when_logged_in_as_admin()
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);
        $response = $this->actingAs($user)->get($this->getLocalizedRoute('admin_dashboard'));

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
            'is_admin' => 0,
        ]);

        $response = $this->post($this->getLocalizedRoute('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect($this->getLocalizedRoute('user_dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $this->post($this->getLocalizedRoute('login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
    }

    public function test_login_works_in_both_languages()
    {
        // Test German login
        $this->setTestLocale('de');
        $response = $this->get($this->getLocalizedRoute('login'));
        $response->assertSee('Login');

        // Test English login
        $this->setTestLocale('en');
        $response = $this->get($this->getLocalizedRoute('login'));
        $response->assertSee('Login');
    }
}
