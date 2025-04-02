<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-4xl text-gray-800 font-semibold text-left">{{ $testName }}</h2>
            </div>

            <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-lg"></i>
                    Resultados do teste de {{ trim($testName) }} divididos por setor.
                </p>
            </div>
    
            <div class="w-full grid grid-cols-4 gap-4">
                @foreach ($testStats as $occupationName => $testSeverity)
                    <a href="{{ route('test-results-list.dashboard', $testName)}}" class="bg-white/25 rounded-md px-4 py-5 shadow-md space-y-2">
                        <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                            <span class="text-base font-semibold text-left">{{ $occupationName }}</span>
                        </div>

                        @foreach ($testSeverity['severities'] as $severityName => $testsQty )
                            <div 
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] flex items-center justify-between
                                    {{ $testsQty['severity_color'] == "5" ? 'to-[#fc6f6f50]' : '' }}
                                    {{ $testsQty['severity_color'] == "4" ? 'to-[#febc5350]' : '' }}
                                    {{ $testsQty['severity_color'] == "3" ? 'to-[#faed5d50]' : '' }}
                                    {{ $testsQty['severity_color'] == "2" ? 'to-[#8cf8c050]' : '' }}
                                    {{ $testsQty['severity_color'] == "1" ? 'to-[#76fc7150]' : '' }}
                                ">
                                <span>{{ $severityName }}</span>
                                <span>{{ round($testsQty['count'] / $testSeverity['total'] * 100) }}%</span>
                            </div>
                        @endforeach
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>