@props([
    'field',
    'queryParam' => null,
])

@php
    $currentOrder = request('order_by');
    $currentDirection = request('order_direction', 'asc');
    $isOrderingThis = $currentOrder === $field;
    $newDirection = $isOrderingThis && $currentDirection === 'asc' ? 'desc' : 'asc';
    $query = array_merge(request()->query(), [
        'order_by' => $field,
        'order_direction' => $newDirection,
    ]);

    if($queryParam){
        $query = array_merge($queryParam, $query);
    }
@endphp

<span data-role="th" {{ $attributes->merge(['class' => 'text-sm sm:text-base font-semibold']) }}>
    <a href="{{ route(Route::currentRouteName(), $query) }}" class="block w-full">
        {{ $slot }}
    </a>
</span>