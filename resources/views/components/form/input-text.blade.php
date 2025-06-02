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
        <x-form.input-label for="{{ $id ? $id : $name }}">
            {{ $label }}
        </x-form.input-label>
    @endif

    <x-form.input-wrapper>
    
        @if($icon && $icon === 'search')
            <i class="fa-solid fa-magnifying-glass text-gray-800"></i>
        @endif

        @if($icon && $icon === 'id')
            <i class="fa-solid fa-id-card text-gray-800"></i>
        @endif

        <input type="{{ $type }}"
            class="w-full focus:outline-0 bg-transparent px-3"
            name="{{ $name }}"
            id="{{ $id ? $id : $name }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $attributes }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
        >

    </x-form.input-wrapper>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
