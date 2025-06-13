<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Criar campanha de testes" 
                :back="route('campaign.index')"
                :breadcrumbs="[
                    'Lista de Campanhas' => route('campaign.index'),
                    'Criar campanha de testes' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('campaign.store') }}" id="form-create-campaign" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input-text name="name" label="Nome da Campanha" placeholder="Digite o nome da campanha de testes"/>
                    
                    <x-form.select name="collection_id" label="Formulário de Pesquisa" :options="$collectionsToSelect" />
                    
                    <x-form.input-date type="datetime-local" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" name="start_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" label="Data de início"/>
                    <x-form.input-date type="datetime-local" value="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d\TH:i') }}" name="end_date" min="{{ \Carbon\Carbon::now()->addHours(1)->format('Y-m-d\TH:i') }}" label="Data de encerramento"/>
                
                    <div class="md:col-span-2">
                        <x-form.textarea name="description" label="Descrição" placeholder="Digite a descrição da campanha de teste" class="bg-gray-100 shadow-none border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500"/>    
                    </div>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action :href="route('campaign.index')" variant="danger">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        <x-action tag="button" type="submit" form="form-create-campaign" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>