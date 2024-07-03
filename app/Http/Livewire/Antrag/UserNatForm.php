<?php

namespace App\Http\Livewire\Antrag;

use App\Enums\Bewilligung;
use App\Enums\CivilStatus;
use App\Models\Country;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class UserNatForm extends Component
{
    public $user;

    public $countries;

    public $partnerVisible = false;

    protected function rules(): array
    {
        return [
            'user.firstname' => 'required',
            'user.lastname' => 'required',
            'user.birthday' => 'required|date',
            'user.salutation' => 'required',
            'user.nationality' => 'required',
            'user.civil_status' => ['required', new Enum(CivilStatus::class)],
            'user.phone' => 'sometimes',
            'user.mobile' => 'sometimes',
            'user.soz_vers_nr' => 'sometimes',
            'user.in_ch_since' => 'sometimes',
            'user.granting' => ['nullable', 'required_with:user.in_ch_since', new Enum(Bewilligung::class)],
        ];
    }

    public function validationAttributes()
    {
        return Lang::get('user');
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->countries = Country::all();
    }

    public function render()
    {
        return view('livewire.antrag.user-nat-form');
    }

    public function saveUserNat()
    {
        $this->validate();
        $this->user->is_draft = false;
        $this->user->save();
        session()->flash('success', __('userNotification.userSaved'));
    }

    public function updatedCivilStatus($value): void
    {
        $civilStatus = CivilStatus::tryFrom($value);
        if ($civilStatus == CivilStatus::verheiratet) {
            $this->partnerVisible = true;
        }
    }
}
