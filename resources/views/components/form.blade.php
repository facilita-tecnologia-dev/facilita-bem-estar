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

<form action="{{ $action }}" class="flex-1 overflow-auto w-full space-y-5" method="{{ $method }}" {{ $attributes }}>
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