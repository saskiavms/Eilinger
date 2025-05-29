<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Currency;
use App\Models\Financing;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class FinancingForm extends Component
{
    public ?float $personal_contribution = 0;
    public ?float $other_income = 0;
    public ?string $income_where = '';
    public ?string $income_who = '';
    public ?float $netto_income = 0;
    public ?float $assets = 0;
    public ?float $scholarship = 0;

    public $currency_id;
    public $myCurrency;
    public $application;
    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'personal_contribution' => 'required|numeric',
            'other_income' => 'nullable|numeric',
            'income_where' => 'required_unless:other_income,null,0,1',
            'income_who' => 'required_unless:other_income,null,0,1',
            'netto_income' => 'required|numeric',
            'assets' => 'required|numeric',
            'scholarship' => 'required|numeric',
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
        
        $financing = Financing::where('application_id', session()->get('appl_id'))
            ->first() ?? new Financing();

        $this->personal_contribution = floatval($financing->personal_contribution ?? 0);
        $this->other_income = floatval($financing->other_income ?? 0);
        $this->income_where = $financing->income_where;
        $this->income_who = $financing->income_who;
        $this->netto_income = floatval($financing->netto_income ?? 0);
        $this->assets = floatval($financing->assets ?? 0);
        $this->scholarship = floatval($financing->scholarship ?? 0);

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
