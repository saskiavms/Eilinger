<div class="border rounded-lg bg-white" x-data="{ open: false }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">{{ __('enclosure.title') }}</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        @if ($enclosure)
            <div class="space-y-4">
                <!-- Remark -->
                <div class="text-sm">
                    <span class="font-medium text-gray-700">{{ __('enclosure.remark') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $enclosure->remark }}</span>
                </div>

                <!-- Files Section -->
                <div class="grid grid-cols-1 gap-3">
                    @php
                        $files = [
                            'certificate_of_study' => __('enclosure.certificate_of_study'),
                            'tax_assessment' => __('enclosure.tax_assessment'),
                            'expense_receipts' => __('enclosure.expense_receipts_stip'),
                            'partner_tax_assessment' => __('enclosure.partner_tax_assessment'),
                            'supplementary_services' => __('enclosure.supplementary_services'),
                            'ects_points' => __('enclosure.ects_points'),
                            'parents_tax_factors' => __('enclosure.parents_tax_factors'),
                            'passport' => __('enclosure.passport'),
                            'cv' => __('enclosure.cv'),
                            'apprenticeship_contract' => __('enclosure.apprenticeship_contract'),
                            'diploma' => __('enclosure.diploma'),
                            'divorce' => __('enclosure.divorce'),
                            'rental_contract' => __('enclosure.rental_contract_aboard'),
                        ];
                    @endphp

                    @foreach ($files as $field => $label)
                        <div class="flex items-center">
                            <span class="font-medium text-gray-700 text-sm min-w-[200px]">{{ $label }}:</span>
                            @if ($enclosure->$field)
                                <a href="{{ Storage::disk('s3')->url($enclosure->$field) }}" target="_blank"
                                    class="text-sm text-primary hover:text-primary-600 hover:underline ml-2 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ basename($enclosure->$field) }}
                                </a>
                            @else
                                <span class="text-sm text-gray-400 ml-2">-</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-sm text-gray-500">
                {{ __('enclosure.noEnclosure') }}
            </div>
        @endif
    </div>
</div>
