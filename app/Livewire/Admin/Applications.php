<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Enums\ApplStatus;
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
        $applications = Application::with([
                'user',
                'user.applications' => function ($query) {
                    $query->whereIn('appl_status', [ApplStatus::APPROVED, ApplStatus::BLOCKED, ApplStatus::FINISHED])
                        ->whereNull('deleted_at');
                },
            ])
            ->whereIn('appl_status', [ApplStatus::PENDING, ApplStatus::WAITING, ApplStatus::COMPLETE])
            ->whereNull('deleted_at')
            ->when($this->filterBereich, function ($query) {
                $query->where('bereich', $this->filterBereich);
            })
            ->paginate(10, pageName: 'currentPage');

        return view('livewire.admin.applications', [
            'applications' => $applications,
        ]);
    }
}
