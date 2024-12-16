<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Applications extends Component
{
    use WithPagination;

    #[Layout('components.layout.admin-dashboard')]
    #[Title('Antragsübersicht')]

    public $filterBereich = '';

    protected $queryString = [
        'filterBereich' => ['except' => ''],
    ];

    public function updatedFilterBereich()
    {
        $this->resetPage();
    }

    public function render()
    {
        $applications = Application::with('user')
            ->whereIn('appl_status', ['pending', 'waiting', 'complete'])
            ->whereNull('deleted_at')
            ->when($this->filterBereich, function ($query) {
                $query->where('bereich', $this->filterBereich);
            })
            ->paginate(10);

        return view('livewire.admin.applications', [
            'applications' => $applications,
        ]);
    }
}
