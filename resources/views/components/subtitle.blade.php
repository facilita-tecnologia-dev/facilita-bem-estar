@props([
    'alignment' => 'center',
])

@php
    $textAlign = $alignment === 'center' ? 'center' : $alignment;
@endphp

<span 
    @class([
        'block w-fit text-center font-semibold leading-tight text-xl md:text-[1.375rem] lg:text-2xl text-gray-500',

        'text-left' => $textAlign === 'left', // Aplica os estilos caso o alinhamento for 'left'
        'text-center' => $textAlign === 'center', // Aplica os estilos caso o alinhamento for 'center'
        'text-right' => $textAlign === 'right', // Aplica os estilos caso o alinhamento for 'right'
    ])
>

    {{ $slot }}

</span>