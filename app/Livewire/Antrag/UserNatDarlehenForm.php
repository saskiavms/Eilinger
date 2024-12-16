<?php

namespace App\Livewire\Antrag;

use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class UserNatDarlehenForm extends Component
{
    public $firstname;
    public $lastname;
    public $birthday;
    public $salutation;
    public $phone;
    public $mobile;
    public $contact_aboard;

    protected function rules(): array
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required|date',
            'salutation' => 'required',
            'phone' => 'required',
            'mobile' => 'sometimes',
            'contact_aboard' => 'sometimes',
        ];
    }

    public function validationAttributes(): array
    {
        return Lang::get('user');
    }

    public function mount(): void
    {
        $user = auth()->user();
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->birthday = $user->birthday;
        $this->salutation = $user->salutation ?? '';
        $this->phone = $user->phone;
        $this->mobile = $user->mobile;
        $this->contact_aboard = $user->contact_aboard;
    }

    public function saveUserNat(): void
    {
        $validatedData = $this->validate();

        $user = auth()->user();
        $user->fill($validatedData);
        $user->is_draft = false;
        $user->save();

        session()->flash('success', __('userNotification.userSaved'));
    }

    public function render()
    {
        return view('livewire.antrag.user-nat-darlehen-form');
    }
}
