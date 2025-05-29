<?php

namespace App\Livewire\Antrag;

use App\Models\Address;
use App\Models\Application;
use App\Models\Country;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AddressForm extends Component
{
    public $street;
    public $number;
    public $town;
    public $plz;
    public $country_id;

    public $countries;
    public $application;
    public $isEditable = true;

    protected function rules(): array
    {
        return [
            'street' => 'required|min:3',
            'number' => 'nullable',
            'town' => 'required|min:3',
            'plz' => 'required|min:4',
            'country_id' => 'required',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('address');
    }

    public function mount()
    {
        $this->application = Application::find(session()->get('appl_id'));
        $this->isEditable = $this->application ? $this->application->isEditable() : true;
        
        $this->countries = Country::all();
        $address = Address::loggedInUser()->first();

        // Initialize properties from address model
        $this->street = $address->street;
        $this->number = $address->number;
        $this->town = $address->town;
        $this->plz = $address->plz;
        $this->country_id = $address->country_id;
    }

    public function render()
    {
        return view('livewire.antrag.address-form');
    }

    public function saveAddress()
    {
        if (!$this->isEditable) {
            session()->flash('error', __('application.edit_restriction_error'));
            return;
        }
        
        $validatedData = $this->validate();

        $address = Address::loggedInUser()->first();
        $address->fill($validatedData);
        $address->is_draft = false;
        $address->save();

        session()->flash('success', __('userNotification.addressSaved'));
    }
}
