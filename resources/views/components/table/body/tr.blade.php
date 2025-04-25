@props([
    'tag' => null,
    'href' => null,
])

@php
    $tag = $tag == 'a' ? 'a' : 'div';
@endphp

<{{ $tag }}
    data-role="tr"
    @if($tag == 'a' && $href)
        href="{{ $href }}" 
    @endif
    {{ $attributes->merge(['class' => 'px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all']) }} 
    >
    {{ $slot }}
</{{ $tag }}>