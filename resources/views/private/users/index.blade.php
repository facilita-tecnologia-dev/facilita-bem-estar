<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <x-page-title title="Lista de colaboradores" />

            @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif
            
            <div class="w-full flex justify-end items-center gap-2">
                <div class="flex gap-2">
                    <a href="{{ route('user.import') }}" class="bg-gray-100/50 w-10 h-10 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                        <i class="fa-solid fa-upload"></i>
                    </a>
                    <a href="{{ route('user.create') }}" class="bg-gray-100/50 w-10 h-10 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    
                    <x-filters-trigger
                        class="h-10 w-10"
                        :modalFilters="['name', 'cpf', 'gender', 'department', 'occupation']" 
                    />
                </div>
            </div>

            <x-filters-info-bar
                :filtersApplied="$filtersApplied"
            />
    
            <div class="w-full flex flex-col gap-4 items-end">
                <div class="w-full space-y-2" data-role="tests-list">
                    <div class="grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-10 gap-3 bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                        <span data-role="th" onclick="reorderTable(event, 0)" class="text-sm sm:text-base font-semibold text-left col-span-3">Nome</span>
                        <span data-role="th" onclick="reorderTable(event, 1)" class="text-sm sm:text-base hidden lg:block font-semibold text-left">Idade</span>
                        <span data-role="th" onclick="reorderTable(event, 2)" class="text-sm sm:text-base hidden md:block col-span-2 font-semibold text-left">Setor</span>
                        <span data-role="th" onclick="reorderTable(event, 3)" class="text-sm sm:text-base hidden sm:block col-span-2 font-semibold text-left">Cargo</span>
                        <span data-role="th" class="text-sm sm:text-base truncate font-semibold text-center">
                            <i class="fa-solid fa-brain"></i>
                        </span>
                        <span data-role="th" class="text-sm sm:text-base truncate font-semibold text-center">
                            <i class="fa-solid fa-cloud"></i>
                        </span>
                    </div>

                    <div class="space-y-2" data-role="tbody">
                        @foreach ($users as $user)
                            <a
                                data-role="tr"
                                href="{{ route('user.show', $user) }}"
                                class="
                                    px-4 py-2 w-full rounded-md shadow-md bg-gradient-to-b from-[#FFFFFF25] to-[#FFFFFF60] grid grid-cols-5 sm:grid-cols-7 md:grid-cols-9 lg:grid-cols-10 gap-3 items-center relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all
                                ">
                                <span data-role="td" class="text-sm sm:text-base col-span-3 truncate">{{ $user->name }}</span>
                                <span data-role="td" class="text-sm sm:text-base hidden lg:block truncate">{{ $user->birth_date ? Carbon\Carbon::create($user->birth_date)->age . ' anos' : 'Não cadastrado' }}</span>
                                <span data-role="td" class="text-sm sm:text-base hidden md:block col-span-2 truncate">{{ $user->department ?? 'Não cadastrado' }}</span>
                                <span data-role="td" class="text-sm sm:text-base hidden sm:block col-span-2 truncate">{{ $user->occupation ?? 'Não cadastrado' }}</span>
                                <span data-role="td" class="text-sm sm:text-base text-center">
                                    <i class="fa-solid fa-check text-lg"></i>
                                </span>
                                <span data-role="td" class="text-sm sm:text-base text-center">
                                    <i class="fa-solid fa-xmark text-lg"></i>
                                </span>
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