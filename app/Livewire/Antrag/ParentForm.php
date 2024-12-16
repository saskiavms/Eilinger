<?php

namespace App\Livewire\Antrag;

use App\Enums\JobType;
use App\Enums\ParentType;
use App\Models\Parents;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class ParentForm extends Component
{
    public array $parentsList = [];

    protected function rules(): array
    {
        return [
            'parentsList.*.parent_type' => ['required', new Enum(ParentType::class)],
            'parentsList.*.firstname' => 'required',
            'parentsList.*.lastname' => 'required',
            'parentsList.*.birthday' => 'required',
            'parentsList.*.phone' => 'nullable',
            'parentsList.*.address' => 'nullable',
            'parentsList.*.plz_ort' => 'nullable',
            'parentsList.*.since' => 'nullable',
            'parentsList.*.job_type' => ['nullable', new Enum(JobType::class)],
            'parentsList.*.job' => 'nullable',
            'parentsList.*.employer' => 'nullable',
            'parentsList.*.in_ch_since' => 'nullable',
            'parentsList.*.married_since' => 'nullable',
            'parentsList.*.separated_since' => 'nullable',
            'parentsList.*.divorced_since' => 'nullable',
            'parentsList.*.death' => 'nullable',
        ];
    }

	public function messages(): array
    {
        $messages = [];
        foreach ($this->parentsList as $index => $parent) {
            $position = $index + 1;
            $parentLabel = __('parents.parent', ['position' => $position]);

            $messages["parentsList.{$index}.birthday.required"] = __('validation.required', [
                'attribute' => "{$parentLabel} {$this->getFieldLabel('birthday')}"
            ]);
            $messages["parentsList.{$index}.lastname.required"] = __('validation.required', [
                'attribute' => "{$parentLabel} {$this->getFieldLabel('lastname')}"
            ]);
            $messages["parentsList.{$index}.firstname.required"] = __('validation.required', [
                'attribute' => "{$parentLabel} {$this->getFieldLabel('firstname')}"
            ]);
            $messages["parentsList.{$index}.parent_type.required"] = __('validation.required', [
                'attribute' => "{$parentLabel} {$this->getFieldLabel('parent_type')}"
            ]);
        }
        return $messages;
    }

    private function getFieldLabel($field): string
    {
        return __("parents.{$field}");
    }

    public function validationAttributes(): array
    {
        return Lang::get('parents');
    }

    public function mount()
    {
        $existingParents = Parents::where('user_id', auth()->user()->id)->get();

        if ($existingParents->isNotEmpty()) {
            foreach ($existingParents as $parent) {
                $this->parentsList[] = $parent->toArray();
            }
        } else {
            $this->addParent(); // Add one empty parent form by default
        }
    }

    public function addParent()
    {
        $this->parentsList[] = [
            'parent_type' => null,
            'firstname' => '',
            'lastname' => '',
            'birthday' => null,
            'phone' => '',
            'address' => '',
            'plz_ort' => '',
            'since' => null,
            'job_type' => null,
            'job' => '',
            'employer' => '',
            'in_ch_since' => null,
            'married_since' => null,
            'separated_since' => null,
            'divorced_since' => null,
            'death' => null,
        ];
    }

    public function removeParent($index)
    {
        unset($this->parentsList[$index]);
        $this->parentsList = array_values($this->parentsList);
    }

    public function saveParents()
    {
        $validatedData = $this->validate();

        foreach ($this->parentsList as $parentData) {
            $parent = Parents::updateOrCreate(
                [
                    'user_id' => auth()->user()->id,
                    'firstname' => $parentData['firstname'],
                    'lastname' => $parentData['lastname'],
                ],
                array_merge($parentData, [
                    'user_id' => auth()->user()->id,
                    'is_draft' => false
                ])
            );
        }

        session()->flash('success', __('userNotification.parentSaved'));
    }

    public function render()
    {
        return view('livewire.antrag.parent-form');
    }
}
