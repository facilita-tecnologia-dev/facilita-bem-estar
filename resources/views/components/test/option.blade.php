@props([
    'option',
    'id',
    'name',
    'pendingAnswer',
])

<label {{ $attributes }} for="{{ $id }}" class="bg-gray-100 w-full px-4 h-[38px] md:h-[45px] flex items-center gap-3 rounded-md shadow-sm cursor-pointer hover:bg-gray-200 transition">
    <input 
        type="radio" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        value="{{ $option['value'] }}" 
        class="hidden"
        data-role="option-checkbox"
        onchange="scrollToNextQuestion(event)"
        {{ $pendingAnswer ==  $option['value'] || old($name) == $option['value'] ? 'checked' : '' }}
    />
    <div data-role="option-icon" class="{{ $pendingAnswer ==  $option['value'] || old($name) == $option['value']  ? 'opacity-100' : 'opacity-0' }}">
        <i class="fa-solid fa-check"></i>
    </div>

    {{ $option['content'] }}
</label>


