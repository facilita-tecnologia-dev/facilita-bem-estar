@props([
    'riskName',
    'risk',
])

<div class="w-full space-y-1">
    <p class="text-xs">{{ $riskName }}</p>
    <div data-role="risk-bar" data-value="{{ $risk['score'] }}" class="relative w-full h-6 border border-gray-800/50 rounded-md overflow-hidden">
        <div class="bar w-0 transition-all duration-700 h-full"></div>
        <p class="text-xs absolute top-1/2 -translate-y-1/2 right-2 text-gray-800">{{ $risk['risk'] }}</p>
    </div>
</div>