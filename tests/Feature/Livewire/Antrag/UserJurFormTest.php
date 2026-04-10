<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\Salutation;
use App\Enums\Types;
use App\Livewire\Antrag\UserJurForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class UserJurFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    private function jurUser(): array
    {
        return [
            'type' => Types::jur,
            'name_inst' => 'Muster GmbH',
            'phone_inst' => '031 123 45 67',
            'email_inst' => 'info@muster.ch',
            'website' => 'https://muster.ch',
            'firstname' => 'Hans',
            'lastname' => 'Muster',
            'salutation' => Salutation::Herr->value,
            'phone' => '079 123 45 67',
        ];
    }

    /** @test */
    public function user_jur_form_renders_successfully()
    {
        $this->createAndAuthenticateUser($this->jurUser());

        Livewire::test(UserJurForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $this->createAndAuthenticateUser($this->jurUser());

        Livewire::test(UserJurForm::class)
            ->set('name_inst', '')
            ->set('phone_inst', '')
            ->set('email_inst', '')
            ->set('firstname', '')
            ->set('lastname', '')
            ->set('salutation', '')
            ->call('saveUserJur')
            ->assertHasErrors([
                'name_inst' => 'required',
                'phone_inst' => 'required',
                'email_inst' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'salutation' => 'required',
            ]);
    }

    /** @test */
    public function email_inst_must_be_valid()
    {
        $this->createAndAuthenticateUser($this->jurUser());

        Livewire::test(UserJurForm::class)
            ->set('email_inst', 'not-an-email')
            ->call('saveUserJur')
            ->assertHasErrors(['email_inst' => 'email']);
    }

    /** @test */
    public function form_loads_existing_user_data_on_mount()
    {
        $this->createAndAuthenticateUser($this->jurUser());

        Livewire::test(UserJurForm::class)
            ->assertSet('name_inst', 'Muster GmbH')
            ->assertSet('phone_inst', '031 123 45 67')
            ->assertSet('firstname', 'Hans')
            ->assertSet('lastname', 'Muster');
    }

    /** @test */
    public function valid_data_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser($this->jurUser());

        Livewire::test(UserJurForm::class)
            ->set('name_inst', 'Neue GmbH')
            ->set('phone_inst', '031 999 88 77')
            ->set('email_inst', 'neu@example.ch')
            ->set('firstname', 'Max')
            ->set('lastname', 'Neu')
            ->set('salutation', Salutation::Herr->value)
            ->call('saveUserJur')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name_inst' => 'Neue GmbH',
            'email_inst' => 'neu@example.ch',
            'is_draft' => false,
        ]);
    }
}
