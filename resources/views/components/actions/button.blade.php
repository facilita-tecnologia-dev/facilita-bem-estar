@props([
    'variant',
    'color',
])


<button {{ $attributes }} @class([
    "block text-center px-4 py-2 cursor-pointer rounded-md text-sm md:text-base font-normal transition",
    "bg-fuchsia-600 text-fuchsia-50 hover:bg-fuchsia-400" => $color === 'success' && $variant == 'solid',
    "bg-rose-700 text-rose-50 hover:bg-rose-400" => $color === 'danger' && $variant == 'solid',
    "bg-transparent border border-fuchsia-600 text-fuchsia-600 hover:bg-fuchsia-600 hover:text-white" => $color === 'success' && $variant == 'outline',
    "bg-transparent border border-rose-700 text-rose-700 hover:bg-rose-700 hover:text-white" => $color === 'danger' && $variant == 'outline',
])>
    {{ $slot }}
</button>

