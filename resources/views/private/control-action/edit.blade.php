<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Medida de Controle - Editar"
                :back="route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $risk->name])"
                :breadcrumbs="[
                    'Plano de Ação' => route('action-plan.show', $actionPlan),
                    $risk->name . ' - Editar' => route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $risk->name]),
                    'Editar' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif
            
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('action-plan.risk.control-action.update', ['actionPlan' => $actionPlan, 'risk' => $risk, 'controlAction' => $controlAction]) }}" id="form-update-control-action" put class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input-text name="control_action" label="Conteúdo" placeholder="Digite a medida de controle" value="{{ old('control_action', $controlAction->content) }}"/>
                    <x-form.input-text name="deadline" label="Prazo" placeholder="Digite o prazo" value="{{ old('deadline', $controlAction->deadline) }}"/>
                    <x-form.input-text name="assignee" label="Responsável" placeholder="Digite o responsável" value="{{ old('assignee', $controlAction->assignee) }}"/>
                    <x-form.input-text name="status" label="Situação" placeholder="Digite a situação" value="{{ old('status', $controlAction->status) }}"/>
                </x-form>

                <div class="w-full flex flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action href="{{ route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $risk->name]) }}" variant="danger">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        <x-action tag="button" type="submit" form="form-update-control-action" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
            
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/control-actions/update.js') }}"></script>
</x-layouts.app>