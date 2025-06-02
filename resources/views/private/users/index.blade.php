<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-structure.sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <x-structure.page-title 
                title="Lista de colaboradores" 
                :breadcrumbs="[
                    'Lista de colaboradores' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif
            
            <div class="w-full flex justify-end flex-col-reverse md:flex-row items-start gap-2">
                @if($users)
                    <div class="w-full flex flex-col-reverse md:flex-row gap-4 items-start">
                        <div class="flex items-center gap-2 w-full flex-wrap">
                            <x-numbers-of-records :value="$filteredUserCount" />

                            <x-applied-filters
                                :filtersApplied="$filtersApplied"
                            />
                        </div>

                        <x-filter-actions
                            :filtersApplied="$filtersApplied"
                            :modalFilters="[
                                'name',
                                'cpf',
                                'gender', 
                                'department', 
                                'occupation', 
                                'work_shift', 
                                'marital_status', 
                                'education_level', 
                                'age_range', 
                                'admission_range',
                                'hasAnsweredPsychosocialCollection', 
                                'hasAnsweredOrganizationalCollection'
                            ]" 
                        />
                    </div>
                @endif

                @can('user-create')
                    <div class="w-full md:w-fit flex gap-2">
                        <div class="w-full md:w-fit">
                            <x-action href="{{ route('user.import') }}" width="full">
                                <i class="fa-solid fa-upload text-sm sm:text-base"></i>
                            </x-action>
                        </div>
                        <div class="w-full md:w-fit">
                            <x-action href="{{ route('user.create') }}" width="full">
                                <i class="fa-solid fa-plus text-sm sm:text-base"></i>
                            </x-action>
                        </div>
                    </div>
                @endcan
            </div>

            @if($users)
                <x-table class="flex flex-col gap-1">
                    <x-table.head class="flex items-center gap-3">
                        <x-table.head.sortable-th class="flex-1" field="name">
                            Nome
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden lg:block w-24" field="age">
                            Idade
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden md:block flex-1" field="department">
                            Setor
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden sm:block flex-1" field="occupation">
                            Função
                        </x-table.head.sortable-th>
                        <x-table.head.th class="text-center w-6 sm:w-12">
                        </x-table.head.th>
                        <x-table.head.sortable-th class="truncate text-center w-6 sm:w-12" field="psychosocial-risks">
                            <i class="fa-solid fa-brain"></i>
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="truncate text-center w-6 sm:w-12" field="organizational-climate">
                            <i class="fa-solid fa-cloud"></i>
                        </x-table.head.sortable-th>
                    </x-table.head>
                    <x-table.body>
                        @foreach ($users as $user)
                            <x-table.body.tr tag="a" href="{{ route('user.show', $user) }}" class="flex items-center gap-3" >
                                <x-table.body.td class="truncate flex-1">{{ $user->name }}</x-table.body.td>
                                <x-table.body.td class="hidden lg:block w-24">{{ $user->birth_date ? Carbon\Carbon::create($user->birth_date)->age . ' anos' : 'Não cadastrado' }}</x-table.body.td>
                                <x-table.body.td class="hidden md:block truncate flex-1">{{ $user->department ?? 'Não cadastrado' }}</x-table.body.td>
                                <x-table.body.td class="hidden sm:block truncate flex-1">{{ $user->occupation ?? 'Não cadastrado' }}</x-table.body.td>
                                <x-table.body.td class="text-center w-6 sm:w-12">
                                    @if($user->hasRole('manager'))
                                        <i class="fa-solid fa-briefcase"></i>
                                    @endif
                                </x-table.body.td>
                                <x-table.body.td class="text-center w-6 sm:w-12">
                                    @if($user->latestPsychosocialCollection)
                                        <i class="fa-solid fa-check"></i>
                                    @else
                                        <i class="fa-solid fa-xmark"></i>
                                    @endif
                                </x-table.body.td>
                                <x-table.body.td class="text-center w-6 sm:w-12">
                                    @if($user->latestOrganizationalClimateCollection)
                                        <i class="fa-solid fa-check"></i>
                                    @else
                                        <i class="fa-solid fa-xmark"></i>
                                    @endif
                                </x-table.body.td>
                            </x-table.body.tr>
                        @endforeach
                    </x-table.body>
                </x-table>

                {{ $users->links() }}
            @else
                <div class="w-full flex flex-col items-center gap-2">
                    <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                    <p class="text-base text-center">Você ainda não registrou colaboradores.</p>
                </div>         
            @endif
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/user/index.js') }}"></script>
</x-layouts.app>
