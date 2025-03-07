@props([
    'testResult'
])

<div @class([
        "border border-teal-700 flex flex-col p-4 rounded-md shadow-md",
        "bg-[#f55547]" => $testResult['severityColor'] === 5,
        "bg-[#FFB74D]" => $testResult['severityColor'] === 4,
        "bg-[#eddd58]" => $testResult['severityColor'] === 3,
        "bg-[#a9f5ac]" => $testResult['severityColor'] === 2,
        "bg-[#4CAF50]" => $testResult['severityColor'] === 1,
    ])>

    <p class="text-md font-bold text-black text-center">{{ $testResult['testName'] }} </p>
    <h1 
        class="text-md font-normal text-center flex-1 flex items-center justify-center py-5"
    >
        {{ $testResult['severityTitle'] }}
    </h1>
</div>        