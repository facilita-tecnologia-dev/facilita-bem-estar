<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Planos de Ação"
                :breadcrumbs="[
                    'Planos de Ação' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <x-table>
                <x-table.head class="flex items-center gap-4">
                    <x-table.head.th class="flex-1">
                        Nome
                    </x-table.head.th>
                    <x-table.head.th class="flex-1">
                        Ano
                    </x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($actionPlans as $actionPlan)
                        <x-table.body.tr tag="a" href="{{ route('action-plan.show', $actionPlan) }}" class="flex items-center gap-4">
                            <x-table.body.td class="truncate flex-1" title="{{ $actionPlan->name }}">{{ $actionPlan->name }}</x-table.body.td>
                            <x-table.body.td class="flex-1">{{ $actionPlan->created_at->year }}</x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/control-actions/update.js') }}"></script>
</x-layouts.app>