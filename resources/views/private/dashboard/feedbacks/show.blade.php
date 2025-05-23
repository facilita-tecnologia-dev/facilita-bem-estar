<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Comentário | Detalhe" :back="route('feedbacks.index')" />
            
            <div class="w-full bg-gray-100/50 p-4 rounded-md shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Setor</p>
                        {{ $parentUser->department }}
                    </div>
                    <div>
                        <p class="font-semibold">Turno</p>
                        {{ $parentUser->work_shift }}
                    </div>
                    <div class="sm:col-span-2">
                        <p class="font-semibold">Comentário</p>
                        {{ $feedback->content }}
                    </div>
                </div>

            </div>

            {{-- <x-table>
                <x-table.head class="flex items-center">
                    <x-table.head.th>Outros comentários desse setor</x-table.head.th>
                </x-table.head>
                <x-table.body>
                    @foreach ($otherFeedbacksFromSameUser as $otherFeedback)
                        <x-table.body.tr tag="a" href="{{ route('feedbacks.show', $otherFeedback) }}" class="flex items-center justify-between gap-4">
                            <x-table.body.td class="truncate" title="{{ $otherFeedback->content }}">{{ $otherFeedback->content }}</x-table.body.td>
                            <x-table.body.td>{{ $otherFeedback->created_at->format('d/m/Y') }}</x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table> --}}

        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>