<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Lista de comentários" 
                :breadcrumbs="[
                    'Lista de comentários' => '',
                ]"
            />

            @if($userFeedbacks)
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
                            'gender', 
                            'department', 
                            'occupation', 
                            'work_shift', 
                            'marital_status', 
                            'education_level', 
                            'age_range', 
                            'admission_range', 
                            'year'
                        ]" 
                    />

                    <div class="w-full sm:w-fit">
                        <x-action href="{{ route('export-feedbacks') }}" width="full">Exportar comentários</x-action>
                    </div>
                </div>
     
                <x-table>
                    <x-table.head class="flex items-center gap-4">
                        <x-table.head.sortable-th class="w-1/2 md:w-48" field="department">
                            Setor
                        </x-table.head.sortable-th>
                        <x-table.head.sortable-th class="hidden md:block w-24" field="work_shift">
                            Turno
                        </x-table.head.sortable-th>
                        <x-table.head.th class="flex-1">Comentário</x-table.head.th>
                    </x-table.head>
                    <x-table.body>
                        @foreach ($userFeedbacks as $user)
                            @foreach ($user->feedbacks as $feedback)
                                <x-table.body.tr tag="a" href="{{ route('feedbacks.show', $feedback) }}" class="flex items-center gap-4" >
                                    <x-table.body.td class="truncate w-1/2 md:w-48" title="{{ $user->department }}">{{ $user->department }}</x-table.body.td>
                                    <x-table.body.td class="hidden md:block w-24">{{ $user->work_shift }}</x-table.body.td>
                                    <x-table.body.td class="truncate flex-1" title="{{ $feedback->content }}">{{ $feedback->content }}</x-table.body.td>
                                </x-table.body.tr>
                            @endforeach
                        @endforeach
                    </x-table.body>
                </x-table>
                
                {{ $userFeedbacks->links('vendor.pagination.tailwind') }}
            @else
                <div class="w-full flex flex-col items-center gap-2">
                    <img src="{{ asset('assets/registers-not-found.svg') }}" alt="" class="max-w-72">
                    <p class="text-base text-center">Você ainda não realizou nenhuma Pesquisa de Clima Organizacional.</p>
                </div>
            @endif

        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>
<script src="{{ asset('js/dashboard/feedbacks/index.js') }}"></script>