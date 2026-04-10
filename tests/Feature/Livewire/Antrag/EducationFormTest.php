<?php

namespace Tests\Feature\Livewire\Antrag;

use App\Enums\Education;
use App\Enums\Grade;
use App\Enums\InitialEducation;
use App\Enums\Time;
use App\Livewire\Antrag\EducationForm;
use App\Models\Application;
use App\Models\Currency;
use App\Models\Education as EducationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\WithAuthUser;

class EducationFormTest extends TestCase
{
    use RefreshDatabase, WithAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        session()->flush();
    }

    private function validEducationData(): array
    {
        return [
            'initial_education' => InitialEducation::Yes->value,
            'education_type' => Education::Universitaet->value,
            'name' => 'Universität Bern',
            'final' => 'Bachelor',
            'grade' => Grade::Highschool->value,
            'ects_points' => 180,
            'time' => Time::Vollzeit->value,
            'begin_edu' => '2020-09-01',
            'duration_edu' => 6,
            'start_semester' => 'HS 2020',
        ];
    }

    private function makeApplication($user): Application
    {
        $currency = Currency::first();
        return Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'appl_status' => \App\Enums\ApplStatus::NOTSEND,
        ]);
    }

    /** @test */
    public function education_form_renders_successfully()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);
        session(['appl_id' => $application->id]);

        Livewire::test(EducationForm::class)
            ->assertSuccessful();
    }

    /** @test */
    public function required_fields_show_validation_errors_when_empty()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);
        session(['appl_id' => $application->id]);

        Livewire::test(EducationForm::class)
            ->call('saveEducation')
            ->assertHasErrors([
                'education_type' => 'required',
                'name' => 'required',
                'final' => 'required',
                'grade' => 'required',
                'ects_points' => 'required',
                'time' => 'required',
                'begin_edu' => 'required',
                'duration_edu' => 'required',
                'start_semester' => 'required',
                'initial_education' => 'required',
            ]);
    }

    /** @test */
    public function education_type_must_be_valid_enum_value()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);
        session(['appl_id' => $application->id]);

        $data = $this->validEducationData();
        $data['education_type'] = 'invalid_type';

        Livewire::test(EducationForm::class)
            ->set($data)
            ->call('saveEducation')
            ->assertHasErrors(['education_type']);
    }

    /** @test */
    public function begin_edu_must_be_a_valid_date()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);
        session(['appl_id' => $application->id]);

        $data = $this->validEducationData();
        $data['begin_edu'] = 'not-a-date';

        Livewire::test(EducationForm::class)
            ->set($data)
            ->call('saveEducation')
            ->assertHasErrors(['begin_edu' => 'date']);
    }

    /** @test */
    public function valid_data_saves_to_database()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);
        session(['appl_id' => $application->id]);

        Livewire::test(EducationForm::class)
            ->set($this->validEducationData())
            ->call('saveEducation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('educations', [
            'user_id' => $user->id,
            'application_id' => $application->id,
            'name' => 'Universität Bern',
            'final' => 'Bachelor',
            'is_draft' => false,
        ]);
    }

    /** @test */
    public function form_loads_existing_education_on_mount()
    {
        $user = $this->createAndAuthenticateUser();
        $application = $this->makeApplication($user);

        EducationModel::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'initial_education' => InitialEducation::Yes->value,
            'education' => Education::Fachhochschule->value,
            'name' => 'ZHAW',
            'final' => 'Master',
            'grade' => Grade::Other->value,
            'ects_points' => 120,
            'time' => Time::Teilzeit->value,
            'begin_edu' => '2019-09-01',
            'duration_edu' => 4,
            'start_semester' => 'HS 2019',
            'is_draft' => false,
        ]);

        session(['appl_id' => $application->id]);

        Livewire::test(EducationForm::class)
            ->assertSet('name', 'ZHAW')
            ->assertSet('final', 'Master')
            ->assertSet('ects_points', 120);
    }

    /** @test */
    public function form_prevents_saving_when_not_editable()
    {
        $user = $this->createAndAuthenticateUser();
        $application = Application::factory()->create([
            'user_id' => $user->id,
            'currency_id' => 1,
            'appl_status' => 'Approved',
        ]);
        session(['appl_id' => $application->id]);

        Livewire::test(EducationForm::class)
            ->set($this->validEducationData())
            ->call('saveEducation');

        $this->assertDatabaseMissing('educations', [
            'application_id' => $application->id,
            'name' => 'Universität Bern',
        ]);
    }
}
