<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Livewire\Antrag\AddressForm;
use App\Models\Address;
use App\Models\Application;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class AddressFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function createAddressForUser($user): Address
    {
        $country = Country::first();
        return Address::factory()->create([
            'user_id' => $user->id,
            'country_id' => $country->id,
            'street' => 'Musterstrasse',
            'number' => '5',
            'plz' => '3000',
            'town' => 'Bern',
            'is_draft' => true,
        ]);
    }

    /** @test */
    public function address_form_renders_successfully()
    {
        $user = $this->createAndAuthenticateUser();
        $this->createAddressForUser($user);
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $user = $this->createAndAuthenticateUser();
        $this->createAddressForUser($user);
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->set('street', '')
            ->set('town', '')
            ->set('plz', '')
            ->set('country_id', '')
            ->call('saveAddress')
            ->assertHasErrors([
                'street' => 'required',
                'town' => 'required',
                'plz' => 'required',
                'country_id' => 'required',
            ]);
    }

    /** @test */
    public function street_must_have_minimum_length()
    {
        $user = $this->createAndAuthenticateUser();
        $this->createAddressForUser($user);
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->set('street', 'AB') // less than 3 chars
            ->call('saveAddress')
            ->assertHasErrors(['street' => 'min']);
    }

    /** @test */
    public function valid_data_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();
        $address = $this->createAddressForUser($user);
        $country = Country::first();
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->set('street', 'Bahnhofstrasse')
            ->set('number', '12')
            ->set('town', 'Zürich')
            ->set('plz', '8001')
            ->set('country_id', $country->id)
            ->call('saveAddress')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'street' => 'Bahnhofstrasse',
            'town' => 'Zürich',
            'plz' => '8001',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function form_loads_existing_address_on_mount()
    {
        $user = $this->createAndAuthenticateUser();
        $this->createAddressForUser($user);
        $currency = Currency::first();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->assertSet('street', 'Musterstrasse')
            ->assertSet('town', 'Bern')
            ->assertSet('plz', '3000');
    }

    /** @test */
    public function form_prevents_saving_when_not_editable()
    {
        $user = $this->createAndAuthenticateUser();
        $this->createAddressForUser($user);
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => 1,
            'appl_status' => 'Approved',
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(AddressForm::class)
            ->set('street', 'Neue Strasse')
            ->call('saveAddress');

        $this->assertDatabaseMissing('addresses', [
            'user_id' => $user->id,
            'street' => 'Neue Strasse',
        ]);
    }
}
