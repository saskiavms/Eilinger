<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\Salutation;
use App\Livewire\Antrag\UserNatDarlehenForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class UserNatDarlehenFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    /** @test */
    public function user_nat_darlehen_form_renders_successfully()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatDarlehenForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatDarlehenForm::class)
            ->set('firstname', '')
            ->set('lastname', '')
            ->set('birthday', '')
            ->set('salutation', '')
            ->set('phone', '')
            ->call('saveUserNat')
            ->assertHasErrors([
                'firstname' => 'required',
                'lastname' => 'required',
                'birthday' => 'required',
                'salutation' => 'required',
                'phone' => 'required',
            ]);
    }

    /** @test */
    public function birthday_must_be_a_valid_date()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(UserNatDarlehenForm::class)
            ->set('firstname', 'Max')
            ->set('lastname', 'Muster')
            ->set('birthday', 'kein-datum')
            ->set('salutation', Salutation::Herr->value)
            ->set('phone', '079 123 45 67')
            ->call('saveUserNat')
            ->assertHasErrors(['birthday' => 'date']);
    }

    /** @test */
    public function form_loads_existing_user_data_on_mount()
    {
        $this->createAndAuthenticateUser([
            'firstname' => 'Petra',
            'lastname' => 'Müller',
            'birthday' => '1990-05-10',
            'salutation' => Salutation::Frau->value,
            'phone' => '031 234 56 78',
        ]);

        Livewire::test(UserNatDarlehenForm::class)
            ->assertSet('firstname', 'Petra')
            ->assertSet('lastname', 'Müller')
            ->assertSet('phone', '031 234 56 78');
    }

    /** @test */
    public function valid_data_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();

        Livewire::test(UserNatDarlehenForm::class)
            ->set('firstname', 'Anna')
            ->set('lastname', 'Beispiel')
            ->set('birthday', '1992-08-15')
            ->set('salutation', Salutation::Frau->value)
            ->set('phone', '031 111 22 33')
            ->call('saveUserNat')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Anna',
            'lastname' => 'Beispiel',
            'is_draft' => false,
        ]);
    }
}
