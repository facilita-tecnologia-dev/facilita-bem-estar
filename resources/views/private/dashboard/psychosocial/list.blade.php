<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>   
            <x-structure.page-title :title="$testName" />

            <div class="w-full flex items-end sm:items-center gap-4 flex-col sm:flex-row">
                <div class="bg-white/25 w-full sm:w-auto flex-1 px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Lista de resultados do teste de {{ trim($testName) }} por colaborador.
                    </p>
                </div>

                <x-filters-trigger
                    class="w-10 h-10"
                    :modalFilters="['name', 'cpf', 'gender', 'department', 'occupation']" 
                />
            </div>

            <x-filters-info-bar
                :countFiltered="true"
                :filtersApplied="$filtersApplied"
                :filteredUsers="$filteredUsers"
            />

            <x-table>
                <x-table.head class="flex items-center gap-3">
                    <x-table.head.th onclick="reorderTable(event, 0)" class="flex-1">Nome</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 1)" class="hidden lg:block flex-1">Setor</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 2)" class="hidden sm:block flex-1">Cargo</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 3)" class="w-40">Severidade</x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @forelse ($usersList as $user)
                        <x-table.body.tr tag="a" href="{{ route('user.show', $user['user']) }}" class="
                                flex items-center gap-3
                                {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::CRITICO->value ? 'to-[#fc6f6f50]' : '' }}
                                {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::ALTO->value ? 'to-[#febc5350]' : '' }}
                                {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::MEDIO->value ? 'to-[#faed5d50]' : '' }}
                                {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::BAIXO->value ? 'to-[#8cf8c050]' : '' }}
                                {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::MINIMO->value ? 'to-[#76fc7150]' : '' }}
                            ">
                            <x-table.body.td class="truncate flex-1" title="{{ $user['user']->name }}">{{ $user['user']->name }}</x-table.body.td>
                            <x-table.body.td class="hidden lg:block flex-1" title="{{ $user['user']->department ?? '(Vazio)'}}">{{ $user['user']->department ?? '(Vazio)'}}</x-table.body.td>
                            <x-table.body.td class="hidden sm:block flex-1" title="{{ $user['user']->occupation ?? '(Vazio)'}}">{{ $user['user']->occupation ?? '(Vazio)'}}</x-table.body.td>
                            <x-table.body.td class="truncate w-40" data-value="{{ $user['severity']['severity_key'] }}">{{ $user['severity']['severity_title'] }}</x-table.body.td>
                        </x-table.body.tr>
                    @empty
                        <p class="w-full text-center mt-6">Não há testes cadastrados.</p>
                    @endforelse
                </x-table.body>
            </x-table>
    
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test-results-list.js') }}"></script>
</x-layouts.app>