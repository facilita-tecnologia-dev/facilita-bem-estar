@props(['tag' => null, 'id', 'title'])

@php
    $elementTag = $tag === 'a' ? 'a' : 'div';
@endphp

<{{ $elementTag }}
    data-role="chart"
    {{ $attributes->merge(['class' => 'w-full h-full p-3 md:p-5 flex flex-col justify-start gap-4 items-center shadow-md rounded-md bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all']) }}
>
    <p class="text-center font-semibold">{{ $title }}</p>
    <div class="w-full max-h-64 h-64 overflow-auto" id="{{ $id }}"></div>
</{{ $elementTag }}>
