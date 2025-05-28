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
        <label
            class="block text-base font-semibold mb-1 text-gray-800"
            for="{{ $name }}"
        >
            {{ $label }}
        </label>
    @endif
        
    <div for="role" class="relative w-full flex items-center overflow-hidden gap-3 bg-gray-100 rounded-md border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500">
        <select multiple  name="{{ $name }}" id="{{ $name }}" {{ $disabled ? 'disabled' : '' }} class="flex-1 p-1.5 h-[140px] bg-transparent appearance-none focus:outline-none">
            @if($defaultValue)
                <option class="p-1.5 cursor-pointer rounded-md mt-1 checked:bg-gradient-to-b checked:from-sky-300/50 checked:to-sky-300/50" value="Todos" {{ in_array('Todos', $value ?? []) ? 'selected' : '' }}>Todos</option>
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
                    // dump($value, old($name), old($name) == $optionValue || $value == $optionValue ? 'selected' : '');
                @endphp

                <option class="p-1.5 cursor-pointer rounded-md mt-1 checked:bg-gradient-to-b checked:from-sky-300/50 checked:to-sky-300/50" value="{{ $optionValue }}" {{ in_array($optionValue, old($name, $value ?? [])) ? 'selected' : '' }}>{{ $optionText }}</option>
                
            @endforeach
        </select>
    </div>

    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>