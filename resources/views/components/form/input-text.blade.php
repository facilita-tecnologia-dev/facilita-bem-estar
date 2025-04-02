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
            class="text-lg font-semibold mb-3"
            for="{{ $id ? $id : $name }}"
        >
            {{ $label }}
        </label>
    @endif

    
    <div class="relative p-[1px] rounded-md overflow-hidden">
        <div style="background: linear-gradient(to right, #FF8AAF, #6BC5FF);" class="absolute left-0 top-0 bottom-0 right-0"></div>
        <div {{ $attributes->merge(['class' => 'relative w-full flex items-center gap-3 bg-gray-100 rounded-md px-3 text-base text-gray-800 placeholder:text-gray-500']) }}>
        
            @if($icon && $icon === 'search')
                <i class="fa-solid fa-magnifying-glass text-gray-800"></i>
            @endif

            <input type="{{ $type }}"
                class="w-full h-[45px] focus:outline-0 bg-transparent"
                name="{{ $name }}"
                id="{{ $id ? $id : $name }}"
                placeholder="{{ $placeholder }}"
                value="{{ $value }}"
                {{ $disabled ? 'disabled' : '' }}
            >
        </div>
    </div>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
