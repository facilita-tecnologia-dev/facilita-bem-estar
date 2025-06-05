<x-layouts.app>
    <x-structure.page-container>
        
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Clima Organizacional" 
                :breadcrumbs="[
                    'Clima Organizacional' => '',
                ]"
            />

            @if($organizationalClimateResults)
                <div class="w-full flex flex-row gap-4">
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
                    <div class="w-fit">
                        <x-form action="{!! route('dashboard.organizational-climate.report', request()->query()) !!}" post>
                            <input type="hidden" name="Geral-to-base-64">
                            @foreach ($organizationalClimateResults['main'] as $testName => $testData)
                                <input type="hidden" name="{{ $testName }}-to-base-64">
                            @endforeach
                            <x-action tag="button" id="create-report-button" disabled style="opacity: 50%">Visualizar relatório</x-action>
                        </x-form>
                    </div>
                </div>

                <div class="w-full grid grid-cols-1 gap-4 ">
                    <x-charts.bar-vertical tag="a" :href="route('dashboard.organizational-climate.by-answers')" id="Geral" title="Índice Geral de Satisfação por Teste" class="" />
                
                    @foreach ($organizationalClimateResults['main'] as $testName => $testData)
                        <x-charts.bar-vertical tag="a" :href="route('dashboard.organizational-climate.by-answers', ['test' => $testName])" :id="$testName" :title="$testName" />
                    @endforeach
                </div>

                @if(!$filtersApplied)
                    <x-charts.bar-vertical id="organizational-participation" title="Participação no teste de Clima Organizacional" />
                @endif
            @else
                @if($companyHasTests)
                    <div class="w-full flex flex-col items-center gap-2">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Nenhum resultado encontrado, tente novamente.</p>
                    </div>
                @else
                    <div class="w-full flex flex-col items-center gap-2">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Você ainda não realizou nenhuma Pesquisa de Clima Organizacional.</p>
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
    const organizationalTestsParticipation = @json($organizationalTestsParticipation);
    const organizationalClimateResults = @json($organizationalClimateResults);
    const filtersApplied = @json($filtersApplied);
</script>

<script src="{{ asset('js/dashboard/charts.js') }}"></script>
<script src="{{ asset('js/dashboard/organizational/index.js') }}"></script>