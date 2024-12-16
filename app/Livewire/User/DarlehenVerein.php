<?php

namespace App\Livewire\User;

use Livewire\Component;

class DarlehenVerein extends Component
{
    public $currentStep = 1;
    public $completeApp = false;
    public $showModal = false;

    protected $listeners = [
        'completeApp' => 'completeApp',
        'sendApplication' => 'sendApplication'
    ];

    public function mount(): void
    {
        $this->currentStep = 1;
    }

    public function increaseStep(): void
    {
        $this->currentStep++;
    }

    public function decreaseStep(): void
    {
        $this->currentStep--;
    }

    public function render()
    {
        return view('livewire.user.darlehen-verein');
    }

    public function completeApp()
    {
        $this->completeApp = true;
    }

    public function saveApplication()
    {
        $this->showModal = true;
    }

    public function save()
    {
        $this->dispatch('sendApplication');
        $this->showModal = false;
    }

    public function close()
    {
        $this->showModal = false;
    }
}
