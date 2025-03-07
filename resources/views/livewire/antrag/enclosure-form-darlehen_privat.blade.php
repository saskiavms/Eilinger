<form wire:submit="saveEnclosure" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-primary mb-2">{{ __('enclosure.title') }}</h3>
        <p class="text-sm text-gray-600">{{ __('enclosure.subtitle') }}</p>
    </div>

    <x-notification />

    <!-- Remarks Section -->
    <div class="mb-8">
        <h4 class="text-md font-medium text-gray-900 mb-3">{{ __('enclosure.remark') }}</h4>
        <textarea wire:model="remark"
            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
            rows="3"></textarea>
    </div>

    <!-- Required Documents Section -->
    <div class="mb-8">
        <h4 class="text-md font-medium text-gray-900 mb-3">{{ __('enclosure.reqDocs') }}</h4>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-6">
            <p class="font-medium">{{ __('enclosure.instructions') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('enclosure.doc') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('enclosure.file') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('enclosure.upload') }}
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('enclosure.send_later') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach (['activity', 'activity_report', 'rental_contract', 'balance_sheet', 'tax_assessment', 'cost_receipts'] as $index => $field)
                        <x-enclosure-upload-row :rowNumber="$index + 1" :fieldName="$field" :isRequired="true" :model="$field"
                            :enclosure="$enclosure" />
                    @endforeach

                    @foreach (['open_invoice'] as $index => $field)
                        <x-enclosure-upload-row :rowNumber="$index + 7" :fieldName="$field" :model="$field"
                            :enclosure="$enclosure" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center mt-6">
        <button type="submit"
            class="px-6 py-2 bg-success text-white rounded-md hover:bg-successHover transition-colors">
            {{ __('attributes.save') }}
        </button>
    </div>
</form>
