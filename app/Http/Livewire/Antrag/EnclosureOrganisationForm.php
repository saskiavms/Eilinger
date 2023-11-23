<?php

namespace App\Http\Livewire\Antrag;

use App\Models\Application;
use App\Models\Enclosure;
use App\Rules\FileUploadRule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnclosureOrganisationForm extends Component
{
    use WithFileUploads;

    public $enclosure;

    public $filePath;

    public $UserName;

    public $isInitialAppl;

    public $commercial_register_extract;

    public $statute;

    public $activity;

    public $balance_sheet;

    public $tax_assessment;

    public $cost_receipts;

    public function rules()
    {
        $commercial_register_extract = is_null($this->enclosure->commercial_register_extract);
        $statute = is_null($this->enclosure->statute);
        $activity = is_null($this->enclosure->activity);
        $balance_sheet = is_null($this->enclosure->balance_sheet);
        $tax_assessment = is_null($this->enclosure->tax_assessment);
        $cost_receipts = is_null($this->enclosure->cost_receipts);

        return [
            'enclosure.remark' => 'nullable',
            'enclosure.commercial_register_extract' => new FileUploadRule($commercial_register_extract),
            'enclosure.statute' => [new FileUploadRule($statute)],
            'enclosure.activity' => [new FileUploadRule($activity)],
            'enclosure.balance_sheet' => [new FileUploadRule($balance_sheet)],
            'enclosure.tax_assessment' => [new FileUploadRule($tax_assessment)],
            'enclosure.cost_receipts' => [new FileUploadRule($cost_receipts)],
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount(): void
    {
        $lastname = auth()->user()->lastname;
        $firstname = auth()->user()->firstname;
        $this->UserName = $lastname.'_'.$firstname;
        $this->enclosure = Enclosure::where('application_id', session()->get('appl_id'))
            ->first() ?? new Enclosure;
        $this->isInitialAppl = Application::where('id', session()->get('appl_id'))->first(['is_first'])->is_first;

    }

    public function render()
    {
        return view('livewire.antrag.enclosure-organisation-form');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function saveEnclosureOrg(): void
    {
        $this->validate();

        $file_commercial_register_extract = $this->upload($this->commercial_register_extract, 'commercial_register_extract');
        $this->enclosure->commercial_register_extract = $file_commercial_register_extract;
        $file_statute = $this->upload($this->statute, 'statute');
        $this->enclosure->statute = $file_statute;
        $file_activity = $this->upload($this->activity, 'activity');
        $this->enclosure->activity = $file_activity;
        $file_balance_sheet = $this->upload($this->balance_sheet, 'balance_sheet');
        $this->enclosure->balance_sheet = $file_balance_sheet;
        $file_tax_assessment = $this->upload($this->tax_assessment, 'tax_assessment');
        $this->enclosure->tax_assessment = $file_tax_assessment;
        $file_cost_receipts = $this->upload($this->cost_receipts, 'cost_receipts');
        $this->enclosure->cost_receipts = $file_cost_receipts;

        $this->enclosure->is_draft = false;
        $this->enclosure->application_id = session()->get('appl_id');
        $this->enclosure->save();
        session()->flash('success', 'Beilagen aktualisiert.');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function upload($type, $text)
    {
        if (! is_null($type)) {
            $appl_id = session()->get('appl_id');
            $fileName = 'Appl'.$appl_id.'_'.$text.'.'.$type->getClientOriginalExtension();

            return $type->storeAs($this->UserName, $fileName, 'uploads');
        }
    }
}
