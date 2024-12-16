<?php

namespace App\Livewire\Antrag;

use App\Models\Application;
use App\Models\Enclosure;
use App\Rules\FileUploadRule;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

abstract class BaseEnclosureForm extends Component
{
    use WithFileUploads;

    public $enclosure;
    public $UserName;
    public $isInitialAppl;
    public $sendLaterFields = [];

    // Add all possible file upload fields
    public $activity;
    public $activity_report;
    public $balance_sheet;
    public $cost_receipts;
    public $open_invoice;
    public $rental_contract;
    public $tax_assessment;
    public $certificate_of_study;
    public $expense_receipts;
    public $partner_tax_assessment;
    public $supplementary_services;
    public $ects_points;
    public $parents_tax_factors;
    public $passport;
    public $cv;
    public $apprenticeship_contract;
    public $diploma;
    public $divorce;
    public $commercial_register_extract;
    public $statute;

    abstract protected function getRequiredFields(): array;
    abstract protected function getOptionalFields(): array;

    public function mount(): void
    {
        $lastname = auth()->user()->lastname;
        $firstname = auth()->user()->firstname;
        $this->UserName = $lastname . '_' . $firstname;

        // Get or create enclosure
        $this->enclosure = Enclosure::where('application_id', session()->get('appl_id'))
            ->first() ?? new Enclosure();

        // Initialize sendLater fields for both required and optional fields
        $allFields = array_merge($this->getRequiredFields(), $this->getOptionalFields());
        foreach ($allFields as $field) {
            $sendLaterField = $this->getCamelCaseSendLater($field);
            // Initialize the public property for the checkbox
            $this->sendLaterFields[$field] = (bool) $this->enclosure->$sendLaterField;
        }

        $this->isInitialAppl = Application::where('id', session()->get('appl_id'))
            ->first(['is_first'])->is_first;
    }

    public function updatedSendLaterFields($value, $field): void
    {
        $sendLaterField = $this->getCamelCaseSendLater($field);
        $this->enclosure->$sendLaterField = $value;
    }

    public function validationAttributes(): array
    {
        return Lang::get('enclosure');
    }

    protected function rules(): array
    {
        $rules = [
            'enclosure.remark' => 'nullable',
        ];

        foreach ($this->getRequiredFields() as $field) {
            // If file already exists in database, skip validation
            if (!is_null($this->enclosure->$field)) {
                continue;
            }

            // Add validation rules for the sendLater checkbox
            $rules["sendLaterFields.{$field}"] = 'boolean';

            // If sendLater is not checked, require the file
            if (empty($this->sendLaterFields[$field])) {
                $rules[$field] = ['required', new FileUploadRule(true)];
            } else {
                $rules[$field] = ['nullable', new FileUploadRule(false)];
            }
        }

        // Handle optional fields - only validate format if file is provided
        foreach ($this->getOptionalFields() as $field) {
            $rules[$field] = ['nullable', new FileUploadRule(false)];
        }

        Log::debug('Validation Rules:', $rules);
        Log::debug('SendLater Fields:', $this->sendLaterFields);
        return $rules;
    }

    protected function getCamelCaseSendLater(string $field): string
    {
        return lcfirst(str_replace('_', '', ucwords($field, '_'))) . 'SendLater';
    }

    protected function handleFileUpload($field): void
    {
        if ($this->$field) {
            $filePath = $this->upload($this->$field, $field);
            $this->enclosure->$field = $filePath;
            $sendLaterField = $this->getCamelCaseSendLater($field);
            $this->enclosure->$sendLaterField = false;
            $this->sendLaterFields[$field] = false;
        }
    }

    public function save(): void
    {
        $validatedData = $this->validate();
        Log::debug('Validated Data:', $validatedData);

        $allFields = array_merge($this->getRequiredFields(), $this->getOptionalFields());
        foreach ($allFields as $field) {
            $this->handleFileUpload($field);
            // Update the sendLater field in the model
            $sendLaterField = $this->getCamelCaseSendLater($field);
            $this->enclosure->$sendLaterField = $this->sendLaterFields[$field] ?? false;
        }

        $this->enclosure->is_draft = false;
        $this->enclosure->application_id = session()->get('appl_id');
        $this->enclosure->save();

        session()->flash('success', __('userNotification.enclosureSaved'));
    }

    public function upload($type, $text)
    {
        if (!is_null($type)) {
            $appl_id = session()->get('appl_id');
            $fileName = 'Appl' . $appl_id . '_' . $text . '.' . $type->getClientOriginalExtension();
            return $type->storeAs($this->UserName, $fileName, 's3');
        }
        return null;
    }
}
