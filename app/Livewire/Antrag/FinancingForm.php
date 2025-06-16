<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Currency;
use App\Models\Financing;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class FinancingForm extends Component
{
    public ?float $personal_contribution = null;
    public ?float $other_income = null;
    public ?string $income_where = null;
    public ?string $income_who = null;
    public ?float $netto_income = null;
    public ?float $assets = null;
    public ?float $scholarship = null;

    public $currency_id;
    public $myCurrency;
    public $application;
    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'personal_contribution' => 'required|numeric|min:0',
            'other_income' => 'nullable|numeric|min:0',
            'income_where' => 'required_unless:other_income,null,0,1',
            'income_who' => 'required_unless:other_income,null,0,1',
            'netto_income' => 'required|numeric|min:0',
            'assets' => 'required|numeric|min:0',
            'scholarship' => 'required|numeric|min:0',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('financing');
    }

    public function mount(): void
    {
        $this->application = Application::find(session()->get('appl_id'));
        $this->isEditable = $this->application ? $this->application->isEditable() : true;
        
        $financing = Financing::where('application_id', session()->get('appl_id'))->first();

        if ($financing && !$financing->is_draft) {
            // Load saved data
            $this->personal_contribution = floatval($financing->personal_contribution ?? 0);
            $this->other_income = floatval($financing->other_income ?? 0);
            $this->income_where = $financing->income_where;
            $this->income_who = $financing->income_who;
            $this->netto_income = floatval($financing->netto_income ?? 0);
            $this->assets = floatval($financing->assets ?? 0);
            $this->scholarship = floatval($financing->scholarship ?? 0);
        } else {
            // Initialize as null for new forms to trigger required validation
            $this->personal_contribution = null;
            $this->other_income = null;
            $this->income_where = null;
            $this->income_who = null;
            $this->netto_income = null;
            $this->assets = null;
            $this->scholarship = null;
        }

        $this->currency_id = Application::where('id', session()->get('appl_id'))->pluck('currency_id');
        $this->myCurrency = Currency::where('id', $this->currency_id)->first();
    }

    public function saveFinancing(): void
    {
        if (!$this->isEditable) {
            session()->flash('error', __('application.edit_restriction_error'));
            return;
        }
        
        $validatedData = $this->validate();

        $financing = Financing::where('application_id', session()->get('appl_id'))
            ->first() ?? new Financing();

        $financing->fill($validatedData);
        $financing->is_draft = false;
        $financing->user_id = auth()->user()->id;
        $financing->total_amount_financing = $this->getAmountFinancing();
        $financing->application_id = session()->get('appl_id');
        $financing->save();

        session()->flash('success', __('userNotification.financingSaved'));
    }

    public function getAmountFinancing(): int
    {
        return (int) (
            floatval($this->personal_contribution ?? 0) +
            floatval($this->other_income ?? 0) +
            floatval($this->netto_income ?? 0) +
            floatval($this->assets ?? 0) +
            floatval($this->scholarship ?? 0)
        );
    }

    public function render()
    {
        return view('livewire.antrag.financing-form');
    }
}
