<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Índices Demográficos" />
            
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4 ">
                <x-charts.bar-vertical id="company-metrics" title="Indicadores da empresa (%)" class="md:col-span-2" />

                @foreach ($demographics as $demographicName => $demographic)
                    <x-charts.bar-vertical :id="$demographicName" :title="$demographicName" />
                @endforeach
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