@props([
    'href',
    'variant',
    'color'
])


<a href="{{ $href }}" {{ $attributes }} @class([
    "block text-center cursor-pointer rounded-md text-sm md:text-base font-normal transition",
    'px-4 py-2' => $variant !== 'underline',
    "bg-fuchsia-600 text-white hover:bg-fuchsia-400" => $color === 'success' && $variant == 'solid',
    "bg-rose-700 text-white hover:bg-rose-400" => $color === 'danger' && $variant == 'solid',

    "bg-transparent border border-fuchsia-600 text-fuchsia-600 hover:bg-fuchsia-600 hover:text-white" => $color === 'success' && $variant == 'outline',
    "bg-transparent border border-rose-700 text-rose-700 hover:bg-rose-700 hover:text-white" => $color === 'danger' && $variant == 'outline',

    "underline font-normal text-fuchsia-400 mt-4" => $color === 'success' && $variant == 'underline',
    "underline font-normal text-rose-400 mt-4" => $color === 'danger' && $variant == 'underline',
])>
    {{ $slot }}
</a>

