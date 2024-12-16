<?php

namespace App\Livewire\User;

use Livewire\Component;

class DarlehenPrivat extends Component
{
    public $currentStep = 1;
    public $showModal = false;
    public $completeApp = false;

    protected $listeners = ['completeApp' => 'completeApp'];

    public function mount(): void
    {
        $this->currentStep = 1;
    }

    public function completeApp(): void
    {
        $this->completeApp = true;
    }

    public function increaseStep(): void
    {
        $this->currentStep++;
    }

    public function decreaseStep(): void
    {
        $this->currentStep--;
    }

    public function saveApplication(): void
    {
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->dispatch('sendApplication');
        $this->showModal = false;
    }

    public function close(): void
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.user.darlehen-privat');
    }
}
