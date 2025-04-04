<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Enums\ApplStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use ZipArchive;
use Illuminate\Support\Str;

class ReportGenerator extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $selectedYear;
    public $isGenerating = false;
    public $downloadUrl = null;

    #[Layout('components.layout.admin-dashboard', ['header' => 'Report Generator'])]
    public function render()
    {
        // First, check if we have any applications without approval date
        $hasNoDateApplications = Application::query()
            ->where('appl_status', ApplStatus::APPROVED->value)
            ->whereNull('approval_appl')
            ->exists();

        // Get years from applications with approval dates
        $years = Application::query()
            ->where('appl_status', ApplStatus::APPROVED->value)
            ->whereNotNull('approval_appl')
            ->selectRaw('YEAR(approval_appl) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $applications = Application::query()
            ->where('appl_status', ApplStatus::APPROVED->value)
            ->when($this->selectedYear === 'no_date', function ($query) {
                return $query->whereNull('approval_appl');
            })
            ->when($this->selectedYear && $this->selectedYear !== 'no_date', function ($query) {
                return $query->whereYear('approval_appl', $this->selectedYear);
            })
            ->with([
                'user.parents',
                'user.siblings',
                'user',
                'education',
                'account',
                'enclosures',
                'cost',
                'costDarlehen',
                'financing',
                'financingOrganisation'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.report-generator', [
            'applications' => $applications,
            'years' => $years,
            'hasNoDateApplications' => $hasNoDateApplications,
        ]);
    }

    public function generateReports()
    {
        try {
            Log::info('Starting report generation process');
            $this->isGenerating = true;

            $uniqueId = Str::random(16);
            $zipFileName = 'reports_' . ($this->selectedYear ?? 'all') . '_' . $uniqueId . '.zip';

            // Create directory in storage/app/public/reports
            $storagePath = storage_path('app/public/reports');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
                Log::info('Created storage directory at: ' . $storagePath);
            }

            // Full path for the ZIP file
            $fullZipPath = $storagePath . '/' . $zipFileName;
            Log::info('Will create ZIP file at: ' . $fullZipPath);

            // Create ZIP file
            $zip = new ZipArchive();
            if (($zipError = $zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
                Log::error('Failed to create ZIP file. Error code: ' . $zipError);
                throw new \Exception('Could not create ZIP file');
            }

            $applications = Application::query()
                ->where('appl_status', ApplStatus::APPROVED->value)
                ->whereNotNull('approval_appl')
                ->when($this->selectedYear, function ($query) {
                    return $query->whereYear('approval_appl', $this->selectedYear);
                })
                ->with([
                    'user.parents',
                    'user.siblings',
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

            // Process each application
            foreach ($applications as $application) {
                Log::info('Processing application ID: ' . $application->id);

                // Generate PDF with lower memory usage
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
                    'parents' => $application->user->parents,
                    'siblings' => $application->user->siblings,
                ])->setPaper('a4');

                // Add PDF to ZIP
                $pdfContent = $pdf->output();
                $pdfFileName = "application_{$application->id}.pdf";
                $zip->addFromString($pdfFileName, $pdfContent);
                unset($pdfContent);
                unset($pdf);

                // Process enclosures
                if ($application->enclosures && $application->enclosures->count() > 0) {
                    foreach ($application->enclosures as $enclosure) {
                        foreach (['cv', 'apprenticeship_contract', 'diploma', 'divorce', 'rental_contract',
                        'certificate_of_study', 'tax_assessment', 'expense_receipts','partner_tax_assessment',
                        'supplementary_services', 'ects_points', 'parents_tax_factors', 'activity', 'activity_report',
                         'balance_sheet', 'cost_receipts', 'open_invoice', 'commercial_register_extract', 'statute' ] as $documentType) {
                            $path = $enclosure->$documentType;

                            if ($path && Storage::disk('s3')->exists($path)) {
                                try {
                                    $contents = Storage::disk('s3')->get($path);
                                    if ($contents) {
                                        $zip->addFromString("documents/{$application->id}/" . basename($path), $contents);
                                        unset($contents);
                                    }
                                } catch (\Exception $e) {
                                    Log::error("Failed to add file to ZIP: {$path} - Error: " . $e->getMessage());
                                }
                            }
                        }
                    }
                }

                gc_collect_cycles();
            }

            $zip->close();
            Log::info('ZIP file created successfully');

            // Verify file exists and log details
            if (!file_exists($fullZipPath)) {
                Log::error('ZIP file not found after creation at: ' . $fullZipPath);
                throw new \Exception('ZIP file not found after creation');
            }

            Log::info('ZIP file exists at: ' . $fullZipPath);
            Log::info('ZIP file size: ' . filesize($fullZipPath) . ' bytes');

            // Store file information in the session
            session(['report_file' => [
                'path' => $fullZipPath,
                'name' => $zipFileName,
                'created_at' => now(),
            ]]);

            // Set the download URL - this points to public/storage/reports/filename.zip
            $this->downloadUrl = url('storage/reports/' . $zipFileName);
            Log::info('Download URL set to: ' . $this->downloadUrl);

            $this->isGenerating = false;

        } catch (\Exception $e) {
            Log::error('Fatal error in report generation: ' . $e->getMessage());
            $this->isGenerating = false;
            throw $e;
        }
    }

    public function downloadReport()
    {
        $fileInfo = session('report_file');

        if (!$fileInfo || !file_exists($fileInfo['path'])) {
            Log::error('Report file not found for download');
            $this->addError('download', 'Report file not found. Please generate the report again.');
            return;
        }

        // Clear the download URL and session before streaming
        $this->downloadUrl = null;
        $filePath = $fileInfo['path'];
        $fileName = $fileInfo['name'];
        session()->forget('report_file');

        // Stream the file with minimal memory usage
        return response()->stream(
            function () use ($filePath) {
                $stream = fopen($filePath, 'rb');
                while (!feof($stream)) {
                    echo fread($stream, 8192); // Stream in 8KB chunks
                    flush();
                }
                fclose($stream);
                unlink($filePath); // Delete the file after streaming
            },
            200,
            [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }

    public function generateReport($applicationId)
    {
        try {
            Log::info('Starting report generation for application: ' . $applicationId);

            $application = Application::query()
                ->where('id', $applicationId)
                ->where('appl_status', ApplStatus::APPROVED->value)
                ->with([
                    'user.parents',
                    'user.siblings',
                    'user',
                    'education',
                    'account',
                    'enclosures',
                    'cost',
                    'costDarlehen',
                    'financing',
                    'financingOrganisation',
                    'messages'
                ])
                ->firstOrFail();

            // Get the approval year for the filename, or use "no_date" if no approval date
            $approvalYear = $application->approval_appl
                ? $application->approval_appl->format('Y')
                : 'no_date';

            $zipFileName = "report_{$approvalYear}_application_{$application->id}.zip";
            $tempPath = storage_path('app/livewire-tmp');
            $fullZipPath = $tempPath . '/' . $zipFileName;

            // Create ZIP file
            $zip = new ZipArchive();
            if (($zipError = $zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
                Log::error('Failed to create ZIP file. Error code: ' . $zipError);
                throw new \Exception('Could not create ZIP file');
            }

            // Generate PDF
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
                'parents' => $application->user->parents,
                'siblings' => $application->user->siblings,
                'messages' => $application->messages
            ])->setPaper('a4');

            // Add PDF to ZIP
            $zip->addFromString('application.pdf', $pdf->output());

            // Process enclosures
            if ($application->enclosures && $application->enclosures->count() > 0) {
                foreach ($application->enclosures as $enclosure) {
                    foreach ([
                        'cv', 'apprenticeship_contract', 'diploma', 'divorce', 'rental_contract',
                        'certificate_of_study', 'tax_assessment', 'expense_receipts', 'partner_tax_assessment',
                        'supplementary_services', 'ects_points', 'parents_tax_factors', 'activity', 'activity_report',
                        'balance_sheet', 'cost_receipts', 'open_invoice', 'commercial_register_extract', 'statute'
                    ] as $documentType) {
                        $path = $enclosure->$documentType;

                        if ($path && Storage::disk('s3')->exists($path)) {
                            try {
                                $contents = Storage::disk('s3')->get($path);
                                if ($contents) {
                                    $zip->addFromString("documents/" . basename($path), $contents);
                                }
                            } catch (\Exception $e) {
                                Log::error("Failed to add file to ZIP: {$path} - Error: " . $e->getMessage());
                            }
                        }
                    }
                }
            }

            $zip->close();

            return response()->download($fullZipPath, $zipFileName, ['Content-Type' => 'application/zip'])
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error generating report: ' . $e->getMessage());
            session()->flash('error', 'Fehler beim Generieren des Reports.');
            return null;
        }
    }

    public function placeholder()
    {
        return view('components.loading');
    }
}
