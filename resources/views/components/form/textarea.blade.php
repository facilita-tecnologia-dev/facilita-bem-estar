@props([
    'name',
    'placeholder' => '',
])

<div class="w-full">
    <div {{ $attributes->merge(['class' => 'w-full flex items-start gap-3 bg-gray-200 p-3 rounded-md  text-base text-gray-800 placeholder:text-gray-500 border border-gray-300']) }}>
    
        <textarea type="text"
            class="w-full h-full focus:outline-0 min-h-[160px] max-h-[300px] resize-none"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"    
        ></textarea>
    
    </div>
    
    @error($name)
        <x-error-span text="{{ $message }}" />
    @enderror
</div>
