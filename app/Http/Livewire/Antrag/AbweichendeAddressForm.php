<?php

namespace App\Http\Livewire\Antrag;

use App\Models\Address;
use App\Models\Country;
use Livewire\Component;

class AbweichendeAddressForm extends Component
{
    public $abweichendeAddress;
    public $countries;

    protected $rules = [
        'abweichendeAddress.street' => 'nullable',
        'abweichendeAddress.number' => 'nullable',
        'abweichendeAddress.town' => 'nullable',
        'abweichendeAddress.plz' => 'nullable',
        'abweichendeAddress.country_id' => 'nullable',
    ];

    public function mount()
    {
        $this->countries = Country::all();
        $this->abweichendeAddress = Address::where('user_id', auth()->user()->id)
            ->where('is_wochenaufenthalt', 1)->first() ?? new Address;
    }

    public function render()
    {
        return view('livewire.antrag.abweichende-address-form');
    }

    public function saveAbweichendeAddress()
    {
        $this->validate();
        $this->abweichendeAddress->is_draft = false;
        $this->abweichendeAddress->user_id = auth()->user()->id;
        $this->abweichendeAddress->is_wochenaufenthalt = true;
        $this->abweichendeAddress->save();
        session()->flash('success', 'Adresse Wochenaufenthalt aktualisiert.');
    }
}
