<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">{{ $testName }}</h2>
            </div>

            <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-lg"></i>
                    Resultados do teste de {{ trim($testName) }} divididos por setor.
                </p>
            </div>
    
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($resultsPerDepartment as $departmentName => $departmentData)
                        
                    <a href="{{ route('dashboard.psychosocial-results-list', [$testName, 'department' => $departmentName])}}" class="bg-white/25 rounded-md px-4 py-5 shadow-md space-y-2 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                            <span class="text-base font-semibold text-left">{{ $departmentName }}</span>
                        </div>

                        @foreach ($departmentData['severities'] as $severityName => $severityData )
                            <div 
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] flex items-center justify-between gap-2
                                    {{ $severityData['severity_color'] == App\Enums\SeverityEnum::CRITICO->value ? 'to-[#fc6f6f50]' : '' }}
                                    {{ $severityData['severity_color'] == App\Enums\SeverityEnum::ALTO->value ? 'to-[#febc5350]' : '' }}
                                    {{ $severityData['severity_color'] == App\Enums\SeverityEnum::MEDIO->value ? 'to-[#faed5d50]' : '' }}
                                    {{ $severityData['severity_color'] == App\Enums\SeverityEnum::BAIXO->value ? 'to-[#8cf8c050]' : '' }}
                                    {{ $severityData['severity_color'] == App\Enums\SeverityEnum::MINIMO->value ? 'to-[#76fc7150]' : '' }}
                                ">
                                <span class="truncate">{{ $severityName }}</span>
                                <span>{{ round($severityData['count'] / $departmentData['total'] * 100) }}%</span>
                            </div>
                        @endforeach
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>