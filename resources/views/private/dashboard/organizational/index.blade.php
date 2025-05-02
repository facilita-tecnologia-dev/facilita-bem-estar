<x-layouts.app>
    <x-structure.page-container>
        
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Clima Organizacional" />

            
            @if($organizationalClimateResults)
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
            
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 ">
                    <x-charts.bar-vertical id="general-bars" title="Índice Geral de Satisfação por Teste" class="md:col-span-2" />
                
                    @foreach ($organizationalClimateResults as $testName => $testData)
                        <x-charts.bar-horizontal tag="a" :href="route('dashboard.organizational-climate.by-answers', ['test' => $testName])" :id="$testName" :title="$testName" />
                    @endforeach
                </div>

                @if(!$filtersApplied)
                    <x-charts.bar-vertical id="organizational-participation" title="Participação no teste de Clima Organizacional" />
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
            @else
                 <div class="w-full flex flex-col items-center gap-2">
                    <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                    <p class="text-base text-center">Nenhum resultado encontrado, tente novamente.</p>
                </div>
            @endif
        
        </x-structure.main-content-container>
    </x-structure.page-container>

</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const organizationalTestsParticipation = @json($organizationalTestsParticipation);
    const organizationalClimateResults = @json($organizationalClimateResults)    
</script>

<script src="{{ asset('js/dashboard/charts.js') }}"></script>
<script src="{{ asset('js/dashboard/organizational/index.js') }}"></script>