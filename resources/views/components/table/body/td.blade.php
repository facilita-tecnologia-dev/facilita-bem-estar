@props([
    'noBorder' => null,
    'severityColor' => null
])

<div data-role="td" {{ $attributes }} 
    @class([
        'px-4 py-2 text-left',
        'border-r border-zinc-200' => !$noBorder,
        'border-0' => $noBorder,

        'bg-gradient-to-l via-[#FFFFFF00] to-[#FFFFFF00] opacity-100' => $severityColor,
        "from-[#F5554770]" => $severityColor == "5",
        "from-[#FFB74D70]" => $severityColor == "4",
        "from-[#eddd5870]" => $severityColor == "3",
        "from-[#a9f5ac70]" => $severityColor == "2",
        "from-[#4CAF5070]" => $severityColor == "1",
    ])>

    {{ $slot }}
</div>