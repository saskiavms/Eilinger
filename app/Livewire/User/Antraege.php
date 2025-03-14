<?php

namespace App\Livewire\User;

use App\Enums\Bereich;
use App\Enums\Form;
use App\Enums\Types;
use App\Models\Application;
use App\Models\Currency;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Antraege extends Component
{
    public $showModal = false;

    public $name;

    public $bereich;

    public $form;

    public $is_first;

    public $currency_id;

    public $main_application_id;

    public $first_applications;

    public $visible;

    public $main_appl_id;

    public $start_appl;

    public $end_appl;

    public $formOptions = [];

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'bereich' => ['required', new Enum(Bereich::class)],
            'form' => ['required', new Enum(Form::class)],
            'is_first' => 'boolean|required',
            'currency_id' => 'required',
            'main_appl_id' => 'sometimes',
            'start_appl' => 'required',
            'end_appl' => 'sometimes',
        ];
    }

    public function validationAttributes()
    {
        return Lang::get('application');
    }

    #[Layout('components.layout.user-dashboard')]
    public function render()
    {
        $applications = Application::LoggedInUser()
            ->where('appl_status', 'not send')
            ->get();
        $currencies = Currency::orderBy('is_pinned', 'DESC')->orderBy('currency')->get();

        return view('livewire.user.antraege', [
            'applications' => $applications,
            'currencies' => $currencies,
        ]);

    }

    public function addApplication()
    {
        $this->showModal = true;
    }

    public function deleteApplication($id)
    {
        Application::find($id)->delete();
        session()->flash('success', 'Antrag erfolgreich gelöscht');
    }

    public function save()
    {
        $this->validate();

        $application = Application::create([
            'name' => $this->name,
            'bereich' => $this->bereich,
            'user_id' => auth()->user()->id,
            'form' => $this->form,
            'is_first' => $this->is_first,
            'currency_id' => $this->currency_id,
            'main_application_id' => $this->main_appl_id,
            'start_appl' => $this->start_appl,
            'end_appl' => $this->end_appl,
        ]);

        $this->close();
    }
    public function updateBereich($value)
    {
        // Convert the string to an enum instance
        $bereichEnum = Bereich::tryFrom($value);
        $userTypeValue = auth()->user()->type->value;

        // For natural persons in education sector
        if ($bereichEnum === Bereich::Bildung && $userTypeValue === Types::nat->value) {
            $this->formOptions = [
                Form::Stipendium->value,
            ];
        }
        // For natural persons NOT in education sector
        elseif ($bereichEnum !== Bereich::Bildung && $userTypeValue === Types::nat->value) {
            $this->formOptions = [
                Form::Darlehen->value,
            ];
        }
        // For juristic persons (any sector)
        else {
            $this->formOptions = [
                Form::Spende->value,
            ];
        }
    }


    public function close(): void
    {
        $this->name = '';
        $this->bereich = '';
        $this->form = '';
        $this->is_first = '';
        $this->currency_id = '';
        $this->main_appl_id = '';
        $this->start_appl = '';
        $this->end_appl = '';

        $this->visible = false;
        $this->showModal = false;
    }

    public function updatedIsFirst()
    {
        if (! $this->is_first) {
            $this->visible = true;
            $this->first_applications = Application::where('user_id', auth()->user()->id)
                ->where('bereich', $this->bereich)
                ->where('form', $this->form)
                ->get();
        }
    }
}
