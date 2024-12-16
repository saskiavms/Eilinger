<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\CostDarlehen;
use App\Models\Currency;
use Illuminate\Support\Collection;
use Livewire\Component;

class CostFormDarlehen extends Component
{
    public $costs;
    public $currency_id;
    public $myCurrency;
    public $total_amount = 0;

    protected function rules(): array
    {
        return [
            'costs.*.cost_name' => 'required',
            'costs.*.cost_amount' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'costs.*.cost_name' => __('cost.cost_name_req'),
            'costs.*.cost_amount' => __('cost.cost_amount_req'),
        ];
    }

    public function mount(): void
    {
        $existingCosts = CostDarlehen::where('application_id', session()->get('appl_id'))->get();

        $this->costs = $existingCosts->isEmpty()
            ? [['cost_name' => '', 'cost_amount' => 0]]
            : $existingCosts->toArray();

        $application = Application::find(session()->get('appl_id'));
        $this->currency_id = $application->currency_id;
        $this->myCurrency = Currency::find($this->currency_id);

        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $this->total_amount = collect($this->costs)->sum('cost_amount');
    }

    public function addCost(): void
    {
        $this->costs[] = [
            'cost_name' => '',
            'cost_amount' => 0
        ];
    }

    public function removeCost($index): void
    {
        if (isset($this->costs[$index]['id'])) {
            CostDarlehen::find($this->costs[$index]['id'])?->delete();
        }

        unset($this->costs[$index]);
        $this->costs = array_values($this->costs);
        $this->calculateTotal();
    }

    public function saveCosts(): void
    {
        $this->validate();

        foreach ($this->costs as $costData) {
            $cost = isset($costData['id'])
                ? CostDarlehen::find($costData['id'])
                : new CostDarlehen();

            $cost->cost_name = $costData['cost_name'];
            $cost->cost_amount = $costData['cost_amount'];
            $cost->application_id = session()->get('appl_id');
            $cost->user_id = auth()->user()->id;
            $cost->is_draft = false;
            $cost->save();
        }

        $this->calculateTotal();
        session()->flash('success', __('userNotification.costSaved'));
    }

    public function updated($field): void
    {
        if (str_contains($field, 'cost_amount')) {
            $this->calculateTotal();
        }
    }

    public function render()
    {
        return view('livewire.antrag.cost-form-darlehen');
    }
}
