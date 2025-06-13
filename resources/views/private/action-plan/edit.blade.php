<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
            
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Planos de Ação - {{ $actionPlan->name }}"
                :back="route('action-plan.show', $actionPlan)"
                :breadcrumbs="[
                    'Plano de Ação' => route('action-plan.show', $actionPlan),
                    $risk->name . ' - Editar' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <x-form action="{{ route('action-plan.risk.update', ['actionPlan' => $actionPlan, 'risk' => $risk]) }}" put class="w-full space-y-2">
                <div class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-semibold flex-1">{{ $risk->name }}</h2>
                    
                    <x-action variant="secondary" tag="button" type="button" onclick="showAddControlActionModal()">Adicionar medida</x-action>
                    <x-action variant="secondary" tag="button">Salvar alterações</x-action>
                </div>
             
                <x-table>
                    <x-table.head class="flex items-center justify-between mt-4 mb-1 bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md
                        {{ $identifiedRisk['riskLevel'] == 1 ? 'to-[#76fc7150]' : '' }}
                        {{ $identifiedRisk['riskLevel'] == 2 ? 'to-[#faed5d50]' : '' }}
                        {{ $identifiedRisk['riskLevel'] == 3 ? 'to-[#dc933250]' : '' }}
                        {{ $identifiedRisk['riskLevel'] == 4 ? 'to-[#fc6f6f50]' : '' }}"
                    >
                        <x-table.head.th class="flex-1">
                            {{ App\Enums\RiskSeverityEnum::labelFromValue($identifiedRisk['riskLevel']) }}
                        </x-table.head.th>
                        <x-table.head.th class="w-40">
                            Prazo
                        </x-table.head.th>
                        <x-table.head.th class="w-40">
                            Responsável
                        </x-table.head.th>
                        <x-table.head.th class="w-40">
                            Situação
                        </x-table.head.th>
                        <x-table.head.th class="w-12"></x-table.head.th>
                    </x-table.head>
                    <x-table.body>
                        @foreach ($identifiedRisk['controlActions'] as $controlAction)
                            <x-table.body.tr class="flex items-center gap-4">
                                <x-table.body.td class="truncate flex-1" title="{{ $controlAction->content }}">
                                    <input 
                                        type="text" 
                                        name="risk[{{ $risk->id }}][control_actions][{{ $controlAction->id }}][content]" 
                                        value="{{ $controlAction->content }}"
                                        class="w-full bg-gray-100 border border-gray-300 focus:outline-gray-400 py-1 px-2 rounded-md"
                                    >
                                </x-table.body.td>
                                <x-table.body.td class="w-40" title="{{ $controlAction->deadline ?? 'Indefinido' }}">
                                    <input 
                                        type="text" 
                                        name="risk[{{ $risk->id }}][control_actions][{{ $controlAction->id }}][deadline]" 
                                        value="{{ $controlAction->deadline ?? 'Indefinido' }}"
                                        class="w-full bg-gray-100 border border-gray-300 focus:outline-gray-400 py-1 px-2 rounded-md"
                                    >
                                </x-table.body.td>
                                <x-table.body.td class="w-40" title="{{ $controlAction->assignee ?? 'Indefinido' }}">
                                    <input 
                                        type="text" 
                                        name="risk[{{ $risk->id }}][control_actions][{{ $controlAction->id }}][assignee]" 
                                        value="{{ $controlAction->assignee ?? 'Indefinido' }}"
                                        class="w-full bg-gray-100 border border-gray-300 focus:outline-gray-400 py-1 px-2 rounded-md"
                                    >
                                </x-table.body.td>
                                <x-table.body.td class="w-40" title="{{ $controlAction->status ?? 'Indefinido' }}">
                                    <input 
                                        type="text" 
                                        name="risk[{{ $risk->id }}][control_actions][{{ $controlAction->id }}][status]" 
                                        value="{{ $controlAction->status ?? 'Indefinido' }}"
                                        class="w-full bg-gray-100 border border-gray-300 focus:outline-gray-400 py-1 px-2 rounded-md"
                                    >
                                </x-table.body.td>
                                <x-table.body.td class="w-12">
                                    <x-action href="{{ route('action-plan.risk.control-action.destroy', ['actionPlan' => $actionPlan, 'risk' => $risk, 'controlAction' => $controlAction]) }}" data-tippy-content="Excluir medida de controle" variant="danger">
                                        <i class="fa-solid fa-trash pointer-events-none"></i>    
                                    </x-action>
                                </x-table.body.td>
                            </x-table.body.tr>
                        @endforeach
                    </x-table.body>
                </x-table>
            </x-form>
                
            <x-modal.background data-role="add-control-action-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Adicionar Medida de Controle</x-modal.title>

                    <x-form action="{{ route('action-plan.risk.control-action.store', ['actionPlan' => $actionPlan, 'risk' => $risk]) }}" post class="w-full space-y-2">
                        <input type="hidden" name="risk_id" value="{{ $risk->id }}">
                        <x-form.input-text name="control_action" label="Conteúdo" placeholder="Digite a medida de controle" />
                        <x-form.input-text name="deadline" label="Prazo" placeholder="Digite o prazo" />
                        <x-form.input-text name="assignee" label="Responsável" placeholder="Digite o responsável" />
                        <x-form.input-text name="status" label="Situação" placeholder="Digite a situação" />
                        <input type="hidden" name="severity" value="{{ $identifiedRisk['riskLevel'] }}">
                        <x-action tag="button" variant="secondary" width="full">Adicionar</x-action>
                    </x-form>
                </x-modal.wrapper>
            </x-modal.background>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/control-actions/update.js') }}"></script>
</x-layouts.app>