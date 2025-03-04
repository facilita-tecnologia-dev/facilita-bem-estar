@props([
    'option',
    'questionNumber',
    'optionNumber',
])

<label for="question_{{ $questionNumber }}_{{ $optionNumber }}" class="border-b border-b-zinc-300 bg-transparent px-4 py-1.5 flex items-center gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
    <input 
        type="radio" 
        name="question_{{ $questionNumber }}" 
        id="question_{{ $questionNumber }}_{{ $optionNumber }}" 
        value="{{ $option['value'] }}" 
        class="w-3.5 h-3 rounded-full accent-current"
        {{-- {{ old('question_'.($optionNumber + 1)) == '1' ? 'checked' : '' }} --}}
    />
    
    {{ $option['content'] }}
</label>