<div wire:init="completeApplication">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('sending.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('sending.subTitle') }}</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('sending.step') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('sending.data') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('sending.status') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ([['step' => 1, 'field' => 'applicant', 'required' => true, 'status' => $userNoDraft], ['step' => 2, 'field' => 'address', 'required' => true, 'status' => $addressNoDraft], ['step' => 3, 'field' => 'aboardAddress', 'required' => false, 'status' => $abweichendeAddressNoDraft], ['step' => 4, 'field' => 'education', 'required' => true, 'status' => $educationNoDraft], ['step' => 5, 'field' => 'account', 'required' => true, 'status' => $accountNoDraft], ['step' => 6, 'field' => 'parents', 'required' => false, 'status' => $parentsNoDraft], ['step' => 7, 'field' => 'sibling', 'required' => false, 'status' => $siblingNoDraft], ['step' => 8, 'field' => 'cost', 'required' => true, 'status' => $costNoDraft], ['step' => 9, 'field' => 'financing', 'required' => true, 'status' => $financingNoDraft], ['step' => 10, 'field' => 'remark', 'required' => true, 'status' => $enclosureNoDraft]] as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $row['step'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ __('sending.' . $row['field']) }}
                            @if ($row['required'])
                                <span class="text-red-500">*</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($row['status'])
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
