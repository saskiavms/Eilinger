@props(['tag' => 'h2'])

<h2
    {{ $attributes->merge([
        'class' => 'font-decorative text-center text-[2rem] font-bold text-[#37517e] uppercase relative mb-5 pb-5
            after:content-[\'\'] after:absolute after:block after:w-10 after:h-[3px] after:bg-[#47b2e4] after:bottom-0 after:left-1/2 after:-translate-x-1/2
            before:content-[\'\'] before:absolute before:block before:w-[120px] before:h-[1px] before:bg-gray-400 before:bottom-[1px] before:left-1/2 before:-translate-x-1/2',
    ]) }}>
    {{ $slot }}
</h2>
