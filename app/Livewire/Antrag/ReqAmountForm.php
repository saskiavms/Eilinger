<?php

namespace App\Livewire\Antrag;

use App\Enums\Form;
use App\Enums\Types;
use App\Models\Application;
use App\Models\Cost;
use App\Models\CostDarlehen;
use App\Models\Financing;
use App\Models\FinancingOrganisation;
use Livewire\Component;

class ReqAmountForm extends Component
{
    public $req_amount;
    public $payout_plan;
    public $total_amount_financing = 0;
    public $total_amount_costs = 0;
    public $diffAmount = 0;
    public $application;

    protected function rules(): array
    {
        return [
            'req_amount' => 'required|numeric',
            'payout_plan' => 'sometimes',
        ];
    }

    public function mount(): void
    {
        $this->application = Application::where('id', session()->get('appl_id'))->first();

        // Get financing amount based on user type - using raw value comparison
        if ($this->application->user->getRawOriginal('type') === Types::nat->value) {
            $this->total_amount_financing = Financing::where('application_id', session()->get('appl_id'))
                ->value('total_amount_financing') ?? 0;
        } else {
            $this->total_amount_financing = FinancingOrganisation::where('application_id', session()->get('appl_id'))
                ->value('financing_amount') ?? 0;
        }

        // Get costs based on application form type
        if ($this->application->form->value === Form::Stipendium->value) {
            $this->total_amount_costs = Cost::where('application_id', session()->get('appl_id'))
                ->value('total_amount_costs') ?? 0;
        } else {
            $this->total_amount_costs = CostDarlehen::where('application_id', session()->get('appl_id'))
                ->value('cost_amount') ?? 0;
        }

        // Debug output
        logger()->debug('Application Details:', [
            'user_type_value' => $this->application->user->type->value,
            'form_type_value' => $this->application->form->value,
            'form_enum_value' => Form::Stipendium->value,
            'comparison_result' => $this->application->form->value === Form::Stipendium->value
        ]);

        logger()->debug('Amounts:', [
            'financing' => $this->total_amount_financing,
            'costs' => [
                'value' => $this->total_amount_costs,
                'raw_query' => Cost::where('application_id', session()->get('appl_id'))
                    ->select('total_amount_costs')
                    ->toSql(),
                'bindings' => [session()->get('appl_id')]
            ],
            'difference' => $this->diffAmount
        ]);

        // Initialize form fields
        $this->req_amount = $this->application->req_amount;
        $this->payout_plan = $this->application->payout_plan;

        // Calculate initial difference
        $this->calculateDifference();
    }

    public function calculateDifference(): void
    {
        $this->diffAmount = $this->total_amount_costs - $this->total_amount_financing;
    }

    public function render()
    {
        return view('livewire.antrag.req-amount-form');
    }

    public function saveReqAmount(): void
    {
        $validatedData = $this->validate();

        $this->application->req_amount = $validatedData['req_amount'];
        $this->application->payout_plan = $validatedData['payout_plan'];
        $this->application->calc_amount = $this->diffAmount;
        $this->application->save();

        session()->flash('success', __('userNotification.reqAmountSaved'));
    }

    public function updated($field): void
    {
        if ($field === 'req_amount') {
            $this->calculateDifference();
        }
    }
}
