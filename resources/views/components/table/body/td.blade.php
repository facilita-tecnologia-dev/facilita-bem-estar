@props([
    'noBorder' => null,
    'severityColor' => null,
])

<div data-role="td" {{ $attributes }} 
    @class([
        'px-4 py-1.5 text-left text-sm',
        'truncate flex-1' => !$severityColor,
        'border-r border-zinc-200' => !$noBorder,
        'border-0' => $noBorder,

        'bg-gradient-to-l via-[#FFFFFF00] to-[#FFFFFF00] opacity-100' => $severityColor,
        "from-[#F5554780]" => $severityColor == "5",
        "from-[#FFB74D90]" => $severityColor == "4",
        "from-[#eddd5870]" => $severityColor == "3",
        "from-[#a9f5ac75]" => $severityColor == "2",
        "from-[#4CAF5090]" => $severityColor == "1",
    ])>

    {{ $slot }}
</div>