@props([
    'severityName',
    'severityColor',
])


<div class="flex flex-col w-full">
    
    <a class="w-full max-w-80 shadow-md rounded-md border flex flex-col items-center overflow-hidden">
    
        <p class="text-md font-medium py-2 w-full text-center text-zinc-100 bg-teal-700">
            {{ $occupationName }}
        </p>
        
        @foreach ($testSeverity['tests'] as $severityName => $testsQty )
        
            <p class="w-full py-1.5 px-3 hover:bg-gray-200 transition cursor-pointer flex items-center justify-between">
                {{ $severityName }}
                
                <span>{{ round($testsQty / $testSeverity['total'] * 100) }}%</span>
            </p>
        @endforeach
    
    </a>

</div>