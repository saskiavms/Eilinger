<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use ZipArchive;

class ReportGenerator extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $selectedYear;
    public $isGenerating = false;

    #[Layout('components.layout.admin-dashboard', ['header' => 'Report Generator'])]
    public function render()
    {
        $years = Application::query()
            ->where('appl_status', 'approved')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $applications = Application::query()
            ->where('appl_status', 'approved')
            ->when($this->selectedYear, function ($query) {
                return $query->whereYear('created_at', $this->selectedYear);
            })
            ->with(['user', 'education', 'account', 'enclosures', 'cost', 'costDarlehen', 'financing', 'financingOrganisation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.report-generator', [
            'applications' => $applications,
            'years' => $years,
        ]);
    }

    public function generateReports()
    {
        $this->isGenerating = true;

        $applications = Application::query()
            ->where('appl_status', 'approved')
            ->when($this->selectedYear, function ($query) {
                return $query->whereYear('created_at', $this->selectedYear);
            })
            ->with([
                'user',
                'education',
                'account',
                'enclosures',
                'cost',
                'costDarlehen',
                'financing',
                'financingOrganisation'
            ])
            ->get();

        $zipFileName = 'reports_' . ($this->selectedYear ?? 'all') . '.zip';
        $zipPath = storage_path('app/public/reports/' . $zipFileName);

        // Ensure the reports directory exists
        Storage::makeDirectory('public/reports');

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($applications as $application) {
            $pdf = PDF::loadView('pdf.application-report', [
                'application' => $application,
                'user' => $application->user,
                'education' => $application->education,
                'account' => $application->account,
                'enclosure' => $application->enclosure,
                'cost' => $application->cost,
                'costDarlehen' => $application->costDarlehen,
                'financing' => $application->financing,
                'financingOrganisation' => $application->financingOrganisation,
            ]);

            // Generate PDF for the application data
            $pdfContent = $pdf->output();
            $pdfFileName = "application_{$application->id}.pdf";
            $zip->addFromString($pdfFileName, $pdfContent);

            // Add enclosure documents if they exist
            if ($application->enclosure) {
                foreach (['cv', 'motivation_letter', 'diplomas', 'language_certificates', 'acceptance_letter', 'registration_confirmation', 'budget_plan', 'transcript_records'] as $documentType) {
                    $path = $application->enclosure->$documentType;
                    if ($path && Storage::exists('public/' . $path)) {
                        $zip->addFile(storage_path('app/public/' . $path), "documents/{$application->id}/" . basename($path));
                    }
                }
            }
        }

        $zip->close();

        $this->isGenerating = false;

        return response()->download($zipPath)->deleteFileAfterSend();
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
