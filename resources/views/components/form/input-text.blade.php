@props([
    'name',
    'id' => null,
    'placeholder' => '',
    'type' => 'text',
    'value' => null,
    'label' => null,
    'disabled' => null,
    
    'icon' => null,
])

<div class="w-full">
    @if($label)
        <label
            class="block text-base font-semibold mb-1 text-gray-800"
            for="{{ $id ? $id : $name }}"
        >
            {{ $label }}
        </label>
    @endif

    <div {{ $attributes->merge(['class' => 'relative w-full flex items-center gap-3 bg-gray-100 rounded-md px-3 border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500']) }}>
    
        @if($icon && $icon === 'search')
            <i class="fa-solid fa-magnifying-glass text-gray-800"></i>
        @endif

        <input type="{{ $type }}"
            class="w-full h-[43px] focus:outline-0 bg-transparent"
            name="{{ $name }}"
            id="{{ $id ? $id : $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $disabled ? 'disabled' : '' }}
        >

        @if($icon && $icon === '%')
            <i class="fa-solid fa-percent text-gray-800"></i>
        @endif

    </div>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
