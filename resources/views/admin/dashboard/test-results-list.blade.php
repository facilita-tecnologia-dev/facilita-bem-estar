<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
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
                @if($queryStringName || $queryStringDepartment || $queryStringOccupation)
                    <div class="flex items-center gap-2">
                        <span class="text-sm">Você filtrou por:</span>
                        @if($queryStringName)
                            <span class="px-3 py-1 rounded-md bg-gray-100/50 text-sm">Nome: {{ $queryStringName }}</span>
                        @endif
                        @if($queryStringDepartment)
                            <span class="px-3 py-1 rounded-md bg-gray-100/50 text-sm">Setor: {{ $queryStringDepartment }}</span>
                        @endif
                        @if($queryStringOccupation)
                            <span class="px-3 py-1 rounded-md bg-gray-100/50 text-sm">Cargo: {{ $queryStringOccupation }}</span>
                        @endif
                    </div>
                @endif

                <button data-role="filter-modal-trigger" class="bg-gray-100 w-10 h-10 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

            <div data-role="filter-modal" class="hidden z-50 left-0 top-0 fixed w-screen h-screen bg-gray-800/30 px-4 items-center justify-center">
                <div class="w-full max-w-[360px] bg-gray-100 py-8 px-4 rounded-md flex flex-col items-center gap-8">
                    <h2 class="text-center text-3xl font-semibold text-gray-800">Filtros</h2>

                    <x-form class="w-full flex flex-col gap-4 items-center">
                        <x-form.input-text icon="search" name="name" placeholder="Nome do colaborador" />

                        <x-form.select name="department" placeholder="Setor" :options="$departmentsToFilter" />
                        <x-form.select name="occupation" placeholder="Cargo" :options="$occupationsToFilter" />
                        <x-form.select name="severities" placeholder="Severidade" :options="['1', '2', '3', '4']" />

                        <x-action tag="button">Filtrar</x-action>
                    </x-form>
                </div>
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
                        @foreach ($usersList as $user)
                            <a
                                data-role="tr"
                                href="{{ route('employee-profile', $user->id) }}"
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all
                                    {{ $user->testCollections[0]->tests[0]->severity_color == "5" ? 'to-[#fc6f6f50]' : '' }}
                                    {{ $user->testCollections[0]->tests[0]->severity_color == "4" ? 'to-[#febc5350]' : '' }}
                                    {{ $user->testCollections[0]->tests[0]->severity_color == "3" ? 'to-[#faed5d50]' : '' }}
                                    {{ $user->testCollections[0]->tests[0]->severity_color == "2" ? 'to-[#8cf8c050]' : '' }}
                                    {{ $user->testCollections[0]->tests[0]->severity_color == "1" ? 'to-[#76fc7150]' : '' }}
                                ">
                                <span data-role="td" class="col-span-1 lg:col-span-2 truncate">{{ $user->name }}</span>
                                <span data-role="td" class="hidden lg:block">{{ $user->department }}</span>
                                <span data-role="td" class="hidden sm:block">{{ $user->occupation }}</span>
                                <span data-value="{{ $user->testCollections[0]->tests[0]->severity_color }}" data-role="td" class="truncate">{{ $user->testCollections[0]->tests[0]->severity_title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test-results-list.js') }}"></script>
</x-layouts.app>