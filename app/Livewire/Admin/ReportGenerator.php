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
use Symfony\Component\HttpFoundation\StreamedResponse;

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

            // Create ZIP file
            $zip = new ZipArchive();
            if (($zipError = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
                Log::error('Failed to create ZIP file. Error code: ' . $zipError);
                throw new \Exception('Could not create ZIP file');
            }

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
                ])->setPaper('a4');

                // Add PDF to ZIP
                $pdfContent = $pdf->output();
                $pdfFileName = "application_{$application->id}.pdf";
                $zip->addFromString($pdfFileName, $pdfContent);
                unset($pdfContent); // Free memory
                unset($pdf); // Free memory

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
                                    // Get file contents from S3
                                    $contents = Storage::disk('s3')->get($path);
                                    if ($contents) {
                                        // Add file contents directly to ZIP
                                        $zip->addFromString("documents/{$application->id}/" . basename($path), $contents);
                                        unset($contents); // Free memory
                                    }
                                } catch (\Exception $e) {
                                    Log::error("Failed to add file to ZIP: {$path} - Error: " . $e->getMessage());
                                }
                            }
                        }
                    }
                }

                // Clear some memory after each application
                gc_collect_cycles();
            }

            $zip->close();
            Log::info('ZIP file created successfully');

            // Stream the download response
            return new StreamedResponse(function () use ($zipPath) {
                if (file_exists($zipPath)) {
                    $stream = fopen($zipPath, 'rb');
                    while (!feof($stream)) {
                        echo fread($stream, 8192);
                        flush();
                    }
                    fclose($stream);
                    unlink($zipPath); // Delete the file after streaming
                }
            }, 200, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . basename($zipPath) . '"',
            ]);

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
