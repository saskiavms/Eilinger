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
    public $approval_appl;

    public function mount(Application $application)
    {
        $this->application = $application;
        // Initialize the properties with current values
        $this->status = $application->appl_status->value;
        $this->reason_rejected = $application->reason_rejected;
        $this->approval_appl = $application->approval_appl;
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
            'approval_appl' => [
                'required_if:status,' . ApplStatus::APPROVED->value,
                'nullable',
                'date'
            ],
        ]);

        $this->application->appl_status = $validated['status'];

        // Handle approval date
        if ($validated['status'] === ApplStatus::APPROVED->value) {
            $this->application->approval_appl = $validated['approval_appl'];
        } elseif ($this->application->appl_status !== ApplStatus::APPROVED->value) {
            // Clear approval date if status is changed from approved to something else
            $this->application->approval_appl = null;
        }

        // Handle rejection reason
        if ($validated['status'] === ApplStatus::BLOCKED->value) {
            $this->application->reason_rejected = $validated['reason_rejected'];
        } else {
            $this->application->reason_rejected = null;
        }

        $this->application->save();

        $this->application->user->notify(new StatusUpdated($this->application));

        session()->flash('message', 'Status des Antrags gespeichert');
    }

    public function messages()
    {
        return [
            'status.required' => 'Bitte wählen Sie einen Status aus.',
            'reason_rejected.required_if' => 'Bei Ablehnung muss ein Grund angegeben werden.',
            'approval_appl.required_if' => 'Bei Genehmigung muss ein Datum angegeben werden.',
            'approval_appl.date' => 'Bitte geben Sie ein gültiges Datum ein.',
        ];
    }

    public function render()
    {
        return view('livewire.set-status');
    }
}
