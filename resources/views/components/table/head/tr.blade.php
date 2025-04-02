@props([
    'flex' => null,
])

<div data-role="tr" {{ $attributes }}
    @class([
        'bg-fuchsia-600',
        'flex justify-between items-center' => $flex,
        'grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6' => !$flex,
    ])
>
    {{ $slot }}
</div>