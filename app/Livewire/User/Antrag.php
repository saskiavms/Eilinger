<?php

namespace App\Livewire\User;

use App\Enums\ApplStatus;
use App\Models\Application;
use App\Models\User;
use App\Notifications\NewApplication;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Antrag extends Component
{
    public $currentStep = 1;

    public $application;

    protected $listeners = ['sendApplication' => 'sendApplication'];

    public function mount($application_id)
    {
        $this->application = Application::where('id', $application_id)->first();
        session(['appl_id' => $application_id]);
    }

    #[Layout('components.layout.user-dashboard')]
    public function render()
    {
        return view('livewire.user.antrag');
    }

    public function sendApplication()
    {
        $this->application->appl_status = ApplStatus::PENDING;
        $this->application->save();
        $admins = User::where('is_admin', 1)->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewApplication($this->application));
        }
        redirect()->route('user_gesuch', app()->getLocale());
    }

    public function increaseStep()
    {
        $this->currentStep++;
    }

    public function decreaseStep()
    {
        $this->currentStep--;
    }
}
