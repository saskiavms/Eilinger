<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\GetAmount;
use App\Livewire\Antrag\SiblingForm;
use App\Models\Sibling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class SiblingFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    private function validSibling(): array
    {
        return [
            'birth_year' => '2001',
            'lastname' => 'Muster',
            'firstname' => 'Petra',
            'education' => 'Gymnasium',
            'graduation_year' => '2019',
            'place_of_residence' => 'Bern',
            'get_amount' => GetAmount::No->value,
            'support_site' => '',
        ];
    }

    /** @test */
    public function sibling_form_renders_successfully_with_empty_sibling()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->assertSuccessful()
            ->assertSet('siblingsList', fn($list) => count($list) === 1);
    }

    /** @test */
    public function sibling_form_loads_existing_siblings_on_mount()
    {
        $user = $this->createAndAuthenticateUser();

        Sibling::create(array_merge($this->validSibling(), ['user_id' => $user->id, 'is_draft' => false]));

        Livewire::test(SiblingForm::class)
            ->assertSet('siblingsList', fn($list) => count($list) === 1 && $list[0]['firstname'] === 'Petra');
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->set('siblingsList.0.birth_year', '')
            ->set('siblingsList.0.lastname', '')
            ->set('siblingsList.0.firstname', '')
            ->set('siblingsList.0.get_amount', '')
            ->call('saveSiblings')
            ->assertHasErrors([
                'siblingsList.0.birth_year',
                'siblingsList.0.lastname',
                'siblingsList.0.firstname',
                'siblingsList.0.get_amount',
            ]);
    }

    /** @test */
    public function birth_year_must_be_four_digits()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->set('siblingsList.0.birth_year', '01')
            ->set('siblingsList.0.lastname', 'Muster')
            ->set('siblingsList.0.firstname', 'Petra')
            ->set('siblingsList.0.get_amount', GetAmount::No->value)
            ->call('saveSiblings')
            ->assertHasErrors(['siblingsList.0.birth_year']);
    }

    /** @test */
    public function support_site_required_when_get_amount_is_yes()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->set('siblingsList', [[
                'birth_year' => '2001',
                'lastname' => 'Muster',
                'firstname' => 'Petra',
                'education' => '',
                'graduation_year' => '',
                'place_of_residence' => '',
                'get_amount' => GetAmount::Yes->value,
                'support_site' => '', // missing - should trigger error
            ]])
            ->call('saveSiblings')
            ->assertHasErrors(['siblingsList.0.support_site']);
    }

    /** @test */
    public function support_site_not_required_when_get_amount_is_no()
    {
        $user = $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->set('siblingsList.0', $this->validSibling()) // get_amount = No, support_site = ''
            ->call('saveSiblings')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('siblings', [
            'user_id' => $user->id,
            'firstname' => 'Petra',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function support_site_passes_when_get_amount_is_yes_and_filled()
    {
        $user = $this->createAndAuthenticateUser();
        $sibling = array_merge($this->validSibling(), [
            'get_amount' => GetAmount::Yes->value,
            'support_site' => 'Kanton Bern',
        ]);

        Livewire::test(SiblingForm::class)
            ->set('siblingsList.0', $sibling)
            ->call('saveSiblings')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('siblings', [
            'user_id' => $user->id,
            'support_site' => 'Kanton Bern',
        ]);
    }

    /** @test */
    public function can_add_a_second_sibling()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->assertSet('siblingsList', fn($list) => count($list) === 1)
            ->call('addSibling')
            ->assertSet('siblingsList', fn($list) => count($list) === 2);
    }

    /** @test */
    public function can_remove_a_sibling()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(SiblingForm::class)
            ->call('addSibling')
            ->assertSet('siblingsList', fn($list) => count($list) === 2)
            ->call('removeSibling', 1)
            ->assertSet('siblingsList', fn($list) => count($list) === 1);
    }
}
