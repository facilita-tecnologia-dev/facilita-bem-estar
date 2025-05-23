@props([
    'name',
    'id' => null,
    'placeholder' => '',
    'type' => 'text',
    'value' => null,
    'label' => null,
    'disabled' => null,
    'readonly' => null,
    
    'icon' => null,
])

<div data-role="input-text" class="w-full">
    @if($label)
        <label
            class="block text-base font-semibold mb-1 text-gray-800"
            for="{{ $id ? $id : $name }}"
        >
            {{ $label }}
        </label>
    @endif

    <div class="relative w-full flex items-center gap-3 bg-gray-100 rounded-md px-3 border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500">
    
        @if($icon && $icon === 'search')
            <i class="fa-solid fa-magnifying-glass text-gray-800"></i>
        @endif

        @if($icon && $icon === 'id')
            <i class="fa-solid fa-id-card text-gray-800"></i>
        @endif

        <input type="{{ $type }}"
            class="w-full h-[43px] focus:outline-0 bg-transparent"
            name="{{ $name }}"
            id="{{ $id ? $id : $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $attributes }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
        >

        @if($icon && $icon === '%')
            <i class="fa-solid fa-percent text-gray-800"></i>
        @endif

    </div>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
