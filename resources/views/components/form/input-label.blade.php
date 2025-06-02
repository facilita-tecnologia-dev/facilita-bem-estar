@props([
    'for',
])

<label class="block text-sm font-semibold mb-1 text-gray-800" for="{{ $for }}">
    {{ $slot }}
</label>