<?php

namespace Tests\Feature\Livewire\User;

use App\Enums\ApplStatus;
use App\Enums\Bereich;
use App\Enums\Form;
use App\Enums\Types;
use App\Models\Application;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NeuerAntragTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'type' => Types::nat,
        ]);

        $this->admin = User::factory()->create([
            'is_admin' => true
        ]);
    }

    /** @test */
    public function user_can_view_applications_page()
    {
        $this->actingAs($this->user)
            ->get(route('user_antraege', ['locale' => 'de']))
            ->assertSuccessful()
            ->assertSeeLivewire('user.antraege')
            ->assertSee(__('application.applications'));
    }

    /** @test */
    public function user_can_create_new_application()
    {
        $currency = Currency::first();

        Livewire::actingAs($this->user)
            ->test('user.antraege')
            ->call('addApplication')
            ->assertSet('showModal', true)
            // First set bereich to trigger formOptions update
            ->set('bereich', Bereich::Bildung->value)
            // Then set other fields
            ->set('name', 'Test Application')
            ->set('form', Form::Stipendium->value)
            ->set('currency_id', $currency->id)
            ->set('is_first', true)
            ->set('start_appl', now()->format('Y-m-d'))
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('applications', [
            'user_id' => $this->user->id,
            'name' => 'Test Application',
            'bereich' => Bereich::Bildung->value,
            'form' => Form::Stipendium->value,
            'currency_id' => $currency->id,
        ]);
    }

    /** @test */
    public function application_creation_respects_localization()
    {
        // Test German
        $this->setTestLocale('de');
        $response = $this->actingAs($this->user)
            ->get(route('user_antraege', ['locale' => 'de']));
        $response->assertSee(__('application.applications'));

        // Test English
        $this->setTestLocale('en');
        $response = $this->actingAs($this->user)
            ->get(route('user_antraege', ['locale' => 'en']));
        $response->assertSee(__('application.applications'));
    }

    /** @test */
    public function unauthorized_users_cannot_access_applications()
    {
        $this->get(route('user_antraege', ['locale' => 'de']))
            ->assertRedirect(route('login', ['locale' => 'de']));
    }

    /** @test */
    public function application_requires_valid_form_type()
    {
        Livewire::actingAs($this->user)
            ->test('user.antraege')
            ->call('addApplication')
            ->set('bereich', Bereich::Bildung->value)
            ->set('form', 'invalid_form_type')
            ->set('name', 'Test Application')
            ->set('currency_id', Currency::first()->id)
            ->set('is_first', true)
            ->set('start_appl', now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['form']);
    }
}
