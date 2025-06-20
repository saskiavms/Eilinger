<?php

namespace App\Livewire\User;

use App\Models\Application;
use Livewire\Component;

class Stipendium extends Component
{
    public $currentStep = 1;

    public $showModal = false;

    public $completeApp = false;

    public $isInitialAppl;

    protected $listeners = ['completeApp' => 'completeApp'];

    public function mount()
    {
        $this->isInitialAppl = Application::where('id', session()->get('appl_id'))->first(['is_first'])->is_first;
    }

    public function completeApp()
    {
        $this->completeApp = true;
    }

    public function render()
    {
        return view('livewire.user.stipendium');
    }

    public function increaseStep()
    {
        $this->currentStep++;
        
        // Reset completeApp when navigating to sending form step
        if ($this->currentStep == 12) {
            $this->completeApp = false;
        }
    }

    public function decreaseStep()
    {
        $this->currentStep--;
        
        // Reset completeApp when navigating to sending form step
        if ($this->currentStep == 12) {
            $this->completeApp = false;
        }
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
