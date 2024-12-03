<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class Applications extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Layout('components.layout.admin-dashboard')]
    public function render()
    {
        $applications = Application::with('user')
            ->whereIn('appl_status', ['pending', 'waiting', 'complete'])
            ->whereNull('deleted_at')
            ->paginate(10);

        return view('livewire.admin.applications', [
            'applications' => $applications,
        ]);
    }
}
