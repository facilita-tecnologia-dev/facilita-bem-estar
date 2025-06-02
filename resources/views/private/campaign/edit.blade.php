<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Editar campanha de testes"
                :back="route('campaign.show', $campaign)"
                :breadcrumbs="[
                    'Lista de Campanhas' => route('campaign.index'),
                    'Campanha - Detalhe' => route('campaign.show', $campaign),
                    'Editar campanha' => ''
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('campaign.update', $campaign) }}" id="form-create-campaign" put class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input-text name="name" label="Nome da Campanha" value="{{ $campaign->name }}" placeholder="Digite o nome da campanha de testes"/>
                    
                    <x-form.select 
                        name="collection_id" 
                        label="Coleção de testes" 
                        disabled="{{ $campaign->start_date < now() ? true : false }}" 
                        value="{{ old('collection_id', $campaign->collection_id) }}" 
                        :options="$collectionsToSelect" 
                    />
                    
                    <x-form.input-date 
                        name="start_date" 
                        type="datetime-local" 
                        disabled="{{ $campaign->start_date < now() ? true : false }}" 
                        value="{{ old('start_date', $campaign->start_date->format('Y-m-d\TH:i')) }}" 
                        min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" 
                        label="Data de início"
                    />

                    <x-form.input-date 
                        name="end_date"
                        type="datetime-local" 
                        value="{{ old('end_date', $campaign->end_date->format('Y-m-d\TH:i')) }}"
                        disabled="{{ $campaign->end_date < now() ? true : false }}" 
                        min="{{ \Carbon\Carbon::now()->addHours(1)->format('Y-m-d\TH:i') }}" 
                        label="Data de encerramento"
                    />
                
                    <div class="md:col-span-2">
                        <x-form.textarea name="description" label="Descrição" value="{{ old('description', $campaign->description) }}" placeholder="Digite a descrição da campanha de teste" class="bg-gray-100 shadow-none border border-[#FF8AAF] text-base text-gray-800 placeholder:text-gray-500"/>    
                    </div>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action :href="route('campaign.show', $campaign)" variant="secondary">Cancelar</x-action>
                    <x-action tag="button" type="submit" form="form-create-campaign" variant="secondary">Salvar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>