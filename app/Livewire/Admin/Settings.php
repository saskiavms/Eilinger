<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Settings extends Component
{
    public $showModal = false;

    #[Rule('required|string')]
    public $salutation = '';

    #[Rule('required|string|unique:users,username')]
    public $username = '';

    #[Rule('required|string|max:255')]
    public $lastname = '';

    #[Rule('required|string|max:255')]
    public $firstname = '';

    #[Rule('required|string')]
    public $phone = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|min:8')]
    public $password = '';

    #[Layout('components.layout.admin-dashboard', ['header' => 'Einstellungen'])]
    public function render()
    {
        return view('livewire.admin.settings', [
            'users' => User::query()
                ->where('is_admin', true)
                ->orderBy('lastname')
                ->get()
        ]);
    }

    public function addAdmin()
    {
        $this->resetValidation();
        $this->reset(['salutation', 'username', 'lastname', 'firstname', 'phone', 'email', 'password']);
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'type' => 'nat',
            'salutation' => $this->salutation,
            'username' => $this->username,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'phone' => $this->phone,
            'email' => $this->email,
            'email_verified_at' => now(),
            'password' => Hash::make($this->password),
        ]);
		$user->is_admin = 1;
        $user->save();

        $this->reset(['showModal', 'salutation', 'username', 'lastname', 'firstname', 'phone', 'email', 'password']);
        $this->dispatch('notify', ['message' => 'Admin erfolgreich erstellt']);
    }

    public function close()
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function messages()
    {
        return [
            'username.required' => 'Bitte geben Sie einen Benutzernamen ein.',
            'username.unique' => 'Dieser Benutzername ist bereits vergeben.',
            'lastname.required' => 'Bitte geben Sie einen Nachnamen ein.',
            'firstname.required' => 'Bitte geben Sie einen Vornamen ein.',
            'email.required' => 'Bitte geben Sie eine E-Mail-Adresse ein.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'email.unique' => 'Diese E-Mail-Adresse ist bereits vergeben.',
            'phone.required' => 'Bitte geben Sie eine Telefonnummer ein.',
            'password.required' => 'Bitte geben Sie ein Passwort ein.',
            'password.min' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
            'salutation.required' => 'Bitte wählen Sie eine Anrede aus.',
        ];
    }
}
