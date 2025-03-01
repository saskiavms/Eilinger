<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Cost;
use App\Models\Currency;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CostForm extends Component
{
    public ?float $semester_fees = 0;
    public ?float $fees = 0;
    public ?float $educational_material = 0;
    public ?float $excursion = 0;
    public ?float $travel_expenses = 0;
    public ?float $number_of_children = 0;
    public ?float $cost_of_living_with_parents = 0;
    public ?float $cost_of_living_alone = 0;
    public ?float $cost_of_living_single_parent = 0;
    public ?float $cost_of_living_with_partner = 0;

    public $currency_id;
    public $myCurrency;

    protected function rules(): array
    {
        return [
            'semester_fees' => 'required|numeric',
            'fees' => 'required|numeric',
            'educational_material' => 'required|numeric',
            'excursion' => 'required|numeric',
            'travel_expenses' => 'required|numeric',
            'cost_of_living_with_parents' => 'nullable|required_without_all:cost_of_living_alone,cost_of_living_single_parent,cost_of_living_with_partner|numeric',
            'cost_of_living_alone' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_single_parent,cost_of_living_with_partner|numeric',
            'cost_of_living_single_parent' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_alone,cost_of_living_with_partner|numeric',
            'cost_of_living_with_partner' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_alone,cost_of_living_single_parent|numeric',
            'number_of_children' => 'required|numeric|between:0,100',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('cost');
    }

    public function mount(): void
    {
        $cost = Cost::where('application_id', session()->get('appl_id'))->first() ?? new Cost();

        $this->semester_fees = floatval($cost->semester_fees ?? 0);
        $this->fees = floatval($cost->fees ?? 0);
        $this->educational_material = floatval($cost->educational_material ?? 0);
        $this->excursion = floatval($cost->excursion ?? 0);
        $this->travel_expenses = floatval($cost->travel_expenses ?? 0);
        $this->number_of_children = floatval($cost->number_of_children ?? 0);
        $this->cost_of_living_with_parents = floatval($cost->cost_of_living_with_parents ?? 0);
        $this->cost_of_living_alone = floatval($cost->cost_of_living_alone ?? 0);
        $this->cost_of_living_single_parent = floatval($cost->cost_of_living_single_parent ?? 0);
        $this->cost_of_living_with_partner = floatval($cost->cost_of_living_with_partner ?? 0);

        $this->currency_id = Application::where('id', session()->get('appl_id'))->pluck('currency_id');
        $this->myCurrency = Currency::where('id', $this->currency_id)->first();
    }

    public function render()
    {
        return view('livewire.antrag.cost-form');
    }

    public function saveCost(): void
    {
        $validatedData = $this->validate();

        $cost = Cost::where('application_id', session()->get('appl_id'))->first() ?? new Cost();
        $cost->fill($validatedData);
        $cost->is_draft = false;
        $cost->user_id = auth()->user()->id;
        $cost->total_amount_costs = $this->getAmountCost();
        $cost->application_id = session()->get('appl_id');
        $cost->save();

        session()->flash('success', __('userNotification.costSaved'));
    }

    public function getAmountCost(): int
    {
        return (int) (
            floatval($this->semester_fees ?? 0) +
            floatval($this->fees ?? 0) +
            floatval($this->educational_material ?? 0) +
            floatval($this->excursion ?? 0) +
            floatval($this->travel_expenses ?? 0) +
            floatval($this->cost_of_living_with_parents ?? 0) +
            floatval($this->cost_of_living_alone ?? 0) +
            floatval($this->cost_of_living_single_parent ?? 0) +
            floatval($this->cost_of_living_with_partner ?? 0)
        );
    }
}
