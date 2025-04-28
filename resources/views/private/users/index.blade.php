<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-structure.sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <x-structure.page-title title="Lista de colaboradores" />

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
                    <x-action href="{{ route('user.import') }}">
                        <i class="fa-solid fa-upload"></i>
                    </x-action>
                    <x-action href="{{ route('user.create') }}">
                        <i class="fa-solid fa-plus"></i>
                    </x-action>
                    
                    <x-filters-trigger
                        class="h-10 w-10"
                        :modalFilters="['name', 'cpf', 'gender', 'department', 'occupation']" 
                    />
                </div>
            </div>

            <x-filters-info-bar
                :filtersApplied="$filtersApplied"
            />

            <x-table class="flex flex-col gap-1">
                <x-table.head class="flex items-center gap-3">
                    <x-table.head.th onclick="reorderTable(event, 0)" class="flex-1">Nome</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 1)" class="hidden lg:block w-24">Idade</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 2)" class="hidden md:block flex-1">Setor</x-table.head.th>
                    <x-table.head.th onclick="reorderTable(event, 3)" class="hidden sm:block flex-1">Cargo</x-table.head.th>
                    <x-table.head.th class="truncate text-center w-12"><i class="fa-solid fa-brain"></i></x-table.head.th>
                    <x-table.head.th class="truncate text-center w-12"><i class="fa-solid fa-cloud"></i></x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($users as $user)
                        <x-table.body.tr tag="a" href="{{ route('user.show', $user) }}" class="flex items-center gap-3" >
                            <x-table.body.td class="truncate flex-1">{{ $user->name }}</x-table.body.td>
                            <x-table.body.td class="hidden lg:block w-24">{{ $user->birth_date ? Carbon\Carbon::create($user->birth_date)->age . ' anos' : 'Não cadastrado' }}</x-table.body.td>
                            <x-table.body.td class="hidden md:block truncate flex-1">{{ $user->department ?? 'Não cadastrado' }}</x-table.body.td>
                            <x-table.body.td class="hidden sm:block truncate flex-1">{{ $user->occupation ?? 'Não cadastrado' }}</x-table.body.td>
                            <x-table.body.td class="text-center w-12">
                                @if($user->latestPsychosocialCollection && $user->latestPsychosocialCollection->created_at->year == Carbon\Carbon::now()->year)
                                    <i class="fa-solid fa-check"></i>
                                @else
                                    <i class="fa-solid fa-xmark"></i>
                                @endif
                            </x-table.body.td>
                            <x-table.body.td class="text-center w-12">
                                @if($user->latestOrganizationalClimateCollection && $user->latestOrganizationalClimateCollection->created_at->year == Carbon\Carbon::now()->year)
                                    <i class="fa-solid fa-check"></i>
                                @else
                                    <i class="fa-solid fa-xmark"></i>
                                @endif
                            </x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/user/index.js') }}"></script>
</x-layouts.app>
