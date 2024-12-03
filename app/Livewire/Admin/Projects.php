<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Layout('components.layout.admin-dashboard')]
    public function render()
    {
        $applications = Application::where('appl_status', 'approved')->paginate(10);

        return view('livewire.admin.projects', [
            'applications' => $applications,
        ]);
    }
}
