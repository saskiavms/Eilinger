<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Layout('components.layout.admin-dashboard', ['header' => 'Projektübersicht'])]
    public function render()
    {
        return view('livewire.admin.projects', [
            'applications' => Application::query()
                ->where('appl_status', 'approved')
                ->with(['user', 'bereich']) // Eager load relationships
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
