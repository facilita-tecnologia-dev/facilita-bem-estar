@props([
    'links' => []
])

<nav aria-label="breadcrumb" class="px-2">
    <ul class="flex items-center gap-2">
        @foreach ($links as $label => $url)
            @if ($loop->last)
                <li class="text-sm" aria-current="page">{{ $label }}</li>
            @else
                <li class="text-sm">
                    <a href="{{ $url }}" class="underline">
                        {{ $label }}
                    </a>
                </li>
                <i class="fa-solid fa-chevron-right text-xs"></i>
            @endif
        @endforeach
    </ul>
</nav>