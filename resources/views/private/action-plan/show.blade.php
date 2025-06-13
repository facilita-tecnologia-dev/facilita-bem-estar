<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Plano de Ação"
                :breadcrumbs="[
                    'Plano de Ação' => '',
                ]"
            />

            <div class="w-full flex flex-col md:flex-row gap-4 justify-end">
                @if(session('message'))
                    <x-structure.message>
                        <i class="fa-solid fa-circle-info"></i>
                        {{ session('message') }}
                    </x-structure.message>
                @endif

                <x-action href="{{ route('dashboard.psychosocial.risks.pdf') }}">
                    Visualizar Inventário de Riscos
                </x-action>
            </div>
            
            
            {{-- @foreach ($riskResults as $test)
                @foreach ($test['risks'] as $riskName => $risk)
                    <div class="w-full bg-gray-100 rounded-md shadow-md p-4 text-sm sm:text-base space-y-4">
                        <div class="flex justify-between gap-4 items-center shadow-md rounded-md bg-gray-200 px-4 py-2">
                            <h2 class="font-semibold">{{ $riskName }}</h2>
                            <a href="{{ route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $riskName]) }}" class="text-sm underline">Editar</a>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-1.5 w-full rounded-md shadow-md
                                {{ $risk['riskLevel'] == 1 ? 'to-[#76fc7150]' : '' }}
                                {{ $risk['riskLevel'] == 2 ? 'to-[#faed5d50]' : '' }}
                                {{ $risk['riskLevel'] == 3 ? 'to-[#dc933250]' : '' }}
                                {{ $risk['riskLevel'] == 4 ? 'to-[#fc6f6f50]' : '' }}">
                                <p class="text-sm">{{ App\Enums\RiskSeverityEnum::labelFromValue($risk['riskLevel']) }}</p>
                            </div>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 list-disc gap-x-8 gap-y-3 px-8">
                                @foreach ($risk['controlActions'] as $controlAction)
                                    <li class="text-sm">{{ $controlAction->content }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endforeach --}}
            @foreach ($riskResults as $test)
                @foreach ($test['risks'] as $riskName => $risk)
                    <div class="w-full p-4 bg-gray-100 shadow-md rounded-md">
                        <div class="flex gap-2 items-end">
                            <div class="w-full flex flex-col gap-1 px-2">
                                <span>Nome do risco: </span>
                                <p class="text-lg font-medium">
                                    {{ $riskName }}
                                </p>
                            </div>
                            <x-action href="{{ route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $riskName]) }}" variant="secondary">
                                Editar
                            </x-action>
                        </div>

                        <x-table>
                            <x-table.head data-tippy-content="O sistema apresenta apenas o nível de risco correspondente ao resultado do teste" class="flex items-center justify-between mt-4 mb-1 bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md
                                {{ $risk['riskLevel'] == 1 ? 'to-[#76fc7150]' : '' }}
                                {{ $risk['riskLevel'] == 2 ? 'to-[#faed5d50]' : '' }}
                                {{ $risk['riskLevel'] == 3 ? 'to-[#dc933250]' : '' }}
                                {{ $risk['riskLevel'] == 4 ? 'to-[#fc6f6f50]' : '' }}"
                            >
                                <x-table.head.th class="flex-1">
                                    {{ App\Enums\RiskSeverityEnum::labelFromValue($risk['riskLevel']) }}
                                </x-table.head.th>
                                <x-table.head.th class="w-56">
                                    Prazo
                                </x-table.head.th>
                                <x-table.head.th class="w-56">
                                    Responsável
                                </x-table.head.th>
                                <x-table.head.th class="w-56">
                                    Situação
                                </x-table.head.th>
                            </x-table.head>
                            <x-table.body>
                                @foreach ($risk['controlActions'] as $controlAction)
                                    <x-table.body.tr class="flex items-center gap-4">
                                        <x-table.body.td class="truncate flex-1" title="{{ $controlAction->content }}">
                                            {{ $controlAction->content }}
                                        </x-table.body.td>
                                        <x-table.body.td class="w-56" title="{{ $controlAction->deadline ?? 'Indefinido' }}">
                                            {{ $controlAction->deadline ?? 'Indefinido' }}
                                        </x-table.body.td>
                                        <x-table.body.td class="w-56" title="{{ $controlAction->assignee ?? 'Indefinido' }}">
                                            {{ $controlAction->assignee ?? 'Indefinido' }}
                                        </x-table.body.td>
                                        <x-table.body.td class="w-56" title="{{ $controlAction->status ?? 'Indefinido' }}">
                                            {{ $controlAction->status ?? 'Indefinido' }}
                                        </x-table.body.td>
                                    </x-table.body.tr>
                                @endforeach
                            </x-table.body>
                        </x-table>
                    </div>
                @endforeach
            @endforeach

        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/control-actions/update.js') }}"></script>
</x-layouts.app>