<tr>
    <th scope="row">{{ $slot }}</th>
    <td><b>{{ $label }} *</b></td>
    <td>
        <div class="mb-3">
            <input wire:model="{{ $field }}" class="form-control" type="file" id="formFile">
        </div>
        <span class="text-danger">
            @error($field)
                {{ $message }}
            @enderror
        </span>
    </td>
    <td>
        @if ($enclosure->$field)
            <a href="{{ Storage::disk('s3')->url($enclosure->$field) }}" target="_blank">{{ $enclosure->$field }}</a>
        @endif
    </td>
    <td>
        <div class="mb-3">
            <input wire:model.live="enclosure.{{ $field }}SendLater" type="checkbox">
        </div>
    </td>
</tr>
