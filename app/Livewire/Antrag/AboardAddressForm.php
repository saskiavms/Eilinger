<?php

namespace App\Livewire\Antrag;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AboardAddressForm extends Component
{
    public $street;
    public $number;
    public $town;
    public $plz;
    public $country_id;
    public $countries;

    protected function rules(): array
    {
        return [
            'street' => 'required',
            'number' => 'nullable',
            'town' => 'required',
            'plz' => 'required',
            'country_id' => 'required',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('address');
    }

    public function mount(): void
    {
        $this->countries = Country::all();

        $aboardAddress = Address::loggedInUser()
            ->where('is_aboard', 1)
            ->first();

        if ($aboardAddress) {
            $this->street = $aboardAddress->street;
            $this->number = $aboardAddress->number;
            $this->town = $aboardAddress->town;
            $this->plz = $aboardAddress->plz;
            $this->country_id = $aboardAddress->country_id;
        }
    }

    public function saveaboardAddress(): void
    {
        $validatedData = $this->validate();

        $address = Address::loggedInUser()
            ->where('is_aboard', 1)
            ->first() ?? new Address;

        $address->fill($validatedData);
        $address->is_draft = false;
        $address->user_id = auth()->user()->id;
        $address->is_aboard = true;
        $address->save();

        session()->flash('success', __('userNotification.addressAboardSaved'));
    }

    public function render()
    {
        return view('livewire.antrag.aboard-address-form');
    }
}
