<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Currency;
use App\Models\Financing;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class FinancingForm extends Component
{
    public $personal_contribution;
    public $other_income;
    public $income_where;
    public $income_who;
    public $netto_income;
    public $assets;
    public $scholarship;

    public $currency_id;
    public $myCurrency;

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
        $financing = Financing::where('application_id', session()->get('appl_id'))
            ->first() ?? new Financing;

        $this->personal_contribution = $financing->personal_contribution;
        $this->other_income = $financing->other_income;
        $this->income_where = $financing->income_where;
        $this->income_who = $financing->income_who;
        $this->netto_income = $financing->netto_income;
        $this->assets = $financing->assets;
        $this->scholarship = $financing->scholarship;

        $this->currency_id = Application::where('id', session()->get('appl_id'))->pluck('currency_id');
        $this->myCurrency = Currency::where('id', $this->currency_id)->first();
    }

    public function saveFinancing(): void
    {
        $validatedData = $this->validate();

        $financing = Financing::where('application_id', session()->get('appl_id'))
            ->first() ?? new Financing;

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
        return (int) ($this->personal_contribution +
            $this->other_income +
            $this->netto_income +
            $this->assets +
            $this->scholarship);
    }

    public function render()
    {
        return view('livewire.antrag.financing-form');
    }
}
