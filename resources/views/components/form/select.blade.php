@props([
    'options' => [],
    'name',
    'label' => null,
    'value' => null,
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
        <select name="{{ $name }}" id="{{ $name }}" class="flex-1 pl-3 pr-9 h-[43px] bg-transparent appearance-none focus:outline-none">
            <option value="" {{ !$value ? 'selected' : '' }}>Todos</option>
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
             
                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>{{ $optionText }}</option>
    
            @endforeach
        </select>
    
        <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2">
            <i class="fa-solid fa-chevron-down"></i>
        </div>
    </div>
</div>