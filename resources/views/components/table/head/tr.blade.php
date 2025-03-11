@props([
    'flex' => null,
])

<div data-role="tr" {{ $attributes }}
    @class([
        'bg-teal-700',
        'flex justify-between items-center' => $flex,
        'grid grid-cols-5' => !$flex,
    ])
>
    {{ $slot }}
</div>