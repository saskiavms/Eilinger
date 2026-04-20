<?php

namespace App\Livewire\Admin;

use App\Enums\FraudSignalType;
use App\Models\FraudSignal;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class FraudSignals extends Component
{
    use WithPagination;

    #[Layout('components.layout.admin-dashboard')]
    #[Title('Betrugssignale')]

    public string $filterType = '';
    public string $filterSeverity = '';
    public string $filterStatus = 'open';

    protected $queryString = [
        'filterType'     => ['except' => ''],
        'filterSeverity' => ['except' => ''],
        'filterStatus'   => ['except' => 'open'],
    ];

    public function updatedFilterType(): void { $this->resetPage(); }
    public function updatedFilterSeverity(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function markReviewed(int $id): void
    {
        FraudSignal::findOrFail($id)->update([
            'reviewed_at'    => now(),
            'reviewed_by_id' => auth()->id(),
        ]);
    }

    public function markFalsePositive(int $id): void
    {
        FraudSignal::findOrFail($id)->update([
            'is_false_positive' => true,
            'reviewed_at'       => now(),
            'reviewed_by_id'    => auth()->id(),
        ]);
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $signals = FraudSignal::with(['user', 'relatedUser', 'application', 'relatedApplication'])
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterSeverity, fn($q) => $q->where('severity', $this->filterSeverity))
            ->when($this->filterStatus === 'open', fn($q) => $q->open())
            ->when($this->filterStatus === 'reviewed', fn($q) => $q->whereNotNull('reviewed_at')->where('is_false_positive', false))
            ->when($this->filterStatus === 'false_positive', fn($q) => $q->where('is_false_positive', true))
            ->orderByRaw("FIELD(severity,'high','medium','low')")
            ->orderByDesc('created_at')
            ->get();

        $filename = 'betrugssignale_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($signals) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel

            fputcsv($handle, ['Typ', 'Schweregrad', 'Benutzer A', 'Email A', 'Benutzer B', 'Email B', 'Antrag A', 'Antrag B', 'Details', 'Erkannt am', 'Status'], ';');

            foreach ($signals as $signal) {
                fputcsv($handle, [
                    $signal->type->label(),
                    match($signal->severity) { 'high' => 'Kritisch', 'medium' => 'Mittel', default => 'Niedrig' },
                    trim(($signal->user?->firstname ?? '') . ' ' . ($signal->user?->lastname ?? '') . ' ' . ($signal->user?->name_inst ?? '')),
                    $signal->user?->email ?? '—',
                    trim(($signal->relatedUser?->firstname ?? '') . ' ' . ($signal->relatedUser?->lastname ?? '') . ' ' . ($signal->relatedUser?->name_inst ?? '')),
                    $signal->relatedUser?->email ?? '—',
                    $signal->application?->name ?? '—',
                    $signal->relatedApplication?->name ?? '—',
                    $this->resolveSignalDetail($signal),
                    $signal->created_at->format('d.m.Y H:i'),
                    $signal->is_false_positive ? 'Falsch positiv' : ($signal->reviewed_at ? 'Geprüft' : 'Offen'),
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function resolveSignalDetail(\App\Models\FraudSignal $signal): string
    {
        return match($signal->type) {
            \App\Enums\FraudSignalType::DUPLICATE_IP => $this->findSharedIp($signal->user_id, $signal->related_user_id),
            \App\Enums\FraudSignalType::DUPLICATE_DOCUMENT => $signal->details['field_name'] ?? '—',
            \App\Enums\FraudSignalType::DUPLICATE_PHONE => $signal->details['field'] ?? '—',
            default => '—',
        };
    }

    private function findSharedIp(?int $userIdA, ?int $userIdB): string
    {
        if (!$userIdA || !$userIdB) return '—';

        $ipsA = \App\Models\Login::withTrashed()->where('user_id', $userIdA)->pluck('ip_address');
        $ipsB = \App\Models\Login::withTrashed()->where('user_id', $userIdB)->pluck('ip_address');

        return $ipsA->intersect($ipsB)->first() ?? '—';
    }

    public function render()
    {
        $signals = FraudSignal::with(['user', 'relatedUser', 'application', 'relatedApplication', 'reviewedBy'])
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->when($this->filterSeverity, fn($q) => $q->where('severity', $this->filterSeverity))
            ->when($this->filterStatus === 'open', fn($q) => $q->open())
            ->when($this->filterStatus === 'reviewed', fn($q) => $q->whereNotNull('reviewed_at')->where('is_false_positive', false))
            ->when($this->filterStatus === 'false_positive', fn($q) => $q->where('is_false_positive', true))
            ->orderByRaw("FIELD(severity,'high','medium','low')")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.admin.fraud-signals', [
            'signals'    => $signals,
            'openCount'  => FraudSignal::open()->count(),
            'highCount'  => FraudSignal::open()->highSeverity()->count(),
            'signalTypes' => FraudSignalType::cases(),
        ]);
    }
}
