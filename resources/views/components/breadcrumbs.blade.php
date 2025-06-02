@props([
    'links' => []
])

<nav aria-label="breadcrumb" class="px-2">
    <ul class="flex items-center gap-1.5">
        
        @foreach ($links as $label => $url)
            <span class="text-sm">\</span>

            @if (!$url)
                <li class="text-sm">{{ $label }}</li>
            @else
                <li class="text-sm">
                    <a href="{{ $url }}" class="underline">
                        {{ $label }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>