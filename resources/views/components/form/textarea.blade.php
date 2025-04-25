@props([
    'name',
    'placeholder' => '',
    'resize' => null,
])

<div class="w-full">
    <div {{ $attributes->merge(['class' => 'w-full flex items-start gap-3 bg-gray-100/50 shadow-md p-3 rounded-md  text-base text-gray-800 placeholder:text-gray-500']) }}>
    
        <textarea type="text"
            class="
                w-full h-full bg-transparent focus:outline-0 min-h-[160px] max-h-[300px] 
                {{ !$resize ? 'resize-none' : ''  }}
            "
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"    
        ></textarea>
    
    </div>
    
    @error($name)
        <x-form.error-span text="{{ $message }}" />
    @enderror
</div>
