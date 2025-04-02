@props([
    'option',
    'questionNumber',
    'optionNumber',
    'pendingAnswer',
])


@php
    $old = '';
    
    if(old('question_' . $questionNumber) === null){
        $old = 'true';
    } else{
        $old = old('question_' . $questionNumber);
    }
@endphp

<label {{ $attributes }} for="question_{{ $questionNumber }}_{{ $optionNumber }}" class="bg-gray-100 w-full px-4 h-[38px] md:h-[45px] flex items-center gap-3 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 transition">
    <input 
        type="radio" 
        name="question_{{ $questionNumber }}" 
        id="question_{{ $questionNumber }}_{{ $optionNumber }}" 
        value="{{ $option['value'] }}" 
        class="hidden"
        data-role="option-checkbox"
        {{ $pendingAnswer ==  $option['value'] || $old == $option['value'] ? 'checked' : '' }}
    />
    <div data-role="option-icon" class="{{ $pendingAnswer ==  $option['value'] || $old == $option['value'] ? 'opacity-100' : 'opacity-0' }}">
        <i class="fa-solid fa-check"></i>
    </div>

    {{ $option['content'] }}
</label>


