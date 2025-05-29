@props([
    'title',
])

<div {{ $attributes->merge(['class' => 'submenu space-y-3']) }}>
    <p class="uppercase text-xs font-semibold px-2">{{ $title }}</p>
    {{ $slot }}
</div>