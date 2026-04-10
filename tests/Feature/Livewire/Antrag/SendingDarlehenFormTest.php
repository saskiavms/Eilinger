<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\SendingDarlehenForm;
use App\Models\Account;
use App\Models\Address;
use App\Models\Application;
use App\Models\CostDarlehen;
use App\Models\Currency;
use App\Models\Enclosure;
use App\Models\Financing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class SendingDarlehenFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function makeApplication(User $user): Application
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
    public function shows_incomplete_when_all_forms_are_missing()
    {
        $user = $this->createAndAuthenticateUser(['is_draft' => true]);
        $this->makeApplication($user);

        Livewire::test(SendingDarlehenForm::class)
            ->assertSet('userNoDraft', false)
            ->assertSet('addressNoDraft', false)
            ->assertSet('costNoDraft', false)
            ->assertSet('accountNoDraft', false)
            ->assertSet('enclosureNoDraft', false);
    }

    /** @test */
    public function detects_completed_user_data()
    {
        $user = $this->createAndAuthenticateUser(['is_draft' => false]);
        $this->makeApplication($user);

        Livewire::test(SendingDarlehenForm::class)
            ->assertSet('userNoDraft', true);
    }

    /** @test */
    public function detects_completed_address_data()
    {
        $user = $this->createAndAuthenticateUser();
        $this->makeApplication($user);

        Address::factory()->create([
            'user_id' => $user->id,
            'is_wochenaufenthalt' => false,
            'is_draft' => false,
        ]);

        Livewire::test(SendingDarlehenForm::class)
            ->assertSet('addressNoDraft', true);
    }

    /** @test */
    public function detects_completed_cost_data()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        CostDarlehen::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'cost_name' => 'Miete',
            'cost_amount' => 1000,
            'is_draft' => false,
        ]);

        Livewire::test(SendingDarlehenForm::class)
            ->assertSet('costNoDraft', true);
    }

    /** @test */
    public function detects_completed_financing_data()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        Financing::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'is_draft' => false,
        ]);

        Livewire::test(SendingDarlehenForm::class)
            ->assertSet('financingNoDraft', true);
    }

    /** @test */
    public function complete_application_does_not_dispatch_when_forms_incomplete()
    {
        $user = $this->createAndAuthenticateUser(['is_draft' => true]);
        $this->makeApplication($user);

        Livewire::test(SendingDarlehenForm::class)
            ->call('completeApplication')
            ->assertNotDispatched('completeApp');
    }

    /** @test */
    public function complete_application_dispatches_when_all_required_forms_saved()
    {
        $user = $this->createAndAuthenticateUser(['is_draft' => false]);
        $application = $this->makeApplication($user);

        Address::factory()->create([
            'user_id' => $user->id,
            'is_wochenaufenthalt' => false,
            'is_draft' => false,
        ]);

        Account::factory()->create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'is_draft' => false,
        ]);

        CostDarlehen::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'cost_name' => 'Miete',
            'cost_amount' => 1000,
            'is_draft' => false,
        ]);

        Financing::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'is_draft' => false,
        ]);

        Enclosure::create([
            'application_id' => $application->id,
            'is_draft' => false,
        ]);

        Livewire::test(SendingDarlehenForm::class)
            ->call('completeApplication')
            ->assertDispatched('completeApp');
    }
}
