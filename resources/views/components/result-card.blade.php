@props([
    'testResult'
])


<div @class([
        "border border-zinc-300 overflow-hidden flex flex-col gap-2 p-4 rounded-md shadow-md relative",
    ])>

    <div @class([
            "absolute left-0 top-0 w-full h-full bg-gradient-to-tr to-[#FFFFFF] opacity-60",
            "from-[#F55547] to-[#F87468]" => $testResult['severity_color'] == 5,
            "from-[#FFB74D] " => $testResult['severity_color'] == 4,
            "from-[#eddd58]" => $testResult['severity_color'] == 3,
            "from-[#a9f5ac]" => $testResult['severity_color'] == 2,
            "from-[#4CAF50] " => $testResult['severity_color'] == 1,
        ])>
    </div>

    <p class="text-lg font-bold text-zinc-800 text-left relative">{{ $testResult['test_name'] }} </p>
    <h1 
        class="text-sm font-normal text-left relative"
        >
        {{ $testResult['severity_title'] }}
    </h1>
</div>        