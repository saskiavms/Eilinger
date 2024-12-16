<?php

namespace App\Livewire\Antrag;

use App\Models\Account;
use App\Models\Address;
use App\Models\CostDarlehen;
use App\Models\Enclosure;
use App\Models\Financing;
use App\Models\FinancingOrganisation;
use App\Models\User;
use Livewire\Component;

class SendingDarlehenForm extends Component
{
    public $userNoDraft;
    public $addressNoDraft;
    public $aboardAddressNoDraft;
    public $costNoDraft;
    public $accountNoDraft;
    public $financingNoDraft;
    public $financingOrganisationNoDraft;
    public $enclosureNoDraft;
    private bool $completeApp;

    public function mount(): void
    {
        $userId = auth()->id();
        $applId = session()->get('appl_id');

        $this->userNoDraft = User::where('id', $userId)
            ->where('is_draft', false)
            ->exists();

        $this->addressNoDraft = Address::where('user_id', $userId)
            ->where('is_wochenaufenthalt', 0)
            ->where('is_draft', false)
            ->exists();

        $this->aboardAddressNoDraft = Address::where('user_id', $userId)
            ->where('is_aboard', 1)
            ->where('is_draft', false)
            ->exists();

        $this->accountNoDraft = Account::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->costNoDraft = CostDarlehen::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->financingNoDraft = Financing::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->financingOrganisationNoDraft = FinancingOrganisation::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->enclosureNoDraft = Enclosure::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();
    }

    public function completeApplication(): void
    {
        if ($this->userNoDraft &&
            $this->addressNoDraft &&
            $this->accountNoDraft &&
            $this->costNoDraft &&
            ($this->financingNoDraft || $this->financingOrganisationNoDraft) &&
            $this->enclosureNoDraft) {
            $this->completeApp = true;
            $this->dispatch('completeApp');
        }
    }

    public function render()
    {
        return view('livewire.antrag.sending-darlehen-form');
    }
}
