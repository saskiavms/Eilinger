@props(['rowNumber', 'fieldName', 'isRequired' => false, 'model', 'enclosure'])

<tr>
    <th scope="row">{{ $rowNumber }}</th>
    <td>
        @if ($isRequired)
            <b>{{ __("enclosure.$fieldName") }} *</b>
        @else
            {{ __("enclosure.$fieldName") }}
        @endif
    </td>
    <td>
        <div class="mb-3">
            <input wire:model="{{ $fieldName }}" class="form-control" type="file">
        </div>
        <span class="text-danger">
            @error($fieldName)
                {{ $message }}
            @enderror
        </span>
    </td>
    <td>
        @if ($enclosure->$fieldName)
            <a href="{{ Storage::disk('s3')->url($enclosure->$fieldName) }}"
                target="_blank">{{ $enclosure->$fieldName }}</a>
        @endif
    </td>
    <td>
        <div class="mb-3">
            <input wire:model="enclosure.{{ lcfirst(str_replace('_', '', ucwords($fieldName, '_'))) }}SendLater"
                type="checkbox">
        </div>
    </td>
</tr>
