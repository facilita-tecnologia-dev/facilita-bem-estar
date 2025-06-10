<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Editar Medidas de Controle"
                :back="route('company-collections')"
                :breadcrumbs="[
                    'Medidas de Controle de Riscos Psicossociais' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 text-sm sm:text-base space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <p>Adicione ou remova testes ou questões e depois clique em "Salvar" para armazenar suas mudanças.</p>
                    <x-action tag="button" type="button" variant="secondary" onclick="showAddControlActionModal()">
                        Adicionar medida
                    </x-action>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ($mappedControlActions as $riskName => $controlActions)
                        <div class="space-y-2">
                            <header class="bg-gray-200 px-4 py-2 rounded-md shadow-md flex items-start sm:items-center flex-col sm:flex-row justify-between gap-4" data-role="test-header">
                                <h2 class="text-sm sm:text-base font-semibold">{{ $riskName }}</h2>
                            </header>
                            <div data-risk-name="{{ $riskName }}" class="flex flex-col gap-2">
                                @foreach ($controlActions as $controlAction)
                                    @php 
                                        $isDefault = $controlAction instanceof App\Models\ControlAction || $controlAction['control_action_id'];
                                        $isAllowed = $controlAction instanceof App\Models\ControlAction || $controlAction->allowed;
                                    @endphp
                                    <div data-role="control-action-line" class="flex gap-2">
                                        <x-form action="{{ route('control-actions.update') }}" put class="w-full">
                                            <input type="hidden" name="control_action_id" value="{{ $controlAction instanceof App\Models\ControlAction ? 'default_' . $controlAction->id : 'custom_' . $controlAction->id }}">
                                            <input type="hidden" name="control_action_content" value="{{ $controlAction->content }}">
                                            <button type="submit" onclick="this.form.submit()" class="flex items-center gap-4 w-full text-left rounded-md shadow-md px-4 py-2 border border-gray-300  {{ $isAllowed ? 'bg-sky-300/50 opacity-100' : 'bg-gray-100/50 opacity-70' }} cursor-pointer relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                                                <i class="fa-solid fa-circle-check opacity-0 {{ $isAllowed ? 'opacity-100' : '' }} transition-opacity text-gray-800"></i>
                                                <div class="flex-1">
                                                    <p class="font-medium text-xs md:text-sm text-gray-800">{{ $controlAction->content }}</p>
                                                </div>
                                                <span class="text-xs">{{ $isDefault ? 'Padrão' : 'Personalizado'}}</span>
                                            </button>
                                        </x-form>
    
                                        @if(!$isDefault)
                                            <x-form action="{{ route('control-actions.destroy', $controlAction) }}" delete class="contents">
                                                <x-action tag="button" variant="danger">
                                                    <i class="fa-solid fa-trash"></i>
                                                </x-action>
                                            </x-form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <x-modal.background data-role="add-control-action-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Adicionar Medida de Controle</x-modal.title>

                    <x-form action="{{ route('control-actions.store') }}" post class="w-full space-y-2">
                        <x-form.input-text name="control_action" label="Conteúdo" />
                        <x-form.select name="risk" label="Risco" :options="$risksToSelect" />
                        <x-action tag="button" variant="secondary" width="full">Adicionar</x-action>
                    </x-form>
                </x-modal.wrapper>
            </x-modal.background>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/control-actions/update.js') }}"></script>
</x-layouts.app>