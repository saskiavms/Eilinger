<?php

namespace App\Livewire\Admin;

use App\Models\Foundation;
use Livewire\Component;
use Livewire\Attributes\Layout;

class FoundationSettings extends Component
{
    public $foundation;

    protected $rules = [
        'foundation.name' => 'required|string|max:255',
        'foundation.strasse' => 'required|string|max:255',
        'foundation.ort' => 'required|string|max:255',
        'foundation.land' => 'required|string|max:255',
        'foundation.nextCouncilMeeting' => 'nullable|date',
    ];

    public function mount()
    {
        $this->foundation = Foundation::firstOrCreate([
            'name' => 'Eilinger Stiftung'
        ], [
            'strasse' => 'Seeweg 45',
            'ort' => '8264 Eschenz',
            'land' => 'Schweiz'
        ]);
    }

    public function save()
    {
        $this->validate();
        $this->foundation->save();

        session()->flash('message', 'Foundation settings updated successfully.');
    }

	#[Layout('components.layout.admin-dashboard')]
	public function render()
    {
        return view('livewire.admin.foundation-settings');
    }
}
