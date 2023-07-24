<?php

namespace App\Http\Livewire\User;

use App\Enums\Form;
use App\Enums\Bereich;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Application;
use Illuminate\Validation\Rules\Enum;

class Antraege extends Component
{
    public $showModal = false;
    public $name;
    public $bereich;
    public $form;
    public $is_first;
    public $currency_id;


    protected function rules() : array
    {   
        return([
            'name' => 'required',
            'bereich' => ['required',new Enum(Bereich::class)], 
            'form' => ['required',new Enum(Form::class)],
            'is_first' => 'boolean|required',
            'currency_id' => 'required',
        ]);
    }

    public function render()
    {
        $applications = Application::where('user_id', auth()->user()->id)
                        ->where('appl_status', 'not send')                
                        ->get();
        $currencies = Currency::all();

        return view('livewire.user.antraege', [
            'applications' => $applications,
            'currencies' => $currencies,
        ])
            ->layout(\App\View\Components\Layouts\UserDashboard::class);
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
        ]);

        $this->name = '';
        $this->bereich = '';
        $this->form = '';
        $this->is_first = '';
        $this->currency_id ='';

        $this->showModal = false;
    }

    public function close()
    {
        $this->showModal = false;
    }
}
