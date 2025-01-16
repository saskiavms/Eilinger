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

    // Add this property to track the status changes
    public $status;
    public $reason_rejected;

    public function mount(Application $application)
    {
        $this->application = $application;
        // Initialize the properties with current values
        $this->status = $application->appl_status->value;
        $this->reason_rejected = $application->reason_rejected;
    }

    public function setStatus()
    {
        $validated = $this->validate([
            'status' => ['required', new Enum(ApplStatus::class)],
            'reason_rejected' => [
                'required_if:status,' . ApplStatus::BLOCKED->value,
                'nullable',
                'string',
                'max:255'
            ],
        ]);

        $this->application->appl_status = $validated['status'];
        $this->application->reason_rejected = $validated['reason_rejected'];
        $this->application->save();

        $this->application->user->notify(new StatusUpdated($this->application));

        session()->flash('message', 'Status des Antrags gespeichert');
    }

    public function messages()
    {
        return [
            'status.required' => 'Bitte wählen Sie einen Status aus.',
            'reason_rejected.required_if' => 'Bei Ablehnung muss ein Grund angegeben werden.',
        ];
    }

    public function render()
    {
        return view('livewire.set-status');
    }
}
