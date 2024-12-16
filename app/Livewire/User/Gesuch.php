<?php

namespace App\Livewire\User;

use App\Models\Application;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Gesuch extends Component
{
	#[Layout('components.layout.user-dashboard')]
    public function render()
    {
        $applications = Application::LoggedInUser()
            ->where('appl_status', '!=', 'not send')
            ->get();

        return view('livewire.user.gesuch', [
            'applications' => $applications,
        ]);
    }
}
