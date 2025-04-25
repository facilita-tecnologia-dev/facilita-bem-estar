<x-layouts.app>
    <x-structure.page-container>
        
        <x-structure.sidebar />
        
        <x-structure.main-content-container>        
            <x-structure.page-title title="Riscos Psicossociais" />

            <div class="w-full flex flex-col md:flex-row gap-2">
                <div class="bg-gray-100/60 px-6 py-2 rounded-md shadow-md flex-1">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3 truncate">
                        @if($psychosocialTestsParticipation['Geral']['Participação'] >= 75)
                            <i class="fa-solid fa-check text-lg"></i>
                            A adesão é superior à 75%, portanto os resultados devem ser considerados válidos.
                        @else
                            <i class="fa-solid fa-xmark text-lg"></i>
                            A adesão é inferior à 75%, portanto os resultados não devem ser considerados válidos.
                        @endif
                    </p>
                </div>
                
                <x-action href="{{ route('dashboard.psychosocial.risks') }}">
                    Visualizar Riscos
                </x-action>
                
                <x-filters-trigger
                    :filtersApplied="$filtersApplied"
                    :modalFilters="[
                        'name', 
                        'cpf', 
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

            <x-filters-info-bar
                :countFiltered="true"
                :filtersApplied="$filtersApplied"
                :filteredUsers="$filteredUsers"
            />

            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @if($psychosocialRiskResults)
                    @foreach ($psychosocialRiskResults as $testName => $testData)
                        <a href="{{ route('dashboard.psychosocial.by-department', $testName)}}" class="w-full px-2 py-6 flex flex-col justify-start gap-5 items-center shadow-md rounded-md bg-gray-100/60 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                            <p class="text-center font-semibold truncate">{{ $testName }}</p>
                            <x-charts.doughnut :id="$testName"/>
                            
                            @if(isset($testData['risks']))
                                <div class="w-full space-y-2 px-4">
                                    @foreach ($testData['risks'] as $riskName => $risk)
                                        <x-charts.risk-bar :riskName="$riskName" :risk="$risk" />
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
            
            @if(!$filtersApplied)
                <x-charts.bar-vertical id="psychosocial-participation" title="Participação no teste de Riscos Psicossociais" />
            @else
                @if($filteredUsers)
                    <div class="w-full space-y-2">
                        <div class="flex gap-2 bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                            <span class="text-base font-semibold text-left">Usuários filtrados</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($filteredUsers as $user)
                                <a
                                    href="{{ route('user.show', $user) }}"
                                    class="px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] to-[#FFFFFF60] relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                                    <span data-role="td" class="w-full truncate">{{ $user->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full flex flex-col items-center gap-2">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Nenhum resultado encontrado, tente novamente.</p>
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