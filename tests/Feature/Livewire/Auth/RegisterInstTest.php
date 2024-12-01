<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\RegisterInst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\LocalizedTestTrait;

class RegisterInstTest extends TestCase
{
    use RefreshDatabase;
    use LocalizedTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        // Add middleware to handle sessions
        $this->withMiddleware([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);
    }

    public function test_user_can_view_a_registerInst_form()
    {
        $response = $this->get($this->getLocalizedRoute('registerInst'));

        $response->assertSuccessful();
        $response->assertSeeLivewire('auth.register-inst');
    }


    /** @test */
    public function username_must_be_unique()
    {
        User::factory()->create(['username' => 'testuser']);

        Livewire::test(RegisterInst::class)
            ->set([
                'username' => 'testuser',
                'name_inst' => 'Test Institution',
                'email' => 'test@example.com',
                'email_inst' => 'test.inst@example.com',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'salutation' => 'Mr',
                'street' => 'Test Street',
                'plz' => '12345',
                'town' => 'Test Town',
                'country_id' => 1,
                'terms' => true,
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!'
            ])
            ->call('registerInst')
            ->assertHasErrors(['username' => 'unique']);
    }

    /** @test */
    public function password_must_meet_requirements()
    {
        Livewire::test(RegisterInst::class)
            ->set([
                'username' => 'newuser',
                'name_inst' => 'Test Institution',
                'email' => 'test@example.com',
                'email_inst' => 'test.inst@example.com',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'salutation' => 'Mr',
                'street' => 'Test Street',
                'plz' => '12345',
                'town' => 'Test Town',
                'country_id' => 1,
                'terms' => true,
                'password' => 'weak',
                'password_confirmation' => 'weak'
            ])
            ->call('registerInst')
            ->assertHasErrors(['password']);
    }

    /** @test */
    public function successful_registration_creates_user()
    {
        $country = \App\Models\Country::first();

        $component = Livewire::test(RegisterInst::class)
            ->set([
                'username' => 'newuser',
                'name_inst' => 'Test Institution',
                'email' => 'test@example.com',
                'email_inst' => 'test.inst@example.com',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'salutation' => 'Herr',
                'street' => 'Test Street',
                'plz' => '12345',
                'town' => 'Test Town',
                'country_id' => $country->id,
                'terms' => true,
                'password' => '^rwJj*Y4Yp!',
                'password_confirmation' => '^rwJj*Y4Yp!'
            ])
            ->call('registerInst')
            ->assertHasNoErrors();

        // Assert the redirect URL matches exactly what Livewire generates
        $expectedUrl = url($this->getLocalizedRoute('verification.notice'));
        $actualUrl = url($component->effects['redirect']);
        $this->assertEquals($expectedUrl, $actualUrl);

        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'test@example.com'
        ]);
    }
}
