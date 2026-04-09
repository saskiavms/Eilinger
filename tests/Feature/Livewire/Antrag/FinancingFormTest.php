<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\ApplStatus;
use App\Livewire\Antrag\FinancingForm;
use App\Models\Application;
use App\Models\Currency;
use App\Models\Financing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class FinancingFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function makeApplication(): Application
    {
        $user = $this->createAndAuthenticateUser();
        $currency = Currency::first() ?? Currency::factory()->create([
            'currency' => 'Swiss Franc',
            'abbreviation' => 'CHF',
            'symbol' => 'CHF',
            'is_pinned' => false,
        ]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        return $application;
    }

    /** @test */
    public function financing_form_renders_successfully()
    {
        $this->makeApplication();

        Livewire::test(FinancingForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function save_financing_validates_required_fields()
    {
        $this->makeApplication();

        Livewire::test(FinancingForm::class)
            ->call('saveFinancing')
            ->assertHasErrors([
                'personal_contribution',
                'netto_income',
                'assets',
                'scholarship',
            ]);
    }

    /** @test */
    public function save_financing_persists_valid_data()
    {
        $application = $this->makeApplication();

        Livewire::test(FinancingForm::class)
            ->set('personal_contribution', 1000)
            ->set('other_income', 200)
            ->set('income_where', 'Teilzeitjob')
            ->set('income_who', 'Ich selbst')
            ->set('netto_income', 1500)
            ->set('assets', 5000)
            ->set('scholarship', 0)
            ->call('saveFinancing')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financings', [
            'application_id' => $application->id,
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function save_financing_blocked_for_non_editable_application()
    {
        $user = $this->createAndAuthenticateUser();
        $currency = Currency::first() ?? Currency::factory()->create([
            'currency' => 'Swiss Franc', 'abbreviation' => 'CHF',
            'symbol' => 'CHF', 'is_pinned' => false,
        ]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => ApplStatus::APPROVED,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(FinancingForm::class)
            ->set('personal_contribution', 1000)
            ->set('netto_income', 1500)
            ->set('assets', 5000)
            ->set('scholarship', 0)
            ->call('saveFinancing');

        $this->assertDatabaseMissing('financings', ['application_id' => $application->id]);
    }

    /** @test */
    public function calculates_total_amount_correctly()
    {
        $this->makeApplication();

        $component = Livewire::test(FinancingForm::class)
            ->set('personal_contribution', 1000)
            ->set('other_income', 200)
            ->set('netto_income', 1500)
            ->set('assets', 500)
            ->set('scholarship', 300);

        // personal_contribution + other_income + netto_income + assets + scholarship
        $this->assertEquals(3500, $component->instance()->getAmountFinancing());
    }

    /** @test */
    public function updates_existing_financing_record()
    {
        $application = $this->makeApplication();
        $user = auth()->user();

        Financing::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'personal_contribution' => 500,
            'netto_income' => 1000,
            'assets' => 2000,
            'scholarship' => 0,
            'is_draft' => false,
        ]);

        Livewire::test(FinancingForm::class)
            ->set('personal_contribution', 800)
            ->set('netto_income', 1200)
            ->set('assets', 3000)
            ->set('scholarship', 100)
            ->call('saveFinancing')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('financings', 1);
        $this->assertDatabaseHas('financings', [
            'application_id' => $application->id,
            'personal_contribution' => 800,
        ]);
    }

    /** @test */
    public function loads_existing_financing_data_on_mount()
    {
        $application = $this->makeApplication();
        $user = auth()->user();

        Financing::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'personal_contribution' => 750,
            'netto_income' => 2000,
            'assets' => 10000,
            'scholarship' => 500,
            'is_draft' => false,
        ]);

        Livewire::test(FinancingForm::class)
            ->assertSet('personal_contribution', 750.0)
            ->assertSet('netto_income', 2000.0)
            ->assertSet('assets', 10000.0)
            ->assertSet('scholarship', 500.0);
    }
}
