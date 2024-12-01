<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UploadEnclosure extends Component
{

    public $field;
    public $label;
    public $enclosure;
    public $sendLater;
    public $mandatory = false;

    public function __construct($field, $label, $enclosure, $sendLater, $mandatory = false)
    {
        $this->field = $field;
        $this->label = $label;
        $this->enclosure = $enclosure;
        $this->sendLater = $sendLater;
        $this->mandatory = $mandatory;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.upload-enclosure');
    }
}
