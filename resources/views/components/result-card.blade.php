@props([
    'testResult'
])

<div @class([
        "border border-teal-700 flex flex-col p-4 rounded-md shadow-md w-48 h-44",
        "bg-red-300" => $testResult['severityColor'] === 'red',
        "bg-orange-300" => $testResult['severityColor'] === 'orange',
        "bg-yellow-300" => $testResult['severityColor'] === 'yellow',
        "bg-blue-300" => $testResult['severityColor'] === 'blue',
        "bg-green-300" => $testResult['severityColor'] === 'green',
    ])>

    <p class="text-md font-bold text-black text-center">{{ $testResult['testName'] }} </p>
    <h1 
        class="text-md font-normal text-center flex-1 flex items-center justify-center"
    >
        {{ $testResult['severityTitle'] }}
    </h1>
</div>        