@props(['tag' => 'h3'])

<{{ $tag }} {{ $attributes->merge(['class' => 'font-ubuntu text-[28px]']) }}>
    {{ $slot }}
    </{{ $tag }}>
