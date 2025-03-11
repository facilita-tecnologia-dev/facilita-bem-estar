@props([
    'noBorder' => null,
])

<div data-role="th" {{ $attributes }}
    @class([
        'py-2 px-4 text-left  text-white cursor-pointer',
        'border-r-0' => $noBorder,
        'border-r border-zinc-200/50' => !$noBorder,
    ])
>
    {{ $slot }}
</div>