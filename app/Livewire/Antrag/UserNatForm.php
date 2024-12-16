<?php

namespace App\Livewire\Antrag;

use App\Enums\Bewilligung;
use App\Enums\CivilStatus;
use App\Models\Country;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class UserNatForm extends Component
{
    public $firstname;
    public $lastname;
    public $birthday;
    public $salutation;
    public $nationality;
    public $civil_status;
    public $phone;
    public $mobile;
    public $soz_vers_nr;
    public $in_ch_since;
    public $granting;

    public $countries;
    public $partnerVisible = false;

    protected function rules(): array
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required|date',
            'salutation' => 'required',
            'nationality' => 'required',
            'civil_status' => ['required', new Enum(CivilStatus::class)],
            'phone' => 'sometimes',
            'mobile' => 'sometimes',
            'soz_vers_nr' => 'sometimes',
            'in_ch_since' => 'sometimes',
            'granting' => ['nullable', 'required_with:in_ch_since', new Enum(Bewilligung::class)],
        ];
    }

    public function validationAttributes()
    {
        return Lang::get('user');
    }

    public function mount()
    {
        $user = auth()->user();

        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->birthday = $user->birthday;
        $this->salutation = $user->salutation;
        $this->nationality = $user->nationality;
        $this->civil_status = $user->civil_status;
        $this->phone = $user->phone;
        $this->mobile = $user->mobile;
        $this->soz_vers_nr = $user->soz_vers_nr;
        $this->in_ch_since = $user->in_ch_since;
        $this->granting = $user->granting;

        $this->countries = Country::all();
    }

    public function render()
    {
        return view('livewire.antrag.user-nat-form');
    }

    public function saveUserNat()
    {
        $validatedData = $this->validate();

        $user = auth()->user();
        $user->fill($validatedData);
        $user->is_draft = false;
        $user->save();

        session()->flash('success', __('userNotification.userSaved'));
    }

    public function updatedCivilStatus($value): void
    {
        $civilStatus = CivilStatus::tryFrom($value);
        if ($civilStatus == CivilStatus::verheiratet) {
            $this->partnerVisible = true;
        } else {
            $this->partnerVisible = false;
        }
    }

    public function getState()
    {
        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'birthday' => $this->birthday,
            'salutation' => $this->salutation,
            'nationality' => $this->nationality,
            'civil_status' => $this->civil_status,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'soz_vers_nr' => $this->soz_vers_nr,
            'in_ch_since' => $this->in_ch_since,
            'granting' => $this->granting,
        ];
    }
}
