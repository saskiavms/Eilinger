<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class NotSend extends Component
{
	use WithPagination;

    protected $paginationTheme = 'tailwind';

    #[Layout('components.layout.admin-dashboard', ['header' => 'Nicht eingereicht'])]
    public function render()
    {
        return view('livewire.admin.not-send', [
            'applications' => Application::query()
                ->where('appl_status', 'Not Send')
                ->with(['user' => function ($query) {
                    $query->withTrashed(); // Include soft-deleted users
                }])
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
