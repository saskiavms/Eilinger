<?php

namespace App\Livewire\User;

use App\Models\Application;
use App\Models\Enclosure;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Datei extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $applications;
    public $userName;
    public $application_id;
    public $column;
    public $file;
    public $columns = [];
    public $enclosure;

    // Search/Filter properties
    public $applicationFilter = '';
    public $contentFilter = '';

    // Add a computed property for available content types
    public function getContentTypesProperty()
    {
        return Enclosure::query()
            ->whereHas('application', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get()
            ->flatMap(function ($enclosure) {
                return collect($enclosure->getAttributes())
                    ->filter(function ($value, $column) {
                        return !in_array($column, ['id', 'created_at', 'updated_at', 'application_id', 'remark', 'is_draft', 'deleted_at'])
                            && !str_ends_with($column, 'SendLater')
                            && !is_null($value);
                    })
                    ->keys();
            })
            ->unique()
            ->values();
    }

    protected function rules(): array
    {
        return [
            'application_id' => 'required',
            'column' => 'required',
            'file' => 'required',
        ];
    }

    public function mount()
    {
        $this->applications = Application::with('enclosures')->LoggedInUser()->get();
        $user = auth()->user();
        $this->userName = "{$user->lastname}_{$user->firstname}";
    }

    #[Layout('components.layout.user-dashboard')]
    public function render()
    {
        $fileColumns = collect(Schema::getColumnListing((new Enclosure)->getTable()))
            ->filter(function ($column) {
                return !in_array($column, ['id', 'created_at', 'updated_at', 'application_id', 'remark', 'is_draft', 'deleted_at'])
                    && !str_ends_with($column, 'SendLater');
            })
            ->values()
            ->toArray();

        $query = Enclosure::query()
            ->whereHas('application', function ($query) {
                $query->where('user_id', auth()->id());
                if ($this->applicationFilter) {
                    $query->where('id', $this->applicationFilter);
                }
            });

        if ($this->contentFilter) {
            $query->whereNotNull($this->contentFilter);
        }

        return view('livewire.user.datei', [
            'enclosuresList' => $query->get(),
            'selectedContentType' => $this->contentFilter,
        ]);
    }

    public function addEnclosure()
    {
        $this->showModal = true;
    }

    public function saveEnclosure()
    {
        $this->validate();

        try {
            $filePath = $this->upload($this->file, $this->column);

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

    private function storeFile($fileName)
    {
        return $this->file->storeAs($this->userName, $fileName, 's3');
    }

    private function upload($file, $text)
    {
        if (!is_null($file)) {
            $fileName = 'Appl' . $this->application_id . '_' . $text . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($this->userName, $fileName, 's3');
        }
    }

    private function resetForm()
    {
        $this->reset(['application_id', 'column', 'file']);
    }
}
