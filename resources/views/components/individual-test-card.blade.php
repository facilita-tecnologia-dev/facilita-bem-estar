@props([
    'severityName',
    'severityColor',
])


<a {{ $attributes }} @class([
    "w-full max-w-80 shadow-md rounded-md border flex flex-col items-center overflow-hidden",
    'border-[#f55547]' => $severityColor == "5",
    'border-[#FFB74D]' => $severityColor == "4",
    'border-[#eddd58]' => $severityColor == "3",
    'border-[#a9f5ac]' => $severityColor == "2",
    'border-[#4CAF50]' => $severityColor == "1",
])>
    <p @class([
        "text-md font-medium py-2 w-full text-center text-zinc-700",
        'bg-[#f55547]' => $severityColor == "5",
        'bg-[#FFB74D]' => $severityColor == "4",
        'bg-[#eddd58]' => $severityColor == "3",
        'bg-[#a9f5ac]' => $severityColor == "2",
        'bg-[#4CAF50]' => $severityColor == "1",
    ])>
        {{ $severityName }}
    </p>

    {{ $slot }}
</a>