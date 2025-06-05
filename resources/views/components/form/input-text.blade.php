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
        @if($type == "password")
            <button data-role="toggle-password-visibility" type="button" data-target="{{ $id ? $id : $name }}" class="absolute right-3 text-gray-700">
                <span data-role="password-hide" class="hidden">
                    <i class="fa-solid fa-eye-slash"></i>
                </span>
                <span data-role="password-show" class="block">
                    <i class="fa-solid fa-eye"></i>
                </span>
            </button>
        @endif

    </x-form.input-wrapper>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
