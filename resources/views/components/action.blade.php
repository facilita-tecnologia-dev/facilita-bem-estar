@props([
    'tag' => 'a',

    'form' => ($tag ?? 'a') === 'button' ? '' : null, // Se for um botão, coloca o form pardrão como null

    'type' => ($tag ?? 'a') === 'button' ? 'submit' : null, // Se for um botão, coloca o type pardrão como submit
    'href' => ($tag ?? 'a') === 'a' ? '#' : null, // Se for um link, coloca o href pardrão como #

    'variant' => 'primary',
    'size' => 'm',

    'color' => 'gray-800',
    'alignment' => 'center',

    'width' => 'fit',
])

@php
    $elementTag = $tag === 'button' ? 'button' : 'a';

    $textAlign = $alignment === 'center' ? 'center' : $alignment;
@endphp

<{{ $elementTag }} 

    {{ $form ? "form=$form" : '' /* Se houver form (se for botão) coloca o atributo form */}}
    {{ $type ? "type=$type" : '' /* Se houver type (se for botão) coloca o atributo type */}}
    {{ $href ? "href=$href" : '' /* Se houver href (se for link) coloca o atributo href */}}
    {{ $attributes }}
    @class([
        'block rounded-md cursor-pointer font-medium transition duration-200 whitespace-nowrap leading-tight flex items-center justify-center gap-2',

        'w-fit' => $width === 'fit', // Aplica os estilos se o width for 'fit'
        'w-full' => $width === 'full', // Aplica os estilos se o width for 'full'

        'px-8 py-3' => $size === 'm', // Aplica os estilos se o size for 'm'
        'px-7 py-1.5' => $size === 's', // Aplica os estilos se o size for 's'

        'bg-fuchsia-50 text-gray-800 border border-[#FF8AAF] hover:bg-fuchsia-100' => $variant === 'primary', // Aplica os estilos se a variant for 'solid'
        'bg-transparent text-gray-800 border border-[#FF8AAF] hover:bg-fuchsia-100' => $variant === 'secondary', // Aplica os estilos se a variant for 'outline'
        '!p-0 bg-transparent hover:underline' => $variant === 'simple', // Aplica os estilos se a variant for 'outline'

        'text-gray-800' => $variant === 'simple' && $color === "gray-800",
        'text-gray-100' => $variant === 'simple' && $color === "gray-100",

        'text-left' => $textAlign === 'left', // Aplica os estilos caso o alinhamento for 'left'
        'text-center' => $textAlign === 'center', // Aplica os estilos caso o alinhamento for 'center'
        'text-right' => $textAlign === 'right', // Aplica os estilos caso o alinhamento for 'right'
        'text-justify' => $textAlign === 'justify', // Aplica os estilos caso o alinhamento for 'right'
    ])
>

    {{ $slot }}

</{{ $elementTag }}>