<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title 
                :title="$campaign->name"
                :back="route('campaign.index')"
                :breadcrumbs="[
                    'Lista de Campanhas' => route('campaign.index'),
                    'Campanha - Detalhe' => '',
                ]"
            />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Nome:</p>
                        <p class="text-sm sm:text-base text-left">{{ $campaign->name }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Tipo:</p>
                        <p class="text-sm sm:text-base text-left">{{ $campaign->collection->name }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Data de início:</p>
                        <p class="text-sm sm:text-base text-left">{{ $campaign->start_date->format('d/m/Y - H:i') }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Data de encerramento:</p>
                        <p class="text-sm sm:text-base text-left">{{ $campaign->end_date->format('d/m/Y - H:i') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="font-semibold text-base sm:text-lg text-left">Descrição:</p>
                        <p class="text-sm sm:text-base text-left">{{ $campaign->description }}</p>
                    </div>
                </div>

                <div class="w-full flex flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        @can('campaign-delete')
                            @if(session('company')->hasCampaignThisYear($campaign->collection->id))
                                <x-form action="{{ route('campaign.destroy', $campaign) }}" delete onsubmit="return confirm('Você deseja excluir a campanha?')">
                                    <x-action tag="button" type="submit" variant="danger">Excluir campanha</x-action>
                                </x-form>
                            @endif
                        @endcan
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        @can('campaign-edit')
                            <x-action href="{{ route('campaign.edit', $campaign) }}" variant="secondary">Editar</x-action>
                        @endcan
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>    
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>