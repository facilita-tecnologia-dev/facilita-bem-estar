<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Formulários de Pesquisa" 
                :back="route('custom-collections.show', $customCollection)" 
                :breadcrumbs="[
                    'Formulários de Pesquisa' => route('custom-collections.index'),
                    $customCollection->name => route('custom-collections.show', $customCollection),
                    $customTest->display_name => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <div class="w-full space-y-6">
                <div class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center justify-between">
                    <h2 class="text-base sm:text-lg font-semibold">{{ $customTest->display_name }}</h2>
                    <x-action variant="secondary" tag="button" type="button" onclick="showAddQuestionModal()">Adicionar Questão</x-action>
                </div>
            
                <div class="space-y-4">
                    <h2 class="text-base sm:text-lg font-semibold px-2">Questões</h2>
                    <x-table>
                        <x-table.head class="flex items-center justify-between mb-1 bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md">
                            <x-table.head.th class="flex-1">
                                Enunciado
                            </x-table.head.th>
                            <x-table.head.th class="w-12"></x-table.head.th>
                            <x-table.head.th class="w-12"></x-table.head.th>
                        </x-table.head>
                        <x-table.body>
                            @foreach ($customTest->questions as $question)
                                <x-table.body.tr class="flex items-center gap-4">
                                    <x-table.body.td class="truncate flex-1" title="{{ $question->statement }}">{{ $question->statement }}</x-table.body.td>
                                    <x-table.body.td class="w-12">
                                        <x-action href="{{ route('custom-collections.tests.questions.edit', ['customCollection' => $customCollection, 'customTest' => $customTest, 'customQuestion' => $question]) }}" variant="primary">
                                            <i class="fa-solid fa-pencil pointer-events-none"></i>    
                                        </x-action>    
                                    </x-table.body.td>
                                    <x-table.body.td class="w-12">
                                        <x-form action="{{ route('custom-collections.tests.questions.destroy', ['customCollection' => $customCollection, 'customTest' => $customTest, 'customQuestion' => $question]) }}" delete>
                                            <x-action tag="button" variant="danger">
                                                <i class="fa-solid fa-trash pointer-events-none"></i>    
                                            </x-action>    
                                        </x-form>
                                    </x-table.body.td>
                                </x-table.body.tr>
                            @endforeach
                        </x-table.body>
                    </x-table>
                </div>
            </div>
                
            <x-modal.background data-role="add-question-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Adicionar Questão</x-modal.title>

                    <x-form action="{{ route('custom-collections.tests.questions.store', ['customCollection' => $customCollection, 'customTest' => $customTest]) }}" post class="w-full space-y-2">
                        <input type="hidden" name="custom_test_id" value="{{ $customCollection->id }}">
                        <x-form.input-text name="statement" label="Enunciado" placeholder="Digite o enunciado da questão" />
                        <x-action tag="button" variant="secondary" width="full">Adicionar</x-action>
                    </x-form>
                </x-modal.wrapper>
            </x-modal.background>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/custom-collections/custom-tests/show.js') }}"></script>
</x-layouts.app>