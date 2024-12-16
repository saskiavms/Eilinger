<?php

namespace App\Livewire\Antrag;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AbweichendeAddressForm extends Component
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

    public function mount()
    {
        $this->countries = Country::all();
        $address = Address::loggedInUser()
            ->where('is_wochenaufenthalt', 1)
            ->first() ?? new Address;

        // Initialize properties from address model
        $this->street = $address->street;
        $this->number = $address->number;
        $this->town = $address->town;
        $this->plz = $address->plz;
        $this->country_id = $address->country_id;
    }

    public function render()
    {
        return view('livewire.antrag.abweichende-address-form');
    }

    public function saveAbweichendeAddress()
    {
        $validatedData = $this->validate();

        $address = Address::loggedInUser()
            ->where('is_wochenaufenthalt', 1)
            ->first() ?? new Address;

        $address->fill($validatedData);
        $address->is_draft = false;
        $address->user_id = auth()->user()->id;
        $address->is_wochenaufenthalt = true;
        $address->save();

        session()->flash('success', __('userNotification.addressWeeklySaved'));
    }
}
