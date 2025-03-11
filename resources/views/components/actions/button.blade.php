@props([
    'variant',
    'color',
])


<button {{ $attributes }} @class([
    "px-4 py-1 cursor-pointer rounded-md text-md font-medium transition",
    "bg-teal-700 text-teal-50 hover:bg-teal-400" => $color === 'success' && $variant == 'solid',
    "bg-rose-700 text-rose-50 hover:bg-rose-400" => $color === 'danger' && $variant == 'solid',
    "bg-transparent border border-teal-700 text-teal-700 hover:bg-teal-700 hover:text-white" => $color === 'success' && $variant == 'outline',
    "bg-transparent border border-rose-700 text-rose-700 hover:bg-rose-700 hover:text-white" => $color === 'danger' && $variant == 'outline',
])>
    {{ $slot }}
</button>

