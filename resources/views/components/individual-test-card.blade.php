@props([
    'severityName',
    'severityColor',
])


<a {{ $attributes }} @class(["w-full max-w-80 shadow-md rounded-md border flex flex-col items-center overflow-hidden"])>

    <p @class(["text-md font-medium py-2 w-full text-center text-zinc-700"])>
        {{ $severityName }}
    </p>

    {{ $slot }}
</a>