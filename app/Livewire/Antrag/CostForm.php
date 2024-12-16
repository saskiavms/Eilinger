<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Cost;
use App\Models\Currency;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CostForm extends Component
{
    public $semester_fees;
    public $fees;
    public $educational_material;
    public $excursion;
    public $travel_expenses;
    public $number_of_children;
    public $cost_of_living_with_parents;
    public $cost_of_living_alone;
    public $cost_of_living_single_parent;
    public $cost_of_living_with_partner;

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
        $cost = Cost::where('application_id', session()->get('appl_id'))->first() ?? new Cost;

        $this->semester_fees = $cost->semester_fees;
        $this->fees = $cost->fees;
        $this->educational_material = $cost->educational_material;
        $this->excursion = $cost->excursion;
        $this->travel_expenses = $cost->travel_expenses;
        $this->number_of_children = $cost->number_of_children;
        $this->cost_of_living_with_parents = $cost->cost_of_living_with_parents;
        $this->cost_of_living_alone = $cost->cost_of_living_alone;
        $this->cost_of_living_single_parent = $cost->cost_of_living_single_parent;
        $this->cost_of_living_with_partner = $cost->cost_of_living_with_partner;

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

        $cost = Cost::where('application_id', session()->get('appl_id'))->first() ?? new Cost;
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
        return (int) ($this->semester_fees +
            $this->fees +
            $this->educational_material +
            $this->excursion +
            $this->travel_expenses +
            $this->cost_of_living_with_parents +
            $this->cost_of_living_alone +
            $this->cost_of_living_single_parent +
            $this->cost_of_living_with_partner);
    }
}
