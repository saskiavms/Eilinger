<div class="border rounded-lg bg-white" x-data="{ open: true }">
    <!-- Accordion Header -->
    <button @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-primary-300 hover:bg-primary-400 transition-colors">
        <h2 class="text-lg font-medium text-gray-900">Antrag</h2>
        <svg class="h-5 w-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Accordion Content -->
    <div x-show="open" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.name') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $application->name }}</span>
                </p>
            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.bereich') }}:</span>
                    <span
                        class="text-gray-900 ml-1">{{ __('application.bereichs_name.' . $application->bereich->name) }}</span>
                </p>
            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.form') }}:</span>
                    <span
                        class="text-gray-900 ml-1">{{ __('application.form_name.' . $application->form->name) }}</span>
                </p>
            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.currency') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $application->currency->abbreviation }}</span>
                </p>
            </div>

            <div>

                <x-convert-money :amount="$application->calc_amount" :text="__('application.calc_amount')" :currency="$application->currency->abbreviation" />


            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.req_amount') }}:</span>
                    <span class="text-gray-900 ml-1">
                        @if ($application->req_amount)
                            @convert($application->req_amount, $application->currency->abbreviation)
                        @endif
                    </span>
                </p>
            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.start_appl') }}:</span>
                    <span class="text-gray-900 ml-1">{{ $application->start_appl->format('d.m.Y') }}</span>
                </p>
            </div>

            <div>
                <p class="text-md">
                    <span class="font-medium text-gray-700">{{ __('application.end_appl') }}:</span>
                    <span
                        class="text-gray-900 ml-1">{{ $application->end_appl ? $application->end_appl->format('d.m.Y') : '-' }}</span>
                </p>
            </div>

            @if ($application->form->value == 'Darlehen')
                <div>
                    <p class="text-md">
                        <span class="font-medium text-gray-700">{{ __('application.payout_plan') }}:</span>
                        <span class="text-gray-900 ml-1">
                            @if ($application->payout_plan)
                                {{ __('application.payoutplan_name.' . $application->payout_plan->name) }}
                            @else
                                -
                            @endif
                        </span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
