<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSeeLivewire('auth.login');
    }

    /** @test */
    public function users_can_authenticate()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function users_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}

class LayoutTest extends TestCase
{
    /** @test */
    public function dashboard_layout_contains_required_components()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSeeLivewire('topbar');
        $response->assertSeeLivewire('sidebar');
    }

    /** @test */
    public function eilinger_layout_contains_required_components()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSeeLivewire('navbar');
        $response->assertSeeLivewire('hero');
        $response->assertSeeLivewire('footer');
    }
}

class ResponsiveTest extends TestCase
{
    /** @test */
    public function mobile_menu_is_not_visible_by_default()
    {
        $response = $this->get('/');

        $response->assertSee('mobile-menu', false);
        $response->assertDontSee('mobile-menu-open', false);
    }
}

class AntragProcessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_antrag()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('antrag.create-form')
            ->set('form.title', 'Test Antrag')
            ->set('form.description', 'Test Description')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertEmitted('antrag-created');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_antrag_form()
    {
        $response = $this->get('/antrag/create');
        $response->assertRedirect('/login');
    }
}
