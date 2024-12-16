<?php

namespace App\Livewire\Antrag;

class EnclosureFormDarlehenPrivat extends BaseEnclosureForm
{
    protected function getRequiredFields(): array
    {
        return [
            'activity',
            'activity_report',
            'rental_contract',
            'balance_sheet',
            'tax_assessment',
            'cost_receipts',
        ];
    }

    protected function getOptionalFields(): array
    {
        return ['open_invoice'];
    }

    public function saveEnclosure(): void
    {
        $this->save();
        $this->dispatch('completeApp');
    }

    public function render()
    {
        return view('livewire.antrag.enclosure-form-darlehen_privat');
    }
}
