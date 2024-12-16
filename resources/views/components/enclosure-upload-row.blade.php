@props(['rowNumber', 'fieldName', 'isRequired' => false, 'model', 'enclosure'])

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $rowNumber }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        {{ __("enclosure.$fieldName") }}
        @if ($isRequired ?? false)
            <span class="text-red-500">*</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        @if ($enclosure->{$fieldName})
            <span class="text-green-600">✓</span>
        @else
            <span class="text-red-600">✗</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <input type="file" wire:model="{{ $model }}"
            class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-medium
                file:bg-primary file:text-white
                hover:file:bg-primary-dark">
        @error($model)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        <label class="inline-flex items-center">
            <input type="checkbox" wire:model="sendLaterFields.{{ $fieldName }}"
                class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
            <span class="ml-2">{{ __('enclosure.send_later') }}</span>
        </label>
    </td>
</tr>
