<?php

namespace App\Livewire\User;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Layout;

class DeleteAccount extends Component
{
    public $showModal = false;
    public $current_password;

    protected $rules = [
        'current_password' => 'required|current-password',
    ];

    #[Layout('components.layout.user-dashboard')]
    public function render()
    {
        return view('livewire.user.delete-account');
    }

    public function destroy(Request $request)
    {
        $this->validate();

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index', app()->getLocale());
    }

    public function delete()
    {
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
        $this->current_password = '';
    }
}
