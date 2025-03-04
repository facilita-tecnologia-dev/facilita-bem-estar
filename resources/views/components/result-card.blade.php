@props([
    'testResult'
])

{{-- @php
    $boxColor = $color ?? 'gray';
@endphp --}}

<div @class([
        "border border-teal-700 flex flex-col p-4 rounded-md shadow-md w-48 h-44",
        "bg-red-300" => $testResult['color'] === 'red',
        "bg-orange-300" => $testResult['color'] === 'orange',
        "bg-yellow-300" => $testResult['color'] === 'yellow',
        "bg-blue-300" => $testResult['color'] === 'blue',
        "bg-green-300" => $testResult['color'] === 'green',
    ])>

    <p class="text-md font-bold text-black text-center">{{ $testResult['testName'] }} </p>
    <h1 
        class="text-md font-normal text-center flex-1 flex items-center justify-center"
    >
        {{ $testResult['severity'] }}
    </h1>
</div>        