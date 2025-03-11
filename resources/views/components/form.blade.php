@props([
    'action' => null,
    'post' => null,
    'put' => null,
    'patch' => null,
    'delete' => null,
])

@php
    $method = $post || $put || $patch || $delete ? 'POST':'GET'
@endphp

<form action="{{ $action }}" {{ $attributes->merge(['class' => '']) }} method="{{ $method }}">
    @if($method !== 'GET')
        @csrf
    @endif

    @if($post)
        @method('post')
    @endif

    @if($put)
        @method('put')
    @endif
    
    @if($patch)
        @method('patch')
    @endif
    
    @if($delete)
        @method('delete')
    @endif


    {{ $slot }}
</form>

