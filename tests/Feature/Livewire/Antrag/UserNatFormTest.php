<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\CivilStatus;
use App\Enums\Salutation;
use App\Livewire\Antrag\UserNatForm;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class UserNatFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function user_nat_form_renders_successfully()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('firstname', '')
            ->set('lastname', '')
            ->set('birthday', '')
            ->set('salutation', '')
            ->set('nationality', '')
            ->set('civil_status', '')
            ->call('saveUserNat')
            ->assertHasErrors([
                'firstname' => 'required',
                'lastname' => 'required',
                'birthday' => 'required',
                'salutation' => 'required',
                'nationality' => 'required',
                'civil_status' => 'required',
            ]);
    }

    /** @test */
    public function form_loads_existing_user_data_on_mount()
    {
        $country = Country::first();
        $user = $this->createAndAuthenticateUser([
            'firstname' => 'Anna',
            'lastname' => 'Müller',
            'birthday' => '1995-06-15',
            'salutation' => Salutation::Frau->value,
            'nationality' => $country->id,
            'civil_status' => CivilStatus::ledig->value,
        ]);

        Livewire::test(UserNatForm::class)
            ->assertSet('firstname', 'Anna')
            ->assertSet('lastname', 'Müller')
            ->assertSet('salutation', Salutation::Frau)
            ->assertSet('civil_status', CivilStatus::ledig);
    }

    /** @test */
    public function valid_data_saves_to_database()
    {
        $country = Country::first();
        $user = $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('firstname', 'Max')
            ->set('lastname', 'Muster')
            ->set('birthday', '1998-03-20')
            ->set('salutation', 'Herr')
            ->set('nationality', $country->id)
            ->set('civil_status', CivilStatus::ledig->value)
            ->call('saveUserNat')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Max',
            'lastname' => 'Muster',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function birthday_must_be_a_valid_date()
    {
        $country = Country::first();
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('firstname', 'Max')
            ->set('lastname', 'Muster')
            ->set('birthday', 'not-a-date')
            ->set('salutation', 'Herr')
            ->set('nationality', $country->id)
            ->set('civil_status', CivilStatus::ledig->value)
            ->call('saveUserNat')
            ->assertHasErrors(['birthday' => 'date']);
    }

    /** @test */
    public function civil_status_must_be_valid_enum_value()
    {
        $country = Country::first();
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('firstname', 'Max')
            ->set('lastname', 'Muster')
            ->set('birthday', '1998-03-20')
            ->set('salutation', 'Herr')
            ->set('nationality', $country->id)
            ->set('civil_status', 'invalid_status')
            ->call('saveUserNat')
            ->assertHasErrors(['civil_status']);
    }

    /** @test */
    public function verheiratet_status_sets_partner_visible()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('civil_status', CivilStatus::verheiratet->value)
            ->assertSet('partnerVisible', true);
    }

    /** @test */
    public function non_verheiratet_status_hides_partner_section()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('civil_status', CivilStatus::verheiratet->value)
            ->assertSet('partnerVisible', true)
            ->set('civil_status', CivilStatus::ledig->value)
            ->assertSet('partnerVisible', false);
    }

    /** @test */
    public function granting_required_when_in_ch_since_is_set()
    {
        $country = Country::first();
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatForm::class)
            ->set('firstname', 'Max')
            ->set('lastname', 'Muster')
            ->set('birthday', '1998-03-20')
            ->set('salutation', 'Herr')
            ->set('nationality', $country->id)
            ->set('civil_status', CivilStatus::ledig->value)
            ->set('in_ch_since', '2015-01-01')
            ->set('granting', null)
            ->call('saveUserNat')
            ->assertHasErrors(['granting']);
    }
}
