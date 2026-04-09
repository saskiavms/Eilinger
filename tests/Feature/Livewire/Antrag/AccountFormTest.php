<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\AccountForm;
use App\Models\Account;
use App\Models\Application;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class AccountFormTest extends TestCase
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
    public function account_form_renders_successfully()
    {
        $this->makeApplication();

        Livewire::test(AccountForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function save_account_validates_required_fields()
    {
        $this->makeApplication();

        Livewire::test(AccountForm::class)
            ->call('saveAccount')
            ->assertHasErrors(['name_bank', 'city_bank', 'owner', 'IBAN']);
    }

    /** @test */
    public function save_account_validates_iban_format()
    {
        $this->makeApplication();

        Livewire::test(AccountForm::class)
            ->set('name_bank', 'Kantonalbank')
            ->set('city_bank', 'Zürich')
            ->set('owner', 'Max Muster')
            ->set('IBAN', 'invalid-iban')
            ->call('saveAccount')
            ->assertHasErrors(['IBAN']);
    }

    /** @test */
    public function save_account_persists_valid_data()
    {
        $application = $this->makeApplication();

        Livewire::test(AccountForm::class)
            ->set('name_bank', 'Zürcher Kantonalbank')
            ->set('city_bank', 'Zürich')
            ->set('owner', 'Max Muster')
            ->set('IBAN', 'CH56 0483 5012 3456 7800 9')
            ->call('saveAccount')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('accounts', [
            'application_id' => $application->id,
            'name_bank' => 'Zürcher Kantonalbank',
            'city_bank' => 'Zürich',
            'owner' => 'Max Muster',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function save_account_updates_existing_record()
    {
        $application = $this->makeApplication();
        $user = auth()->user();

        $account = Account::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'name_bank' => 'Alte Bank',
            'city_bank' => 'Basel',
            'owner' => 'Max Muster',
            'IBAN' => 'CH56 0483 5012 3456 7800 9',
            'is_draft' => true,
        ]);

        Livewire::test(AccountForm::class)
            ->set('name_bank', 'Neue Bank')
            ->set('city_bank', 'Bern')
            ->set('owner', 'Max Muster')
            ->set('IBAN', 'CH56 0483 5012 3456 7800 9')
            ->call('saveAccount')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name_bank' => 'Neue Bank',
            'city_bank' => 'Bern',
        ]);
        $this->assertDatabaseCount('accounts', 1);
    }

    /** @test */
    public function save_account_blocked_for_non_editable_application()
    {
        $user = $this->createAndAuthenticateUser();
        $currency = Currency::first() ?? Currency::factory()->create([
            'currency' => 'Swiss Franc', 'abbreviation' => 'CHF',
            'symbol' => 'CHF', 'is_pinned' => false,
        ]);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::APPROVED,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AccountForm::class)
            ->set('name_bank', 'Kantonalbank')
            ->set('city_bank', 'Zürich')
            ->set('owner', 'Max Muster')
            ->set('IBAN', 'CH56 0483 5012 3456 7800 9')
            ->call('saveAccount');

        $this->assertDatabaseMissing('accounts', ['application_id' => $application->id]);
    }

    /** @test */
    public function form_loads_existing_account_data_on_mount()
    {
        $application = $this->makeApplication();
        $user = auth()->user();

        Account::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'name_bank' => 'Testbank',
            'city_bank' => 'Luzern',
            'owner' => 'Anna Müller',
            'IBAN' => 'CH56 0483 5012 3456 7800 9',
            'is_draft' => false,
        ]);

        Livewire::test(AccountForm::class)
            ->assertSet('name_bank', 'Testbank')
            ->assertSet('city_bank', 'Luzern')
            ->assertSet('owner', 'Anna Müller');
    }
}
