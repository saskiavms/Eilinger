<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApplicationProgress extends Component
{
    public function __construct(
        public int $currentStep,
        public int $totalSteps
    ) {}

    public function render()
    {
        return view('components.application-progress');
    }
}
