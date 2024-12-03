<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Uebersicht extends Component
{
    #[Layout('components.layout.admin-dashboard')]
    public function render()
    {
        $userCount = User::where('is_admin', 0)->count();
        $applicationCount = Application::whereIn('appl_status', ['pending', 'waiting', 'complete'])->count();
        $projectCount = Application::where('appl_status', 'approved')->count();

        return view('livewire.admin.uebersicht', [
            'userCount' => $userCount,
            'applicationCount' => $applicationCount,
            'projectCount' => $projectCount,
        ]);
    }
}
