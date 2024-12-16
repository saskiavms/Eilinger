@props(['amount', 'text', 'currency'])


<p class="text-md text-gray-700 font-medium">{{ $text }}:
    @if ($amount)
        <span class="text-gray-900 ml-1">
            @convert($amount, $currency)
        </span>
    @endif
</p>
