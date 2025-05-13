<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Lista de Campanhas" />

            @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif
            
            @can('campaign-create')
                <div class="w-full flex justify-end flex-col-reverse md:flex-row items-start gap-2">
                    <div class="w-full md:w-fit flex gap-2">
                        <div class="w-full md:w-fit">
                            <x-action href="{{ route('campaign.create') }}" width="full">
                                <i class="fa-solid fa-plus text-sm sm:text-base"></i>
                            </x-action>
                        </div>
                    </div>
                </div>
            @endcan

            @if($companyCampaigns)
                <x-table class="flex flex-col gap-1">
                    <x-table.head class="flex items-center gap-3">
                        <x-table.head.sortable-th class="flex-1" field="name">
                            Nome
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden sm:block flex-1" field="collection_id">
                            Tipo
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden lg:block flex-1" field="start_date">
                            Data de Início
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden lg:block flex-1" field="end_date">
                            Data de Encerramento
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="w-24" field="end_date">
                            Status
                        </x-table.head.sortable-th>
                    </x-table.head>
                    <x-table.body>
                        @foreach ($companyCampaigns as $campaign)
                            <x-table.body.tr tag="a" href="{{ route('campaign.show', $campaign) }}" class="flex items-center gap-3" >
                                <x-table.body.td class="truncate flex-1">{{ $campaign->name }}</x-table.body.td>
                                <x-table.body.td class="hidden sm:block truncate flex-1">{{ $campaign->collection->name }}</x-table.body.td>
                                <x-table.body.td class="hidden lg:block truncate flex-1">{{ $campaign->start_date->format('d/m/Y - H:i') }}</x-table.body.td>
                                <x-table.body.td class="hidden lg:block truncate flex-1">{{ $campaign->end_date->format('d/m/Y - H:i') }}</x-table.body.td>
                                <x-table.body.td class="truncate w-24">{{ now() >= $campaign->start_date && now() <= $campaign->end_date ? 'Ativo' : 'Não ativo' }}</x-table.body.td>
                            </x-table.body.tr>
                        @endforeach
                    </x-table.body>
                </x-table>

                {{ $companyCampaigns->links() }}
            @else
                <div class="w-full flex flex-col items-center gap-2">
                    <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                    <p class="text-base text-center">Você ainda não registrou colaboradores.</p>
                </div>         
            @endif
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>