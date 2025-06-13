<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Formulários de Pesquisa"
                :back="route('custom-collections.index')"
                :breadcrumbs="[
                    'Formulários de Pesquisa' => '',
                ]"
            />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('custom-collections.store') }}" id="form-create-campaign" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input-text name="name" label="Nome da pesquisa" placeholder="Digite o nome da pesquisa de testes"/>
                    
                    <div class="flex gap-2 items-end">
                        <x-form.input-text name="collection_type" label="Tipo de pesquisa" value="Pesquisa de Clima Organizacional" readonly/>
                        <x-action 
                        variant="secondary" 
                        tag="button"
                        type="button"
                        data-tippy-content="Apenas formulários de Pesquisa de Clima Organizacional são customizáveis."
                        >
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                    
                    <div class="md:col-span-2">
                        <x-form.textarea name="description" label="Descrição" placeholder="Digite a descrição da coleção de testes" class="bg-gray-100 shadow-none border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500"/>    
                    </div>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action :href="route('custom-collections.index')" variant="danger">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        <x-action tag="button" type="submit" form="form-create-campaign" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>