<?php

namespace App\Livewire\Antrag;

class EnclosureFormStipendiumFolge extends BaseEnclosureForm
{
    protected function getRequiredFields(): array
    {
        return [
            'certificate_of_study',
            'tax_assessment',
            'expense_receipts',
            'parents_tax_factors'
        ];
    }

    protected function getOptionalFields(): array
    {
        return [
            'partner_tax_assessment',
            'supplementary_services',
            'ects_points'
        ];
    }

    public function saveEnclosure(): void
    {
        $this->save();
        $this->dispatch('completeApp');
    }

    public function render()
    {
        return view('livewire.antrag.enclosure-form-stipendium-folge');
    }
}
