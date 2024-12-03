<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $searchUsername;

    public $searchUserEmail;

    public $filterStatus;

    public $filterBereich;

    public $searchNameInst;

    public $sortCol;
    public $sortAsc = false;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->searchUsername = '';
        $this->searchUserEmail = '';
        $this->searchNameInst = '';
        $this->filterBereich = '';
        $this->filterStatus = '';
    }

    protected function applySorting($query)
    {
    }

    protected function searchUsername($query)
    {
        return $this->searchUsername === ''
            ? $query
            : $query->where('username', 'like', '%' . $this->searchUsername . '%');
    }

    protected function searchUserEmail($query)
    {
        return $this->searchUserEmail === ''
            ? $query
            : $query->where('email', 'like', '%' . $this->searchUserEmail . '%');
        ;
    }

    protected function searchNameInst($query)
    {
        return $this->searchNameInst === ''
            ? $query
            : $query->where('name_inst', 'like', '%' . $this->searchNameInst . '%');
        ;
    }

    protected function filterBereich($query)
    {
        return $this->filterBereich === ''
            ? $query
            : $query->where('bereich', 'like', '%' . $this->filterBereich . '%');
        ;
    }

    protected function filterStatus($query)
    {
        return $this->filterStatus === ''
            ? $query
            : $query->where('status', 'like', '%' . $this->filterStatus . '%');
        ;
    }

    #[Layout('components.layout.admin-dashboard')]
    public function render()
    {
        $users = User::with('lastLogin')
            ->where('is_admin', 0)
            ->with('sendApplications')
            ->orderBy('lastname')
            ->when($this->searchUsername != '', function ($query) {
                $this->searchUsername($query);
            })
            ->when($this->searchUserEmail != '', function ($query) {
                $this->searchUserEmail($query);
            })
            ->when($this->searchNameInst != '', function ($query) {
                $this->searchNameInst($query);
            })
            ->when($this->filterBereich != '', function ($query) {
                // Add your filter logic here
            })
            ->paginate(20);


        return view('livewire.admin.users', [
            'users' => $users,
        ]);
    }
}
