<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Lista de comentários" />

            <div class="w-full flex items-start justify-end gap-2">
                <x-filters-info-bar
                    :filtersApplied="$filtersApplied"
                    :filteredUsers="$filteredUsers"
                />
  
                <x-filters-trigger
                    :modalFilters="['name', 'cpf', 'gender', 'department', 'occupation', 'year']" 
                />
            </div>
            
            <x-table>
                <x-table.head class="flex items-center gap-4">
                    <x-table.head.th class="flex-1">Nome</x-table.head.th>
                    <x-table.head.th class="hidden md:block w-24">Data</x-table.head.th>
                    <x-table.head.th class="flex-1">Comentário</x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($userFeedbacks as $user)
                        @foreach ($user->feedbacks as $feedback)
                            <x-table.body.tr tag="a" href="{{ route('feedbacks.show', $feedback) }}" class="flex items-center gap-4" >
                                <x-table.body.td class="truncate flex-1" title="{{ $user->name }}">{{ $user->name }}</x-table.body.td>
                                <x-table.body.td class="hidden md:block w-24">{{ $feedback->created_at->format('d/m/Y') }}</x-table.body.td>
                                <x-table.body.td class="truncate flex-1" title="{{ $feedback->content }}">{{ $feedback->content }}</x-table.body.td>
                            </x-table.body.tr>
                        @endforeach
                    @endforeach
                </x-table.body>
            </x-table>

        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>
<script src="{{ asset('js/dashboard/feedbacks/index.js') }}"></script>