@props([
    'href',
    'variant',
    'color'
])


<a href="{{ $href }}" {{ $attributes }} @class([
    "block text-center cursor-pointer rounded-md text-sm md:text-base font-normal transition",
    'px-4 py-2' => $variant !== 'underline',
    "bg-teal-700 text-white hover:bg-teal-400" => $color === 'success' && $variant == 'solid',
    "bg-rose-700 text-white hover:bg-rose-400" => $color === 'danger' && $variant == 'solid',

    "bg-transparent border border-teal-700 text-teal-700 hover:bg-teal-700 hover:text-white" => $color === 'success' && $variant == 'outline',
    "bg-transparent border border-rose-700 text-rose-700 hover:bg-rose-700 hover:text-white" => $color === 'danger' && $variant == 'outline',

    "underline font-normal text-teal-400 mt-4" => $color === 'success' && $variant == 'underline',
    "underline font-normal text-rose-400 mt-4" => $color === 'danger' && $variant == 'underline',
])>
    {{ $slot }}
</a>

