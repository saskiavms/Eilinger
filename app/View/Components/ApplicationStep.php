<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ApplicationStep extends Component
{
    public function __construct(
        public int $currentStep,
        public int $stepNumber,
        public int $totalSteps,
        public string $title,
        public string $component
    ) {}

    public function render()
    {
        return view('components.application-step');
    }
}
