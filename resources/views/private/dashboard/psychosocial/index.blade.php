<x-layouts.app>
    <x-structure.page-container>
        
        <x-structure.sidebar />
        
        <x-structure.main-content-container>        
            <x-structure.page-title 
                title="Riscos Psicossociais" 
                :breadcrumbs="[
                    'Riscos Psicossociais' => ''
                ]"
            />

            @if($psychosocialTestsParticipation)
                <div class="w-full flex flex-col gap-4">
                    <div class="w-full flex flex-col md:flex-row gap-2">
                        <x-structure.message>
                            @if($psychosocialTestsParticipation['Geral']['per_cent'] >= 75)
                                <i class="fa-solid fa-check"></i>
                                A adesão é superior à 75%, portanto os resultados devem ser considerados válidos.
                            @else
                                <i class="fa-solid fa-xmark"></i>
                                A adesão é inferior à 75%, portanto os resultados não devem ser considerados válidos.
                            @endif
                        </x-structure.message>
        
                        <div class="w-full md:w-fit">
                            <x-action href="{{ route('dashboard.psychosocial.risks') }}" width="full">
                                Visualizar Riscos
                            </x-action>
                        </div> 
                    </div>
            
                    
                    <div class="w-full flex flex-col-reverse md:flex-row gap-4 items-start">
                        <div class="flex items-center gap-2 w-full flex-wrap">
                            <x-numbers-of-records :value="$filteredUserCount" />

                            <x-applied-filters
                                :filtersApplied="$filtersApplied"
                            />
                        </div>

                        <x-filter-actions
                            :filtersApplied="$filtersApplied"
                            :modalFilters="[
                                'gender', 
                                'department', 
                                'occupation', 
                                'work_shift', 
                                'marital_status', 
                                'education_level', 
                                'age_range', 
                                'admission_range', 
                                'year'
                            ]" 
                        />
                    </div>
                </div>

                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($psychosocialRiskResults as $testTypeName => $results)
                        <a href="{{ route('dashboard.psychosocial.by-department', $testTypeName)}}" class="w-full px-2 py-6 flex flex-col justify-start gap-5 items-center shadow-md rounded-md bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                            <p class="text-center font-semibold truncate">{{ $testTypeName }}</p>
                            <x-charts.doughnut :id="$testTypeName"/>
                            
                            @if(isset($results['risks']))
                                <div class="w-full space-y-2 px-4">
                                    @foreach ($results['risks'] as $riskName => $risk)
                                        <x-charts.risk-bar :riskName="$riskName" :risk="$risk" />
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
                
                @if(!$filtersApplied)
                    <x-charts.bar-vertical id="psychosocial-participation" title="Participação no teste de Riscos Psicossociais" />
                @endif

            @else
                @if($companyHasTests)
                    <div class="w-full flex flex-col items-center gap-2">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Nenhum resultado encontrado, tente novamente.</p>
                    </div>
                @else
                    <div class="w-full flex flex-col items-center justify-center gap-2 flex-1">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Você ainda não realizou testes de Riscos Psicossociais.</p>
                    </div>
                @endif
            @endif
            
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const psychosocialTestsParticipation = @json($psychosocialTestsParticipation);
    const psychosocialRiskResults = @json($psychosocialRiskResults)   
</script>

<script src="{{ asset('js/dashboard/charts.js') }}"></script>
<script src="{{ asset('js/dashboard/psychosocial/index.js') }}"></script>