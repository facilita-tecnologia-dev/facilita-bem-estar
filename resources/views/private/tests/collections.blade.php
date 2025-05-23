<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Coleções de Testes" />

            @if($companyActiveCampaigns->count())
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Existem campanhas ativas no momento. Por isso, não é possível editar coleções associadas a essas campanhas.
                    </p>
                </div>
            @endif

            <x-table>
                <x-table.head class="flex items-center gap-4">
                    <x-table.head.th class="flex-1">
                        Nome da Coleção
                    </x-table.head.th>
                    <x-table.head.th class="flex-1">
                        Qtd. Testes
                    </x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($collections as $collection)
                        <x-table.body.tr 
                            tag="a" 
                            href="{{ route('company-collections.update', $collection) }}" 
                            class="flex items-center gap-4 {{ session('company')->hasSameCampaignThisYear($collection->id) || $collection->id == 1 ? 'pointer-events-none opacity-50' : '' }}" 
                        >
                            <x-table.body.td class="truncate flex-1" title="{{ $collection->name }}">{{ $collection->name }}</x-table.body.td>
                            <x-table.body.td class="flex-1">{{ $collection->tests_count }}</x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>