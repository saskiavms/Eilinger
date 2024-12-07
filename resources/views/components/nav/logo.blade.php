@props(['href'])

<h1 class="text-[30px] font-medium tracking-[2px] uppercase m-0 p-0 leading-none">
    <a href="{{ $href }}" class="text-white hover:text-white no-underline hover:no-underline">
        {{ $slot }}
    </a>
</h1>
