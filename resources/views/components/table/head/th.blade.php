@props([
    'noBorder' => null,
    'lgNone' => null,
    'mdNone' => null,
    'smNone' => null,
])

<div data-role="th" {{ $attributes }}
    @class([
        'text-sm py-1.5 lg:py-3 px-3 lg:px-4 text-left text-gray-800 font-semibold cursor-pointer whitespace-nowrap',
        'border-r-0' => $noBorder,
        'border-r border-zinc-200/50' => !$noBorder,
        'hidden lg:block' => $lgNone,
        'hidden md:block' => $mdNone,
        'hidden sm:block' => $smNone,
    ])
>
    {{ $slot }}
</div>