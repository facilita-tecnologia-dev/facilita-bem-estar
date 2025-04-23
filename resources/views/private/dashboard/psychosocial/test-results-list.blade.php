<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">{{ $testName }}</h2>
            </div>

            <div class="w-full flex items-end sm:items-center gap-4 flex-col sm:flex-row">
                <div class="bg-white/25 w-full sm:w-auto flex-1 px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Lista de resultados do teste de {{ trim($testName) }} por colaborador.
                    </p>
                </div>
            </div>

            <div class="w-full flex items-end sm:items-center gap-4 flex-col sm:flex-row justify-end">
                <x-filters-info-bar
                    :countFiltered="true"
                    :filtersApplied="$filtersApplied"
                    :filteredUsers="$filteredUsers"
                />

                <x-filters-trigger
                    class="w-10 h-10"
                    :modalFilters="['name', 'cpf', 'gender', 'department', 'occupation']" 
                />
            </div>

    
            <div class="w-full flex flex-col gap-4 items-end">
                <div class="w-full space-y-2" data-role="tests-list">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                        <span data-role="th" onclick="reorderTable(event, 0)" class="text-base font-semibold text-left col-span-1 lg:col-span-2">Nome</span>
                        <span data-role="th" onclick="reorderTable(event, 1)" class="hidden lg:block text-base font-semibold text-left">Setor</span>
                        <span data-role="th" onclick="reorderTable(event, 2)" class="hidden sm:block text-base font-semibold text-left">Cargo</span>
                        <span data-role="th" onclick="reorderTable(event, 3)" class="text-base font-semibold text-left">Severidade</span>
                    </div>

                    <div class="space-y-2" data-role="tbody">
                        @forelse ($usersList as $user)
                            <a
                                data-role="tr"
                                href="{{ route('user.show', $user['user']->id) }}"
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all
                                    {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::CRITICO->value ? 'to-[#fc6f6f50]' : '' }}
                                    {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::ALTO->value ? 'to-[#febc5350]' : '' }}
                                    {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::MEDIO->value ? 'to-[#faed5d50]' : '' }}
                                    {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::BAIXO->value ? 'to-[#8cf8c050]' : '' }}
                                    {{ $user['severity']['severity_color'] == App\Enums\SeverityEnum::MINIMO->value ? 'to-[#76fc7150]' : '' }}
                                ">
                                <span data-role="td" class="col-span-1 lg:col-span-2 truncate">{{ $user['user']->name }}</span>
                                <span data-role="td" class="hidden lg:block">
                                    @if($user['user']->department)
                                        {{ $user['user']->department }}
                                    @else
                                        <i>(Vazio)</i>
                                    @endif
                                </span>
                                <span data-role="td" class="hidden sm:block">
                                    @if($user['user']->occupation)
                                        {{ $user['user']->occupation }}
                                    @else
                                        <i>(Vazio)</i>
                                    @endif
                                </span>
                                
                                <span data-value="{{ $user['severity']['severity_key'] }}" data-role="td" class="truncate">{{ $user['severity']['severity_title'] }}</span>
                            </a>
                        @empty
                            <p class="w-full text-center mt-6">Não há testes cadastrados.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test-results-list.js') }}"></script>
</x-layouts.app>