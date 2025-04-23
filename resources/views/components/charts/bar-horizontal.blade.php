@props(['tag' => null, 'id', 'title'])

@php
    $elementTag = $tag === 'a' ? 'a' : 'div';
@endphp

<{{ $elementTag }} 
    {{ $attributes->merge(['class' => 'w-full p-6 flex flex-col justify-start gap-5 items-center shadow-md rounded-md flex flex-col items-center bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all']) }}
>
    <p class="text-center font-semibold">{{ $title }}</p>
    <div class="w-full min-h-40" id="{{ $id }}"></div>
</{{ $elementTag }}>
