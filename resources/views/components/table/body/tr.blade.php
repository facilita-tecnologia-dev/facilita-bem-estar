@props([
    'flex' => null,
    'noBorder' => null,
    'anchor' => null,
    'href' => null,
])

@php
    $tag = $anchor ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if($anchor && $href) href="{{ $href }}" @endif
    data-role="tr" {{ $attributes }} 
    @class([
        'flex justify-between items-center' => $flex,
        'grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6' => !$flex,
        'border-b border-zinc-200' => !$noBorder,
        'border-b-0' => $noBorder,
        'hover:bg-gray-200 transition' => $anchor
    ])
    >
    {{ $slot }}
</{{ $tag }}>