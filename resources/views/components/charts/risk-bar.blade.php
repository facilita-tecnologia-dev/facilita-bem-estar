@props([
    'href',
    'riskName',
    'risk',
])

<a href="{{ $href }}" class="block px-1.5 py-2 border border-transparent hover:border-gray-200 w-full space-y-1 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all hover:shadow-md">
    <p class="text-xs">{{ $riskName }}</p>
    <div data-role="risk-bar" data-value="{{ $risk['riskLevel'] }}" class="relative w-full h-6 border border-gray-800/50 rounded-md overflow-hidden">
        <div class="bar w-0 transition-all duration-700 h-full"></div>
        <p class="text-xs absolute top-1/2 -translate-y-1/2 right-2 text-gray-800">{{ $risk['riskCaption'] }}</p>
    </div>
</a>