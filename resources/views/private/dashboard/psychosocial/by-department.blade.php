<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                :title="$riskName" 
                :back="route('dashboard.psychosocial')"
                :breadcrumbs="[
                    'Riscos Psicossociais' => route('dashboard.psychosocial'),
                    'Divisão por departamento' => ''
                ]"
            />

            <x-structure.message>
                <i class="fa-solid fa-circle-info"></i>
                Resultados do risco de {{ trim($riskName) }} divididos por setor.
            </x-structure.message>
            
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($resultsPerDepartment as $departmentName => $departmentData)
                    <a href="{{ route('dashboard.psychosocial.list', ['testName' => $testName, 'riskName' => $riskName])}}" class="bg-white/25 rounded-md px-4 py-5 shadow-md">
                        <x-table>
                            <x-table.head class="flex items-center justify-between">
                                <x-table.head.th class="truncate">{{ $departmentName }}</x-table.head.th>
                            </x-table.head>
                            <x-table.body>
                                @foreach ($departmentData['severities'] as $severityName => $severityData)
                                    <x-table.body.tr class="
                                        flex items-center justify-between gap-4
                                        {{ $severityData['severity_key'] == App\Enums\RiskSeverityEnum::CRITICA->value ? 'to-[#F26C6C75]' : '' }}
                                        {{ $severityData['severity_key'] == App\Enums\RiskSeverityEnum::ALTA->value ? 'to-[#F6B26B75]' : '' }}
                                        {{ $severityData['severity_key'] == App\Enums\RiskSeverityEnum::MODERADA->value ? 'to-[#DDE26F75]' : '' }}
                                        {{ $severityData['severity_key'] == App\Enums\RiskSeverityEnum::BAIXA->value ? 'to-[#A8E6CFCC]' : '' }}
                                    ">
                                        <x-table.body.td class="truncate">{{ $severityData['severity_name'] }}</x-table.body.td>
                                        <x-table.body.td>{{ round($severityData['count'] / $departmentData['total'] * 100) }}%</x-table.body.td>
                                    </x-table.body.tr>
                                @endforeach
                            </x-table.body>
                        </x-table>
                    </a>
                @endforeach
            </div>

        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>