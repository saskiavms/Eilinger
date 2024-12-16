<?php

namespace App\Livewire\Antrag;

use App\Models\Account;
use App\Models\Address;
use App\Models\Cost;
use App\Models\Education;
use App\Models\Enclosure;
use App\Models\Financing;
use App\Models\Parents;
use App\Models\Sibling;
use App\Models\User;
use Livewire\Component;

class SendingForm extends Component
{
    public $userNoDraft;
    public $addressNoDraft;
    public $abweichendeAddressNoDraft;
    public $educationNoDraft;
    public $costNoDraft;
    public $parentsNoDraft;
    public $siblingNoDraft;
    public $accountNoDraft;
    public $financingNoDraft;
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

        $this->abweichendeAddressNoDraft = Address::where('user_id', $userId)
            ->where('is_wochenaufenthalt', 1)
            ->where('is_draft', false)
            ->exists();

        $this->educationNoDraft = Education::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->accountNoDraft = Account::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->costNoDraft = Cost::where('application_id', $applId)
            ->where('is_draft', false)
            ->exists();

        $this->parentsNoDraft = Parents::where('user_id', $userId)
            ->where('is_draft', false)
            ->exists();

        $this->siblingNoDraft = Sibling::where('user_id', $userId)
            ->where('is_draft', false)
            ->exists();

        $this->financingNoDraft = Financing::where('application_id', $applId)
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
            $this->educationNoDraft &&
            $this->accountNoDraft &&
            $this->costNoDraft &&
            $this->financingNoDraft &&
            $this->enclosureNoDraft) {
            $this->completeApp = true;
            $this->dispatch('completeApp');
        }
    }

    public function render()
    {
        return view('livewire.antrag.sending-form');
    }
}
