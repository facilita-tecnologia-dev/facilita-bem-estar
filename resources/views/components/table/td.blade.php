@props([
    'severityColor' => null,
])

<span @class([
    "px-2 py-1 text-sm text-center",
    "text-[#f55547]" => $severityColor == "5",
    "text-[#FFB74D]" => $severityColor == "4",
    "text-[#eddd58]" => $severityColor == "3",
    "text-[#a9f5ac]" => $severityColor == "2",
    "text-[#4CAF50]" => $severityColor == "1",
])>
    {{ $slot }}
</span>