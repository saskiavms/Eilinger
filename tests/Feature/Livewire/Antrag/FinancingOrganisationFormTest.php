<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\FinancingOrganisationForm;
use App\Models\Application;
use App\Models\Currency;
use App\Models\FinancingOrganisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class FinancingOrganisationFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function makeApplication($user): Application
    {
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);
        return $application;
    }

    /** @test */
    public function financing_organisation_form_renders_with_one_empty_row()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->assertSuccessful()
            ->assertSet('financings', fn($f) => count($f) === 1);
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->set('financings.0.financing_name', '')
            ->set('financings.0.financing_amount', '')
            ->call('saveFinancings')
            ->assertHasErrors([
                'financings.0.financing_name',
                'financings.0.financing_amount',
            ]);
    }

    /** @test */
    public function financing_amount_must_be_numeric()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->set('financings.0.financing_name', 'Kanton')
            ->set('financings.0.financing_amount', 'viel')
            ->call('saveFinancings')
            ->assertHasErrors(['financings.0.financing_amount' => 'numeric']);
    }

    /** @test */
    public function valid_financing_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->set('financings.0.financing_name', 'Kanton Bern')
            ->set('financings.0.financing_amount', 5000)
            ->call('saveFinancings')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financing_organisations', [
            'application_id' => $application->id,
            'user_id' => $user->id,
            'financing_name' => 'Kanton Bern',
            'financing_amount' => 5000,
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function can_add_and_remove_financing_rows()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->call('addFinancing')
            ->assertSet('financings', fn($f) => count($f) === 2)
            ->call('removeFinancing', 1)
            ->assertSet('financings', fn($f) => count($f) === 1);
    }

    /** @test */
    public function total_is_calculated_correctly()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Livewire::test(FinancingOrganisationForm::class)
            ->set('financings.0.financing_amount', 3000)
            ->call('addFinancing')
            ->set('financings.1.financing_amount', 2000)
            ->assertSet('total_amount', 5000.0);
    }

    /** @test */
    public function loads_existing_financing_entries_on_mount()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        FinancingOrganisation::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'financing_name' => 'Gemeinde Bern',
            'financing_amount' => 2000,
            'is_draft' => false,
        ]);

        Livewire::test(FinancingOrganisationForm::class)
            ->assertSet('financings', fn($f) =>
                count($f) === 1 && $f[0]['financing_name'] === 'Gemeinde Bern'
            );
    }
}
