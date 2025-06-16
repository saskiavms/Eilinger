<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Cost;
use App\Models\Currency;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class CostForm extends Component
{
    public ?float $semester_fees = null;
    public ?float $fees = null;
    public ?float $educational_material = null;
    public ?float $excursion = null;
    public ?float $travel_expenses = null;
    public ?float $number_of_children = null;
    public ?float $cost_of_living_with_parents = null;
    public ?float $cost_of_living_alone = null;
    public ?float $cost_of_living_single_parent = null;
    public ?float $cost_of_living_with_partner = null;

    public $currency_id;
    public $myCurrency;
    public $application;
    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'semester_fees' => 'required|numeric|min:0',
            'fees' => 'required|numeric|min:0',
            'educational_material' => 'required|numeric|min:0',
            'excursion' => 'required|numeric|min:0',
            'travel_expenses' => 'required|numeric|min:0',
            'cost_of_living_with_parents' => 'nullable|required_without_all:cost_of_living_alone,cost_of_living_single_parent,cost_of_living_with_partner|numeric|min:0',
            'cost_of_living_alone' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_single_parent,cost_of_living_with_partner|numeric|min:0',
            'cost_of_living_single_parent' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_alone,cost_of_living_with_partner|numeric|min:0',
            'cost_of_living_with_partner' => 'nullable|required_without_all:cost_of_living_with_parents,cost_of_living_alone,cost_of_living_single_parent|numeric|min:0',
            'number_of_children' => 'required|numeric|between:0,100',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('cost');
    }


    public function mount(): void
    {
        $this->application = Application::find(session()->get('appl_id'));
        $this->isEditable = $this->application ? $this->application->isEditable() : true;
        
        $cost = Cost::where('application_id', session()->get('appl_id'))->first();

        if ($cost && !$cost->is_draft) {
            // Load saved data
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
        } else {
            // Initialize as null for new forms to trigger required validation
            $this->semester_fees = null;
            $this->fees = null;
            $this->educational_material = null;
            $this->excursion = null;
            $this->travel_expenses = null;
            $this->number_of_children = null;
            $this->cost_of_living_with_parents = null;
            $this->cost_of_living_alone = null;
            $this->cost_of_living_single_parent = null;
            $this->cost_of_living_with_partner = null;
        }

        $this->currency_id = Application::where('id', session()->get('appl_id'))->pluck('currency_id');
        $this->myCurrency = Currency::where('id', $this->currency_id)->first();
    }

    public function render()
    {
        return view('livewire.antrag.cost-form');
    }

    public function saveCost(): void
    {
        if (!$this->isEditable) {
            session()->flash('error', __('application.edit_restriction_error'));
            return;
        }
        
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
