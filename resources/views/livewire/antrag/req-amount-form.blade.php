<form wire:submit="saveReqAmount">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('reqAmount.title') }}</h3>
        <div class="space-y-1">
            <p class="text-sm text-gray-600">{{ __('reqAmount.subTitle') }}</p>
            <p class="text-sm text-gray-600">{{ __('reqAmount.addHint') }}</p>
        </div>
    </div>

    <x-notification />

    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('reqAmount.amount') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Total Cost -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ __('reqAmount.totalCost') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        @if (!is_null($total_amount_costs))
                            @convert($total_amount_costs, $application->currency->abbreviation)
                        @endif
                    </td>
                </tr>

                <!-- Total Financing -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ __('reqAmount.totalFinancing') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        @if ($total_amount_financing > 0)
                            @convert($total_amount_financing, $application->currency->abbreviation)
                        @else
                            0 {{ $application->currency->abbreviation }}
                        @endif
                    </td>
                </tr>

                <!-- Difference Amount -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ __('reqAmount.diffAmount') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        @if ($diffAmount != 0)
                            = @convert($diffAmount, $application->currency->abbreviation)
                        @endif
                    </td>
                </tr>

                <!-- Required Amount -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ __('reqAmount.reqAmount') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                {{ $application->currency->abbreviation }}
                            </span>
                            <input wire:model.blur="req_amount" type="number"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 sm:text-sm">
                        </div>
                        @error('req_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

                <!-- Payout Plan -->
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ __('reqAmount.payoutPlan') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select wire:model.blur="payout_plan"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring focus:ring-accent focus:ring-opacity-50 sm:text-sm">
                            <option value="">{{ __('attributes.please_select') }}</option>
                            @foreach (App\Enums\PayoutPlan::cases() as $payoutplan)
                                <option value="{{ $payoutplan }}">
                                    {{ __('application.payoutplan_name.' . $payoutplan->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payout_plan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
