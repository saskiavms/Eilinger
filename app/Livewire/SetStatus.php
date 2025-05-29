<?php

namespace App\Livewire;

use App\Enums\ApplStatus;
use App\Models\Application;
use App\Models\Payment;
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
    public $new_payment_amount;
    public $new_payment_date;
    public $new_payment_notes;

    public function mount(Application $application)
    {
        $this->application = $application;
        $this->status = $application->appl_status->value;
        $this->reason_rejected = $application->reason_rejected;
        // Format dates for the date input fields
        $this->approval_appl = $application->approval_appl ? $application->approval_appl->format('Y-m-d') : null;
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
            ]
        ]);

        $this->application->appl_status = $validated['status'];

        // Handle approval date
        if ($validated['status'] === ApplStatus::APPROVED->value) {
            $this->application->approval_appl = $validated['approval_appl'];
        } elseif ($this->application->appl_status !== ApplStatus::APPROVED->value) {
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

    public function addPayment()
    {
        $validated = $this->validate([
            'new_payment_amount' => ['required', 'numeric', 'min:0.01'],
            'new_payment_date' => ['required', 'date'],
            'new_payment_notes' => ['nullable', 'string', 'max:500']
        ], [
            'new_payment_amount.required' => 'Bitte geben Sie einen Zahlungsbetrag ein.',
            'new_payment_amount.numeric' => 'Der Zahlungsbetrag muss eine Zahl sein.',
            'new_payment_amount.min' => 'Der Zahlungsbetrag muss mindestens 0.01 betragen.',
            'new_payment_date.required' => 'Bitte geben Sie ein Zahlungsdatum ein.',
            'new_payment_date.date' => 'Bitte geben Sie ein gültiges Datum ein.',
            'new_payment_notes.max' => 'Die Notizen dürfen nicht länger als 500 Zeichen sein.',
        ]);

        try {
            $payment = Payment::create([
                'application_id' => $this->application->id,
                'amount' => $validated['new_payment_amount'],
                'payment_date' => $validated['new_payment_date'],
                'notes' => $validated['new_payment_notes'],
            ]);

            // Reset form fields
            $this->new_payment_amount = null;
            $this->new_payment_date = null;
            $this->new_payment_notes = null;

            // Refresh the application relationship
            $this->application->refresh();

            session()->flash('payment_message', 'Zahlung erfolgreich hinzugefügt');
        } catch (\Exception $e) {
            session()->flash('payment_error', 'Fehler beim Speichern der Zahlung: ' . $e->getMessage());
        }
    }

    public function deletePayment($paymentId)
    {
        $payment = Payment::where('id', $paymentId)
            ->where('application_id', $this->application->id)
            ->first();

        if ($payment) {
            $payment->delete();
            $this->application->refresh();
            session()->flash('payment_message', 'Zahlung erfolgreich gelöscht');
        }
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
        // Ensure payments are loaded fresh
        $this->application->load('payments');
        
        return view('livewire.set-status');
    }
}
