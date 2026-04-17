<?php

namespace App\Livewire\Admin;

use App\Enums\Salutation;
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

    // Edit modal
    public bool $showEditModal = false;
    public ?int $editUserId = null;
    public string $editSalutation = '';
    public string $editFirstname = '';
    public string $editLastname = '';
    public string $editEmail = '';
    public string $editPhone = '';
    public string $editMobile = '';

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

    public function openEditModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editUserId = $user->id;
        $this->editSalutation = $user->salutation instanceof Salutation ? $user->salutation->value : (string) $user->salutation;
        $this->editFirstname = $user->firstname ?? '';
        $this->editLastname = $user->lastname ?? '';
        $this->editEmail = $user->email ?? '';
        $this->editPhone = $user->phone ?? '';
        $this->editMobile = $user->mobile ?? '';
        $this->showEditModal = true;
    }

    public function saveContact(): void
    {
        $this->validate([
            'editSalutation' => 'required',
            'editFirstname'  => 'required|min:2',
            'editLastname'   => 'required|min:2',
            'editEmail'      => 'required|email|unique:users,email,' . $this->editUserId,
            'editPhone'      => 'nullable',
            'editMobile'     => 'nullable',
        ], [
            'editSalutation.required' => 'Anrede ist erforderlich.',
            'editFirstname.required'  => 'Vorname ist erforderlich.',
            'editFirstname.min'       => 'Vorname muss mindestens 2 Zeichen haben.',
            'editLastname.required'   => 'Nachname ist erforderlich.',
            'editLastname.min'        => 'Nachname muss mindestens 2 Zeichen haben.',
            'editEmail.required'      => 'E-Mail ist erforderlich.',
            'editEmail.email'         => 'Ungültige E-Mail-Adresse.',
            'editEmail.unique'        => 'Diese E-Mail-Adresse ist bereits vergeben.',
        ]);

        User::findOrFail($this->editUserId)->update([
            'salutation' => $this->editSalutation,
            'firstname'  => $this->editFirstname,
            'lastname'   => $this->editLastname,
            'email'      => $this->editEmail,
            'phone'      => $this->editPhone,
            'mobile'     => $this->editMobile,
        ]);

        $this->showEditModal = false;
        $this->editUserId = null;
        session()->flash('success', 'Kontaktperson wurde aktualisiert.');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editUserId = null;
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
