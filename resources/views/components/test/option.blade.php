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

<label {{ $attributes }} for="question_{{ $questionNumber }}_{{ $optionNumber }}" class="border-b border-b-zinc-300 bg-transparent px-3 lg:px-4 py-1 lg:py-1.5 flex items-center text-sm sm:text-base gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
    <input 
        type="radio" 
        name="question_{{ $questionNumber }}" 
        id="question_{{ $questionNumber }}_{{ $optionNumber }}" 
        value="{{ $option['value'] }}" 
        class="w-3.5 h-3 rounded-full accent-current"
        {{ $pendingAnswer ==  $option['value'] || $old == $option['value'] ? 'checked' : '' }}
    />
    
    {{ $option['content'] }}
</label>