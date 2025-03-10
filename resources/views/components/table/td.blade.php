@props([
    'severity_color' => null,
])

<span @class([
    "p-2 text-sm text-center",
    "text-black bg-[#f55547]" => $severity_color == "5",
    "text-black bg-[#FFB74D]" => $severity_color == "4",
    "text-black bg-[#eddd58]" => $severity_color == "3",
    "text-black bg-[#a9f5ac]" => $severity_color == "2",
    "text-black bg-[#4CAF50]" => $severity_color == "1",
])>
    {{ $slot }}
</span>