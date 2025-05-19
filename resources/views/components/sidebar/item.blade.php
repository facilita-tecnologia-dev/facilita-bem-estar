@props([
    'href' => '',
    'active' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => "px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"]) }}>
    {{ $slot }}
</a>