<?php

namespace App\Livewire\Antrag;

use App\Models\Account;
use App\Models\Application;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AccountForm extends Component
{
    public $name_bank;

    public $city_bank;

    public $owner;

    public $IBAN;

    public $application;

    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'name_bank' => 'required',
            'city_bank' => 'required',
            'owner' => 'required',
            'IBAN' => 'required|regex:/^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('account');
    }

    public function mount()
    {
        $this->application = Application::find(session()->get('appl_id'));
        $this->isEditable = $this->application ? $this->application->isEditable() : true;

        $account = Account::loggedInUser()
            ->where('application_id', session()->get('appl_id'))
            ->first() ?? new Account;

        $this->name_bank = $account->name_bank;
        $this->city_bank = $account->city_bank;
        $this->owner = $account->owner;
        $this->IBAN = $account->IBAN;
    }

    public function render()
    {
        return view('livewire.antrag.account-form');
    }

    public function saveAccount()
    {
        // Prevent saving if application is not editable
        if (! $this->isEditable) {
            session()->flash('error', __('application.edit_restriction_error'));

            return;
        }

        $validatedData = $this->validate();

        $account = Account::loggedInUser()
            ->where('application_id', session()->get('appl_id'))
            ->first() ?? new Account;

        $account->fill($validatedData);
        $account->is_draft = false;
        $account->user_id = auth()->user()->id;
        $account->application_id = session()->get('appl_id');
        $account->save();

        session()->flash('success', __('userNotification.accountSaved'));
    }
}
