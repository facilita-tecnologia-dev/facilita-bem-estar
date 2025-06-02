@props([
    'options' => [],
    'name',
    'label' => null,
    'value' => null,
    'defaultValue' => false,
    'disabled' => false,
])

<div class="w-full">
    @if($label)
        <x-form.input-label for="{{ $name }}">
            {{ $label }}
        </x-form.input-label>
    @endif

    <x-form.input-wrapper>
        <select  name="{{ $name }}" id="{{ $name }}" {{ $disabled ? 'disabled' : '' }} class="flex-1 pl-3 pr-9 bg-transparent appearance-none focus:outline-none cursor-pointer">
            @if($defaultValue)
                <option value="" {{ !$value ? 'selected' : '' }}>Todos</option>
            @endif
            @foreach ($options as $option)
                @php
                    $optionText = isset($option['option']) ? $option['option'] : $option;
                    $optionValue = "";
    
                    if(is_array($option) && isset($option['value'])){
                        $optionValue = $option['value'];
                    } else if(is_array($option) && isset($option['option'])){
                        $optionValue = $option['option'];
                    } else{
                        $optionValue = $option;
                    }
                @endphp
             
                <option class="px-2" value="{{ $optionValue }}" {{ old($name) == $optionValue || $value == $optionValue ? 'selected' : '' }}>
                    {{ $optionText }}
                </option>
                
            @endforeach
        </select>
    
        <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2">
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </div>
    </x-form.input-wrapper>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>