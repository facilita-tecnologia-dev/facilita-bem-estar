@props([
    'tag' => 'a',
    'variant' => 'primary',
    'width' => 'fit',
    'disabled' => false,
])

@php
    $elementTag = $tag === 'button' ? 'button' : 'a';
@endphp

<{{ $elementTag }} 
    @class([
        'px-4 py-2 text-sm md:text-base block rounded-md shadow-md cursor-pointer font-medium transition duration-200 whitespace-nowrap leading-tight flex items-center justify-center gap-2',

        'w-fit' => $width === 'fit', // Aplica os estilos se o width for 'fit'
        'w-full' => $width === 'full', // Aplica os estilos se o width for 'full'

        'bg-gray-100/50 text-gray-800 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all' => $variant === 'primary', // Aplica os estilos se a variant for 'solid'
        'border border-[#FF8AAF] bg-gray-100 text-gray-800 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all' => $variant === 'secondary', // Aplica os estilos se a variant for 'solid'
        'border border-red-400 bg-red-400 text-gray-100 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all' => $variant === 'danger', // Aplica os estilos se a variant for 'solid'
    ])
    {{ $disabled == true ? 'disabled' : ''}}
    {{ $attributes }}
>

    {{ $slot }}

</{{ $elementTag }}>


