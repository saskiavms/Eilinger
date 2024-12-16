@props(['tag' => 'h4'])

<{{ $tag }} {{ $attributes->merge(['class' => 'font-roboto text-[24px]']) }}>
    {{ $slot }}
    </{{ $tag }}>
