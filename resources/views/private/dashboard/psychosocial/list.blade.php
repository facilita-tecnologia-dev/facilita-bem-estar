<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>   
            <x-structure.page-title 
                :title="$riskName" 
                :back="route('dashboard.psychosocial.by-department', ['testName' => $testName, 'riskName' => $riskName])"
                :breadcrumbs="[
                    'Riscos Psicossociais' => route('dashboard.psychosocial'),
                    'Divisão por departamento' => route('dashboard.psychosocial.by-department', ['testName' => $testName, 'riskName' => $riskName]),
                    'Lista de Resultados' => ''
                ]"
            />

            <div class="w-full flex flex-col gap-4">
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    Lista de resultados do risco de {{ trim($riskName) }} por colaborador.
                </x-structure.message>
                
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
                        ]" 
                    />
                </div>
            </div>

            <x-table>
                <x-table.head class="flex items-center gap-3">
                    <x-table.head.sortable-th class="hidden lg:block flex-1" field="department" :queryParam="['testName' => $testName, 'riskName' => $riskName]">
                        Setor
                    </x-table.head.sortable-th>
                    <x-table.head.sortable-th class="hidden sm:block flex-1" field="occupation" :queryParam="['testName' => $testName, 'riskName' => $riskName]">
                        Função
                    </x-table.head.sortable-th>
                    <x-table.head.th class="w-40">Severidade</x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @forelse ($usersList as $user)
                        <x-table.body.tr class="
                                flex items-center gap-3
                                {{ $user['severity']['severity_key'] == App\Enums\RiskSeverityEnum::CRITICA->value ? 'to-[#F26C6C75]' : '' }}
                                {{ $user['severity']['severity_key'] == App\Enums\RiskSeverityEnum::ALTA->value ? 'to-[#F6B26B75]' : '' }}
                                {{ $user['severity']['severity_key'] == App\Enums\RiskSeverityEnum::MODERADA->value ? 'to-[#DDE26F75]' : '' }}
                                {{ $user['severity']['severity_key'] == App\Enums\RiskSeverityEnum::BAIXA->value ? 'to-[#A8E6CFCC]' : '' }}
                            ">
                            <x-table.body.td class="hidden lg:block flex-1" title="{{ $user['user']->department ?? '(Vazio)'}}">{{ $user['user']->department ?? '(Vazio)'}}</x-table.body.td>
                            <x-table.body.td class="hidden sm:block flex-1" title="{{ $user['user']->occupation ?? '(Vazio)'}}">{{ $user['user']->occupation ?? '(Vazio)'}}</x-table.body.td>
                            <x-table.body.td class="truncate w-40" data-value="{{ $user['severity']['severity_key'] }}">{{ $user['severity']['severity_name'] }}</x-table.body.td>
                        </x-table.body.tr>
                    @empty
                        <p class="w-full text-center mt-6">Não há testes cadastrados.</p>
                    @endforelse
                </x-table.body>
            </x-table>
            
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test-results-list.js') }}"></script>
</x-layouts.app>