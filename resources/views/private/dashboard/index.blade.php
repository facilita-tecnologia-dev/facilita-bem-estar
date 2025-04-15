<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-gray-100/60 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Dashboard</h2>
            </div>

            <div class="relative w-fit bg-gray-100/60 rounded p-1 shadow-inner grid grid-cols-2 gap-2">
                <div data-role="toggle-bg" class="absolute w-[calc(50%-8px)] h-9 bg-gray-200 shadow-sm rounded-md left-1 top-1 transition-all"></div>
                <button data-role="toggle-collection-tab" id="psychosocial-risks" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Riscos Psicossociais</button>
                <button data-role="toggle-collection-tab" id="organizational-climate" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Clima Organizacional</button>
            </div>

            
            <div id="psychosocial-risks-tab" data-role="collection-tab" class="contents">
                @if($dashboardResults)
                    <div class="w-full flex flex-col md:flex-row gap-4">
                        <div class="bg-gray-100/60 w-full px-6 py-2 rounded-md shadow-md">
                            <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                                <i class="fa-solid fa-circle-info text-lg"></i>
                                Se a adesão for inferior à 75% os resultados não devem ser considerados válidos.
                            </p>
                        </div>
                        <a href="{{ route('dashboard.risks') }}" class="whitespace-nowrap py-2 px-4 bg-gray-100/60 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                            Visualizar Riscos
                        </a>
                    </div>
                @endif

                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 ">
                    <div class="shadow-md rounded-md w-full bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <div class="w-full h-full p-5 flex flex-col justify-start gap-4 items-center">
                            <p class="text-center font-semibold">Participação nos testes</p>
                            <div class="w-40 h-40 xl:w-44 xl:h-44" id="Participação">
                                
                            </div>
                        </div>
                    </div>
                    <div class="shadow-md rounded-md w-full bg-gray-100/60 relative sm:col-span-1 lg:col-span-2 xl:col-span-3 left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <div class="w-full h-full p-5 flex flex-col justify-start gap-4 items-center">
                            <p class="text-center font-semibold">Indicadores (%)</p>
                            <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-3">
                                @foreach ($metrics as $metric)
                                    <div class="w-full">
                                        <p class="text-sm">{{ $metric->metricType->display_name }}</p>
                                            @if($metric->value != 'null')
                                                <div data-role="metric-bar" data-value="{{ $metric->value }}" class="relative border border-gray-800/50  w-full rounded-md overflow-hidden">
                                                <div class="bar h-6 bg-[#64B5F6]"></div>
                                                <p class="text-xs absolute right-2 text-gray-800 top-1/2 -translate-y-1/2">{{ $metric->value }}%</p>
                                            @else
                                                <div data-role="metric-bar" data-value="0" class="relative border border-gray-800/50  w-full rounded-md overflow-hidden">
                                                <div class="bar h-6"></div>
                                                <p class="text-sm absolute right-2 text-gray-800 top-1/2 -translate-y-1/2">Indicador não disponível</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if($dashboardResults)
                        @foreach ($dashboardResults['psychosocial-risks'] as $testName => $testData)
                            <div class="shadow-md rounded-md w-full flex flex-col items-center bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                                <a href="{{ route('dashboard.test-result-per-department', $testName)}}" class="w-full px-2 py-6 flex flex-col justify-start gap-5 items-center">
                                    <p class="text-center font-semibold">{{ $testName }}</p>
                                    <div class="w-40 h-40 xl:w-44 xl:h-44" id="{{ $testName }}">
                                        
                                    </div>
                                    @if(isset($testData['risks']))
                                        <div class="w-full space-y-2 px-4">
                                            @foreach ($testData['risks'] as $riskName => $risk)
                                                <div class="w-full space-y-1">
                                                    <p class="text-xs">{{ $riskName }}</p>
                                                    <div data-role="risk-bar" data-value="{{ $risk['score'] }}" class="relative w-full h-6 border border-gray-800/50 rounded-md overflow-hidden">
                                                        <div class="bar bg-red-500 h-full"></div>
                                                        <p class="text-xs absolute top-1/2 -translate-y-1/2 right-2 text-gray-800">{{ $risk['risk'] }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div id="organizational-climate-tab" data-role="collection-tab" class="hidden">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4 ">
                    {{-- <div class="shadow-md rounded-md w-full bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <div class="w-full h-full p-5 flex flex-col justify-start gap-4 items-center">
                            <p class="text-center font-semibold">Participação nos testes</p>
                            <div class="w-40 h-40 xl:w-44 xl:h-44" id="Participação">
                                
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="shadow-md rounded-md w-full bg-gray-100/60 relative sm:col-span-1 lg:col-span-2 xl:col-span-3 left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <div class="w-full h-full p-5 flex flex-col justify-start gap-4 items-center">
                            <p class="text-center font-semibold">Indicadores (%)</p>
                            <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-3">
                                @foreach ($metrics as $metric)
                                    <div class="w-full">
                                        <p class="text-sm">{{ $metric->metricType->display_name }}</p>
                                            @if($metric->value != 'null')
                                                <div data-role="metric-bar" data-value="{{ $metric->value }}" class="relative border border-gray-800/50  w-full rounded-md overflow-hidden">
                                                <div class="bar h-6 bg-[#64B5F6]"></div>
                                                <p class="text-xs absolute right-2 text-gray-800 top-1/2 -translate-y-1/2">{{ $metric->value }}%</p>
                                            @else
                                                <div data-role="metric-bar" data-value="0" class="relative border border-gray-800/50  w-full rounded-md overflow-hidden">
                                                <div class="bar h-6 bg-[#64B5F6]"></div>
                                                <p class="text-sm absolute right-2 text-gray-800 top-1/2 -translate-y-1/2">Indicador não disponível</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div> --}}
                    <div class="shadow-md rounded-md w-full flex flex-col items-center bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <a href="" class="w-full p-6 flex flex-col justify-start gap-5 items-center">
                            <p class="text-center font-semibold">Índice Geral de Satisfação por Teste</p>
                            <div class="w-full" id="general-bars">
                                
                            </div>
                        </a>
                    </div>
                    
                    @if($dashboardResults)
                        @foreach ($dashboardResults['organizational-climate'] as $testName => $testData)
                            <div class="shadow-md rounded-md w-full flex flex-col items-center bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                                <a href="" class="w-full p-6 flex flex-col justify-start gap-5 items-center">
                                    <p class="text-center font-semibold">{{ $testName }}</p>
                                    <div class="w-full" id="{{ $testName }}">
                                        
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const testParticipation = @json($testsParticipation);
    const dashboardResults = @json($dashboardResults)    
</script>

<script src="{{ asset('js/dashboard.js') }}"></script>