<?php

namespace App\Livewire\Antrag;

use App\Enums\Education;
use App\Enums\Grade;
use App\Enums\Time;
use App\Enums\InitialEducation;
use App\Models\Application;
use App\Models\Education as EducationModel;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class EducationForm extends Component
{
    public $initial_education;
    public $education_type;
    public $name;
    public $final;
    public $grade;
    public $ects_points;
    public $time;
    public $begin_edu;
    public $duration_edu;
    public $start_semester;
    public $application;
    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'education_type' => ['required', 'min:1', new Enum(Education::class)],
            'name' => 'required',
            'final' => 'required',
            'grade' => ['required', new Enum(Grade::class)],
            'ects_points' => 'required',
            'time' => ['required', new Enum(Time::class)],
            'begin_edu' => 'required|date',
            'duration_edu' => 'required',
            'start_semester' => 'required',
            'initial_education' => ['required', new Enum(InitialEducation::class)],
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('education');
    }

    public function mount()
    {
        $this->application = Application::find(session()->get('appl_id'));
        $this->isEditable = $this->application ? $this->application->isEditable() : true;
        
        $education = EducationModel::loggedInUser()
            ->where('application_id', session()->get('appl_id'))
            ->first() ?? new EducationModel;

        $this->initial_education = $education->initial_education;
        $this->education_type = $education->education;
        $this->name = $education->name;
        $this->final = $education->final;
        $this->grade = $education->grade;
        $this->ects_points = $education->ects_points;
        $this->time = $education->time;
        $this->begin_edu = $education->begin_edu;
        $this->duration_edu = $education->duration_edu;
        $this->start_semester = $education->start_semester;
    }

    public function render()
    {
        return view('livewire.antrag.education-form');
    }

    public function saveEducation()
    {
        if (!$this->isEditable) {
            session()->flash('error', __('application.edit_restriction_error'));
            return;
        }
        
        $validatedData = $this->validate();

        $education = EducationModel::loggedInUser()
            ->where('application_id', session()->get('appl_id'))
            ->first() ?? new EducationModel;

        $education->fill([
            'initial_education' => $validatedData['initial_education'],
            'education' => $validatedData['education_type'],
            'name' => $validatedData['name'],
            'final' => $validatedData['final'],
            'grade' => $validatedData['grade'],
            'ects_points' => $validatedData['ects_points'],
            'time' => $validatedData['time'],
            'begin_edu' => $validatedData['begin_edu'],
            'duration_edu' => $validatedData['duration_edu'],
            'start_semester' => $validatedData['start_semester'],
        ]);

        $education->is_draft = false;
        $education->user_id = auth()->user()->id;
        $education->application_id = session()->get('appl_id');
        $education->save();

        session()->flash('success', __('userNotification.educationSaved'));
    }
}
