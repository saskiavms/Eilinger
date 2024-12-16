<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Currency;
use App\Models\FinancingOrganisation;
use Livewire\Component;

class FinancingOrganisationForm extends Component
{
    public $financings;
    public $currency_id;
    public $myCurrency;
    public $total_amount = 0;

    protected function rules(): array
    {
        return [
            'financings.*.financing_name' => 'required',
            'financings.*.financing_amount' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'financings.*.financing_name' => __('financing :position financing_name'),
            'financings.*.financing_amount' => __('financing :position financing_amount'),
        ];
    }

    public function mount(): void
    {
        $existingFinancings = FinancingOrganisation::where('application_id', session()->get('appl_id'))->get();

        $this->financings = $existingFinancings->isEmpty()
            ? [['financing_name' => '', 'financing_amount' => 0]]
            : $existingFinancings->toArray();

        $application = Application::find(session()->get('appl_id'));
        $this->currency_id = $application->currency_id;
        $this->myCurrency = Currency::find($this->currency_id);

        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total_amount = collect($this->financings)->sum('financing_amount');
    }

    public function addFinancing(): void
    {
        $this->financings[] = [
            'financing_name' => '',
            'financing_amount' => 0
        ];
    }

    public function removeFinancing($index): void
    {
        if (isset($this->financings[$index]['id'])) {
            FinancingOrganisation::find($this->financings[$index]['id'])?->delete();
        }

        unset($this->financings[$index]);
        $this->financings = array_values($this->financings);
        $this->calculateTotal();
    }

    public function saveFinancings(): void
    {
        $this->validate();

        foreach ($this->financings as $financingData) {
            $financing = isset($financingData['id'])
                ? FinancingOrganisation::find($financingData['id'])
                : new FinancingOrganisation();

            $financing->financing_name = $financingData['financing_name'];
            $financing->financing_amount = $financingData['financing_amount'];
            $financing->application_id = session()->get('appl_id');
            $financing->user_id = auth()->user()->id;
            $financing->is_draft = false;
            $financing->save();
        }

        $this->calculateTotal();
        session()->flash('success', __('userNotification.financingSaved'));
    }

    public function updated($field): void
    {
        if (str_contains($field, 'financing_amount')) {
            $this->calculateTotal();
        }
    }

    public function render()
    {
        return view('livewire.antrag.financing-organisation-form');
    }
}
