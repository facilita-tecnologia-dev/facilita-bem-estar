@props([
    'alignment' => 'center',
    'color' => null,
])

@php
    $textAlign = $alignment === 'center' ? 'center' : $alignment;
    
    $textColor = $color === 'gray-100' ? 'gray-100' : 'gray-800';
@endphp

<p 
    @class([
        'text-base md:text-lg font-normal leading-relaxed',

        'text-left' => $textAlign === 'left', // Aplica os estilos caso o alinhamento for 'left'
        'text-center' => $textAlign === 'center', // Aplica os estilos caso o alinhamento for 'center'
        'text-right' => $textAlign === 'right', // Aplica os estilos caso o alinhamento for 'right'
        'text-justify' => $textAlign === 'justify', // Aplica os estilos caso o alinhamento for 'right'

        'text-gray-800' => $textColor === 'gray-800', // Aplica os estilos caso a cor for 'gray-800'
        'text-gray-100' => $textColor === 'gray-100', // Aplica os estilos caso a cor for 'gray-100'
    ])>

    {{ $slot }}

</p>