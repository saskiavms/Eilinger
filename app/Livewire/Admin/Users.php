<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    #[Layout('components.layout.admin-dashboard')]
    #[Title('Benutzerübersicht')]

    public $searchUsername = '';
    public $searchUserEmail = '';
    public $searchNameInst = '';
    public $filterBereich = '';
    public $filterStatus = '';

    protected $queryString = [
        'searchUsername' => ['except' => ''],
        'searchUserEmail' => ['except' => ''],
        'searchNameInst' => ['except' => ''],
        'filterBereich' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatedSearchUsername()
    {
        $this->resetPage();
    }

    public function updatedSearchUserEmail()
    {
        $this->resetPage();
    }

    public function updatedSearchNameInst()
    {
        $this->resetPage();
    }

    public function updatedFilterBereich()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::with(['lastLogin', 'sendApplications'])
            ->where('is_admin', 0)
            ->when($this->searchUsername, function ($query) {
                $query->where('username', 'like', '%' . $this->searchUsername . '%');
            })
            ->when($this->searchUserEmail, function ($query) {
                $query->where('email', 'like', '%' . $this->searchUserEmail . '%');
            })
            ->when($this->searchNameInst, function ($query) {
                $query->where('name_inst', 'like', '%' . $this->searchNameInst . '%');
            })
            ->orderBy('lastname')
            ->paginate(20);

        return view('livewire.admin.users', [
            'users' => $users,
        ]);
    }
}
