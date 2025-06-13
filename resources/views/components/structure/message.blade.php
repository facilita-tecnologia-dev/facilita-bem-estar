<div {{ $attributes->merge(['class' => 'bg-white/25 w-full px-6 rounded-md shadow-md py-2']) }}>
    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
        {{ $slot }}
    </p>
</div>