@props([
    'name',
    'id' => null,
    'placeholder' => '',
    'type' => null,
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


        <div class="relative w-full flex items-center gap-3 bg-gray-100 rounded-md px-3 border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500">

            <input type="{{ $type ?? 'date' }}"
                {{ $attributes->merge(['class' => 'w-full h-[43px] focus:outline-0 bg-transparent']) }}
                name="{{ $name }}"
                id="{{ $id ? $id : $name }}"
                value="{{ old($name, $value) }}"
                {{ $disabled ? 'disabled' : '' }}
            >
        </div>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
