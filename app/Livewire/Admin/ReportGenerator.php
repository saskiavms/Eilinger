<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
        try {
            Log::info('Starting report generation process');
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

            Log::info('Found ' . $applications->count() . ' applications to process');

            $zipFileName = 'reports_' . ($this->selectedYear ?? 'all') . '.zip';
            $zipPath = storage_path('app/public/reports/' . $zipFileName);

            // Ensure the reports directory exists
            Storage::makeDirectory('public/reports');
            Log::info('Created reports directory at: ' . $zipPath);

            $zip = new ZipArchive();
            if (($zipError = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
                Log::error('Failed to create ZIP file. Error code: ' . $zipError);
                throw new \Exception('Could not create ZIP file');
            }

            $tempFiles = [];

            foreach ($applications as $application) {
                Log::info('Processing application ID: ' . $application->id);

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
                    'address' => $application->user->address()->where('is_wochenaufenthalt', 0)->where('is_aboard', 0)->first(),
                    'abweichendeAddress' => $application->user->address()->where('is_wochenaufenthalt', 1)->first(),
                    'aboardAddress' => $application->user->address()->where('is_aboard', 1)->first(),
                ]);

                // Generate PDF for the application data
                $pdfContent = $pdf->output();
                $pdfFileName = "application_{$application->id}.pdf";
                $zip->addFromString($pdfFileName, $pdfContent);
                Log::info('Added PDF for application: ' . $pdfFileName);

                // Add enclosure documents if they exist
                if ($application->enclosures && $application->enclosures->count() > 0) {
                    Log::info('Processing enclosures for application: ' . $application->id);

                    foreach ($application->enclosures as $enclosure) {
                        foreach (['cv', 'apprenticeship_contract', 'diploma', 'divorce', 'rental_contract',
                        'certificate_of_study', 'tax_assessment', 'expense_receipts','partner_tax_assessment',
                        'supplementary_services', 'ects_points', 'parents_tax_factors', 'activity', 'activity_report',
                         'balance_sheet', 'cost_receipts', 'open_invoice', 'commercial_register_extract', 'statute' ] as $documentType) {
                            $path = $enclosure->$documentType;

                            if ($path) {
                                Log::info("Processing document type: {$documentType}, path: {$path}");

                                try {
                                    // Check if file exists in S3
                                    if (!Storage::disk('s3')->exists($path)) {
                                        Log::error("File does not exist in S3: {$path}");
                                        continue;
                                    }

                                    // Create a temporary file
                                    $tempFile = tempnam(sys_get_temp_dir(), 'report_');
                                    Log::info("Created temp file: {$tempFile}");

                                    // Get file contents directly using Storage facade
                                    $contents = Storage::disk('s3')->get($path);

                                    if ($contents) {
                                        // Save to temporary file
                                        file_put_contents($tempFile, $contents);

                                        // Add to ZIP using the original filename
                                        $zip->addFile($tempFile, "documents/{$application->id}/" . basename($path));

                                        // Track temporary files for cleanup
                                        $tempFiles[] = $tempFile;

                                        Log::info("Successfully added file to ZIP: " . basename($path));
                                    } else {
                                        Log::error("Failed to get file contents from S3: " . $path);
                                    }
                                } catch (\Exception $e) {
                                    Log::error("Failed to add file to ZIP: {$path} - Error: " . $e->getMessage());
                                    continue;
                                }
                            }
                        }
                    }
                } else {
                    Log::info('No enclosures found for application: ' . $application->id);
                }
            }

            $zip->close();
            Log::info('ZIP file closed successfully');

            // Clean up all temporary files
            if (!empty($tempFiles)) {
                foreach ($tempFiles as $tempFile) {
                    if (file_exists($tempFile)) {
                        unlink($tempFile);
                        Log::info('Cleaned up temp file: ' . $tempFile);
                    }
                }
            }

            $this->isGenerating = false;
            Log::info('Report generation completed successfully');

            return response()->download($zipPath)->deleteFileAfterSend();
        } catch (\Exception $e) {
            Log::error('Fatal error in report generation: ' . $e->getMessage());
            $this->isGenerating = false;
            throw $e;
        }
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
