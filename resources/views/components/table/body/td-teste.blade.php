@props([
    'severity_color' => null,
    'flat' => null,
])

<span @class([
    "p-3 text-sm text-left border-r border-b border-zinc-200",
    "text-black bg-[#f55547]" => !$flat && $severity_color == "5",
    "text-black bg-[#FFB74D]" => !$flat && $severity_color == "4",
    "text-black bg-[#eddd58]" => !$flat && $severity_color == "3",
    "text-black bg-[#a9f5ac]" => !$flat && $severity_color == "2",
    "text-black bg-[#4CAF50]" => !$flat && $severity_color == "1",

    "text-center! text-black bg-gradient-to-l from-[#F5554770] via-[#FFFFFF] to-[#FFFFFF] opacity-100" => $flat && $severity_color == "5",
    "text-center! text-black bg-gradient-to-l from-[#FFB74D70] via-[#FFFFFF] to-[#FFFFFF] opacity-100" => $flat && $severity_color == "4",
    "text-center! text-black bg-gradient-to-l from-[#eddd5870] via-[#FFFFFF] to-[#FFFFFF] opacity-100" => $flat && $severity_color == "3",
    "text-center! text-black bg-gradient-to-l from-[#a9f5ac70] via-[#FFFFFF] to-[#FFFFFF] opacity-100" => $flat && $severity_color == "2",
    "text-center! text-black bg-gradient-to-l from-[#4CAF5070] via-[#FFFFFF] to-[#FFFFFF] opacity-100" => $flat && $severity_color == "1",
])>
    {{ $slot }}
</span>