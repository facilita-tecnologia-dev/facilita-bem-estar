@props([
    'title',
    'back' => null,
    'centered' => null,
    'breadcrumbs' => null,
])

<div class="w-full space-y-4">
    <div class="w-full flex {{ $centered ? 'justify-center' : 'justify-between' }} gap-4">
        <h2 {{ $attributes->merge(['class' => 'bg-white/25 w-fit px-4 py-1.5 rounded-md shadow-md text-xl md:text-2xl text-gray-800 font-semibold text-left truncate']) }}>
            {{ $title }}
        </h2>
    
        @if($back)
            <x-action href="{{ $back }}">
                <i class="fa-solid fa-arrow-left text-sm sm:text-base"></i>
            </x-action>
        @endif
    </div>

    @if($breadcrumbs)
        <x-breadcrumbs :links="$breadcrumbs" />
    @endif
</div>