<?php

namespace App\Livewire\User;

use App\Models\Application;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Message extends Component
{
    public $application;

    public function mount($application_id)
    {
        $this->application = Application::where('id', $application_id)
            ->with('messages')
            ->first();
    }

    #[Layout('components.layout.user-dashboard')]
    public function render()
    {
        return view('livewire.user.message');
    }
}
