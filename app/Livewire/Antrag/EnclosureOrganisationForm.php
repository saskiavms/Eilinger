<?php

namespace App\Livewire\Antrag;

class EnclosureOrganisationForm extends BaseEnclosureForm
{
    protected function getRequiredFields(): array
    {
        return [
            'commercial_register_extract',
            'statute',
            'activity',
            'activity_report'
        ];
    }

    protected function getOptionalFields(): array
    {
        return [
            'balance_sheet',
            'tax_assessment',
            'cost_receipts'
        ];
    }

    public function saveEnclosure(): void
    {
        $this->save();
        $this->dispatch('completeApp');
    }

    public function render()
    {
        return view('livewire.antrag.enclosure-organisation-form');
    }
}
