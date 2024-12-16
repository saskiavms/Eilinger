<?php

namespace App\Livewire\Antrag;

use App\Models\Country;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class UserJurForm extends Component
{
    public $name_inst;
    public $phone_inst;
    public $email_inst;
    public $website;
    public $firstname;
    public $lastname;
    public $salutation;
    public $phone;
    public $mobile;
    public $contact_aboard;

    protected function rules(): array
    {
        return [
            'name_inst' => 'required',
            'phone_inst' => 'required',
            'email_inst' => 'required|email:strict',
            'website' => 'sometimes',
            'firstname' => 'required',
            'lastname' => 'required',
            'salutation' => 'required',
            'phone' => 'nullable',
            'mobile' => 'nullable',
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
        $this->name_inst = $user->name_inst;
        $this->phone_inst = $user->phone_inst;
        $this->email_inst = $user->email_inst;
        $this->website = $user->website;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->salutation = $user->salutation ?? '';
        $this->phone = $user->phone;
        $this->mobile = $user->mobile;
        $this->contact_aboard = $user->contact_aboard;
    }

    public function saveUserJur(): void
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
        return view('livewire.antrag.user-jur-form');
    }
}
