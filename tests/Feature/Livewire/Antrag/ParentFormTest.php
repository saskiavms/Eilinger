<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\JobType;
use App\Enums\ParentType;
use App\Livewire\Antrag\ParentForm;
use App\Models\Parents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class ParentFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    private function validParent(): array
    {
        return [
            'parent_type' => ParentType::mother->value,
            'firstname' => 'Maria',
            'lastname' => 'Muster',
            'birthday' => '1965-04-12',
            'phone' => '079 123 45 67',
            'address' => 'Musterstrasse 1',
            'plz_ort' => '3000 Bern',
            'since' => null,
            'job_type' => JobType::angestellt->value,
            'job' => 'Lehrerin',
            'employer' => 'Kanton Bern',
            'in_ch_since' => null,
            'married_since' => null,
            'separated_since' => null,
            'divorced_since' => null,
            'death' => null,
        ];
    }

    /** @test */
    public function parent_form_renders_successfully_with_empty_parent()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->assertSuccessful()
            ->assertSet('parentsList', fn($list) => count($list) === 1);
    }

    /** @test */
    public function parent_form_loads_existing_parents_on_mount()
    {
        $user = $this->createAndAuthenticateUser();

        Parents::create(array_merge($this->validParent(), ['user_id' => $user->id, 'is_draft' => false]));

        Livewire::test(ParentForm::class)
            ->assertSet('parentsList', fn($list) => count($list) === 1 && $list[0]['firstname'] === 'Maria');
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->set('parentsList.0.parent_type', '')
            ->set('parentsList.0.firstname', '')
            ->set('parentsList.0.lastname', '')
            ->set('parentsList.0.birthday', '')
            ->call('saveParents')
            ->assertHasErrors([
                'parentsList.0.parent_type',
                'parentsList.0.firstname',
                'parentsList.0.lastname',
                'parentsList.0.birthday',
            ]);
    }

    /** @test */
    public function parent_type_must_be_valid_enum_value()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->set('parentsList.0.parent_type', 'invalid_type')
            ->set('parentsList.0.firstname', 'Maria')
            ->set('parentsList.0.lastname', 'Muster')
            ->set('parentsList.0.birthday', '1965-04-12')
            ->call('saveParents')
            ->assertHasErrors(['parentsList.0.parent_type']);
    }

    /** @test */
    public function valid_parent_data_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->set('parentsList.0', $this->validParent())
            ->call('saveParents')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('parents', [
            'user_id' => $user->id,
            'firstname' => 'Maria',
            'lastname' => 'Muster',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function can_add_a_second_parent()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->assertSet('parentsList', fn($list) => count($list) === 1)
            ->call('addParent')
            ->assertSet('parentsList', fn($list) => count($list) === 2);
    }

    /** @test */
    public function can_remove_a_parent()
    {
        $this->createAndAuthenticateUser();

        Livewire::test(ParentForm::class)
            ->call('addParent')
            ->assertSet('parentsList', fn($list) => count($list) === 2)
            ->call('removeParent', 1)
            ->assertSet('parentsList', fn($list) => count($list) === 1);
    }

    /** @test */
    public function can_save_two_parents()
    {
        $user = $this->createAndAuthenticateUser();

        $father = array_merge($this->validParent(), [
            'parent_type' => ParentType::father->value,
            'firstname' => 'Hans',
            'lastname' => 'Muster',
            'birthday' => '1962-08-22',
        ]);

        Livewire::test(ParentForm::class)
            ->set('parentsList.0', $this->validParent())
            ->call('addParent')
            ->set('parentsList.1', $father)
            ->call('saveParents')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('parents', ['user_id' => $user->id, 'firstname' => 'Maria']);
        $this->assertDatabaseHas('parents', ['user_id' => $user->id, 'firstname' => 'Hans']);
    }
}
