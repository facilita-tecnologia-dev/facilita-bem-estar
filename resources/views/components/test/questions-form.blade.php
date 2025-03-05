@props([
    'action',
    'post' => null,
    'put' => null,
    'patch' => null,
    'delete' => null,
])

@php
    $method = $post || $put || $patch || $delete ? 'POST':'GET'
@endphp

<form action="{{ $action }}" class="flex-1 overflow-auto w-full space-y-5" method="{{ $method }}" {{ $attributes }}>
    @csrf

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


    <div class="flex flex-col items-center gap-4">
        {{ $slot }}
    </div>
</form>