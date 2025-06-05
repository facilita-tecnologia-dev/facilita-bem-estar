<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Índices Demográficos" 
                :breadcrumbs="[
                    'Índices Demográficos' => '',
                ]"
            />

            <div class="w-full grid grid-cols-1 gap-4 items-start">
                <div class="bg-gray-100 rounded-md py-2 px-4 text-center shadow-md">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800">Dados de Desempenho Organizacional (%)</h2>
                </div>
                <x-charts.bar-vertical id="company-metrics" title="Dados de Desempenho Organizacional (%)" />
                
                <div class="bg-gray-100 rounded-md py-2 px-4 text-center shadow-md">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800">Índices Demográficos da Empresa</h2>
                </div>
                @if($demographics)
                    @foreach ($demographics as $demographicName => $demographic)
                        <x-charts.bar-vertical :id="$demographicName" :title="$demographicName" />
                    @endforeach
                @else
                    <div class="w-full flex flex-col items-center gap-2">
                        <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                        <p class="text-base text-center">Você ainda não registrou colaboradores, portanto os índices demográficos não podem ser calculados.</p>
                    </div>
                @endif
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const companyMetrics = @json($companyMetrics)    
    const demographics = @json($demographics)    
</script>

<script src="{{ asset('js/dashboard/charts.js') }}"></script>
<script src="{{ asset('js/dashboard/demographics/index.js') }}"></script>