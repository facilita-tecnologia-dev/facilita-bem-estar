@props([
    'title',
    'back' => null,
])

<div class="w-full flex {{ Route::is('test') ? 'justify-center' : 'justify-between' }} gap-4">
    <h2 {{ $attributes->merge(['class' => 'bg-white/25 w-fit px-4 py-1.5 rounded-md shadow-md text-xl md:text-2xl text-gray-800 font-semibold text-left truncate']) }}>
        {{ $title }}
    </h2>

    @if($back)
        <x-action href="{{ $back }}">
            <i class="fa-solid fa-arrow-left text-sm sm:text-base"></i>
        </x-action>
    @endif
</div>