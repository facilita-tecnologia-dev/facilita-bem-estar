<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Formulários de Pesquisa" 
                :breadcrumbs="[
                    'Formulários de Pesquisa' => '',
                ]"
            />

            @if(session('company')->getActiveCampaigns()->isNotEmpty())
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    Existem campanhas de teste em andamento, por isso não é possível editar ou excluir coleções relacionadas a elas neste momento.
                </x-structure.message>
            @endif

            <div class="w-full flex flex-col md:flex-row gap-4 justify-end">
                <x-action href="{{ route('custom-collections.create') }}">
                    Criar Pesquisa
                </x-action>
            </div>

            <x-table>
                <x-table.head class="flex items-center gap-4">
                    <x-table.head.th class="flex-1">
                        Nome da pesquisa
                    </x-table.head.th>
                    <x-table.head.th class="flex-1">
                        Qtd. Testes
                    </x-table.head.th>
                    <x-table.head.th class="w-10"></x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($customCollections as $collection)
                        <x-table.body.tr tag="a" href="{{ route('custom-collections.show', ['customCollection' => $collection]) }}" class="flex items-center gap-4 {{ $collection['is_default'] || session('company')->getActiveCampaigns()->where('collection_id', $collection->id)->isNotEmpty() ? 'pointer-events-none' : '' }}">
                            <x-table.body.td class="truncate flex-1" title="{{ $collection['name'] }}">
                                {{ $collection['name'] }}
                                @if($collection['is_default'])
                                    <span class="text-xs bg-sky-300 rounded-md p-1 ml-3">Padrão</span>
                                @endif
                                @if(session('company')->getActiveCampaigns()->where('collection_id', $collection->id)->isNotEmpty())
                                    <span class="text-xs bg-green-300 rounded-md p-1 ml-3">Campanha ativa</span>
                                @endif
                            </x-table.body.td>
                            <x-table.body.td class="flex-1">
                                {{ $collection['tests_count'] }}
                            </x-table.body.td>
                            <x-table.body.td class="w-10">
                                @if(!$collection['is_default'] && !(session('company')->getActiveCampaigns()->where('collection_id', $collection->id)->isNotEmpty()))
                                    <x-form action="{{ route('custom-collections.destroy', ['customCollection' => $collection]) }}" delete>
                                        <x-action tag="button" variant="danger" data-tippy-content="Excluir coleção">
                                            <i class="fa-solid fa-trash pointer-events-none"></i>    
                                        </x-action>    
                                    </x-form>
                                @endif
                            </x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>