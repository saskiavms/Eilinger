<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApplicationNavigation extends Component
{
    public function __construct(
        public int $currentStep,
        public int $totalSteps,
        public bool $completeApp = false,
        public bool $showModal = false
    ) {}

    public function render()
    {
        return view('components.application-navigation');
    }
}
