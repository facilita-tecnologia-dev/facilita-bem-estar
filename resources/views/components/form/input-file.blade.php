@props([
    'name',
    'id' => null,
    'value' => null,
    'accept' => ".jpg, .jpeg, .webp, .png, .svg",
])

<label {{ $attributes->merge(['class' => 'w-full flex items-center gap-3 pr-2 cursor-pointer bg-transparent overflow-hidden h-[45px] rounded-md text-base text-gray-800 placeholder:text-gray-500 border border-[#FF8AAF]']) }}>
    <div class="bg-fuchsia-50 px-4 h-full flex items-center">
        <span class="text-gray-800 text-sm md:text-md whitespace-nowrap flex items-center gap-2">
            <i class="fa-solid fa-upload"></i>
            Escolha um arquivo
        </span>
    </div>
    
    <span class="filename whitespace-nowrap text-sm md:text-md truncate">{{ $value }}</span>

    <input type="file" class="hidden" name="{{ $name }}" id="{{ $id ? $id : $name }}" value="{{ $value }}" accept="{{ $accept }}"" onchange="getFileName(event)">
</label>

<script>
    function getFileName(event) {
        const input = event.target;
        const arquivo = input.files[0];
        
        if (arquivo) {
            const label = input.parentElement;
            const nomeArquivo = arquivo.name;

            label.querySelector('.filename').innerText = nomeArquivo;
        }
    }
</script>