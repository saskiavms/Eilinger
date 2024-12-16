<?php

namespace App\Livewire;

use App\Enums\ApplStatus;
use App\Models\Application;
use App\Notifications\StatusUpdated;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class SetStatus extends Component
{
    public Application $application;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    public function setStatus()
    {
        $this->validate();

        $this->application->save();

        $this->application->user->notify(new StatusUpdated($this->application));

        session()->flash('message', 'Status des Antrags gespeichert');
    }

    public function rules()
    {
        return [
            'application.appl_status' => ['required', new Enum(ApplStatus::class)],
            'application.reason_rejected' => [
                'required_if:application.appl_status,' . ApplStatus::BLOCKED->value,
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages()
    {
        return [
            'application.appl_status.required' => 'Bitte wählen Sie einen Status aus.',
            'application.reason_rejected.required_if' => 'Bei Ablehnung muss ein Grund angegeben werden.',
        ];
    }

    public function render()
    {
        return view('livewire.set-status');
    }
}
