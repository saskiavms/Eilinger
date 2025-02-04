<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Blocked extends Component
{
	use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Layout('components.layout.admin-dashboard', ['header' => 'Abgelehnt'])]
    public function render()
    {
        return view('livewire.admin.blocked', [
            'applications' => Application::query()
                ->where('appl_status', 'blocked')
                ->with(['user']) // Only eager load the user relationship
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
