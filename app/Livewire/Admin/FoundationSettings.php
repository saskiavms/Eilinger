<?php

namespace App\Livewire\Admin;

use App\Models\Foundation;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;

class FoundationSettings extends Component
{
    public $name;
	public $strasse;
	public $ort;
	public $land;
	public $nextCouncilMeeting;
	public $nextCouncilMeetingNote_de;
	public $nextCouncilMeetingNote_en;

    protected $rules = [
        'name' => 'required|string|max:255',
        'strasse' => 'required|string|max:255',
        'ort' => 'required|string|max:255',
        'land' => 'required|string|max:255',
        'nextCouncilMeeting' => 'nullable|date',
        'nextCouncilMeetingNote_de' => 'nullable|string|max:500',
        'nextCouncilMeetingNote_en' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $foundation = Foundation::first();
		$this->name = $foundation->name;
		$this->strasse = $foundation->strasse;
		$this->ort = $foundation->ort;
		$this->land = $foundation->land;
		$this->nextCouncilMeeting = $foundation->nextCouncilMeeting;
		$this->nextCouncilMeetingNote_de = $foundation->nextCouncilMeetingNote_de;
		$this->nextCouncilMeetingNote_en = $foundation->nextCouncilMeetingNote_en;
    }

    public function save()
    {
        $validatedData = $this->validate();
		$foundation = Foundation::first();
        $foundation->fill($validatedData);
		$foundation->save();

        session()->flash('message', 'Foundation settings updated successfully.');
    }

	#[Layout('components.layout.admin-dashboard')]
	public function render()
    {
        return view('livewire.admin.foundation-settings');
    }
}
