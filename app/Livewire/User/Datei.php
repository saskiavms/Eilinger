<?php

namespace App\Livewire\User;

use App\Models\Application;
use App\Models\Enclosure;
use App\View\Components\Layout\UserDashboard;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class Datei extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $enclosures;
    public $applications;
    public $userName;
    public $application_id;
    public $column;
    public $file;
    public $columns = [];
    public $enclosure;

    protected function rules(): array
    {
        return [
            'application_id' => 'required',
            'column' => 'required',
            'file' => 'required',
        ];
    }

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->applications = Application::with('enclosures')->LoggedInUser()->get();

        $user = auth()->user();
        $this->userName = "{$user->lastname}_{$user->firstname}";
    }

    public function render()
    {
        return view('livewire.user.datei')
            ->layout(UserDashboard::class);
    }

    public function addEnclosure()
    {
        $this->showModal = true;
    }

    public function saveEnclosure()
    {
        $this->validate();

        try {
            $fileName = $this->generateFileName();
            $filePath = $this->storeFile($fileName);

            $this->enclosure = $this->enclosure ?? new Enclosure();
            $this->enclosure->application_id = $this->application_id;
            $this->enclosure->{$this->column} = $filePath;
            $this->enclosure->save();

            $this->resetForm();
            $this->showModal = false;
            $this->dispatch('fileUploaded');
            session()->flash('message', 'File uploaded successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error uploading file: ' . $e->getMessage());
        }
    }

    public function updatedApplicationId($application_id)
    {
        $this->enclosure = Enclosure::where('application_id', $application_id)->first() ?? new Enclosure;;
        $this->columns = $this->enclosure->exists
            ? Schema::getColumnListing($this->enclosure->getTable())
            : [];
    }

    public function close()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    private function generateFileName()
    {
        return "Appl{$this->application_id}_{$this->column}.{$this->file->getClientOriginalExtension()}";
    }

    private function storeFile($fileName)
    {
        return $this->file->storeAs($this->userName, $fileName, 'uploads');
    }

    private function resetForm()
    {
        $this->reset(['application_id', 'column', 'file']);
    }
}
