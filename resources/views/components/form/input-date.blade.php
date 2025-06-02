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
        <x-form.input-label for="{{ $id ? $id : $name }}">
            {{ $label }}
        </x-form.input-label>
    @endif


        <x-form.input-wrapper>
            <input type="{{ $type ?? 'date' }}"
                {{ $attributes->merge(['class' => 'w-full focus:outline-0 bg-transparent px-3 cursor-pointer']) }}
                name="{{ $name }}"
                id="{{ $id ? $id : $name }}"
                value="{{ old($name, $value) }}"
                {{ $disabled ? 'disabled' : '' }}
            >
        </x-form.input-wrapper>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
