<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Lista de colaboradores</h2>
            </div>
  
            
            <div class="w-full flex items-end sm:items-center gap-4 flex-col sm:flex-row sm:justify-end">
                <button data-role="filter-modal-trigger" class="bg-gray-100 w-10 h-10 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>

            <div data-role="filter-modal" class="hidden z-50 left-0 top-0 fixed w-screen h-screen bg-gray-800/30 px-4 items-center justify-center">
                <div class="w-full max-w-[360px] bg-gray-100 py-8 px-4 rounded-md flex flex-col items-center gap-8">
                    <h2 class="text-center text-3xl font-semibold text-gray-800">Filtros</h2>

                    <x-form class="w-full flex flex-col gap-4 items-center">
                        <x-form.input-text icon="search" name="name" placeholder="Nome do colaborador" />
                        <x-form.input-text name="age" placeholder="Idade" />

                        <x-form.select name="department" placeholder="Setor" :options="['1', '2', '3', '4']" />
                        <x-form.select name="occupation" placeholder="Cargo" :options="['1', '2', '3', '4']" />

                        <x-action tag="button">Filtrar</x-action>
                    </x-form>
                </div>
            </div>
    
            <div class="w-full flex flex-col gap-4 items-end">
                <div class="w-full space-y-2" data-role="tests-list">
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                        <span data-role="th" onclick="reorderTable(event, 0)" class="text-base font-semibold text-left col-span-1 lg:col-span-2">Nome</span>
                        <span data-role="th" onclick="reorderTable(event, 1)" class="hidden lg:block text-base font-semibold text-left">Idade</span>
                        <span data-role="th" onclick="reorderTable(event, 2)" class="hidden lg:block text-base font-semibold text-left">Setor</span>
                        <span data-role="th" onclick="reorderTable(event, 3)" class="text-base font-semibold text-left">Cargo</span>
                    </div>

                    <div class="space-y-2" data-role="tbody">
                        @foreach ($employees as $employee)
                            <a
                                data-role="tr"
                                href="{{ route('employee-profile', $employee->id) }}"
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] to-[#FFFFFF60] grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all
                                ">
                                <span data-role="td" class="col-span-1 lg:col-span-2 truncate">{{ $employee->name }}</span>
                                <span data-role="td" class="hidden lg:block">{{ $employee->age }}</span>
                                <span data-role="td" class="hidden lg:block">{{ $employee->department }}</span>
                                <span data-role="td" class="truncate">{{ $employee->occupation }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/employees-list.js') }}"></script>
</x-layouts.app>