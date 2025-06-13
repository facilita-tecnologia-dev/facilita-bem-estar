<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Formulários de Pesquisa" 
                :back="route('custom-collections.index')" 
                :breadcrumbs="[
                    'Formulários de Pesquisa' => route('custom-collections.index'),
                    $customCollection->name => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <x-form action="{{ route('custom-collections.update', ['customCollection' => $customCollection]) }}" put class="w-full space-y-6">
                <div class="w-full bg-gray-100 rounded-md shadow-md p-4 flex flex-col gap-2">
                    <div class="w-full flex gap-2 items-end">
                        <div class="w-full flex flex-col gap-1.5">
                            <label>Nome da Pesquisa:</label> 
                            <input 
                                type="text" 
                                name="collection_name" 
                                class="bg-gray-200 px-2 py-1.5 shadow-md rounded-md focus:outline-gray-300 text-lg font-medium"
                                value="{{ $customCollection->name }}"
                            >
                        </div>
                        <x-action variant="secondary" tag="button" data-tippy-content="Salvar as alterações da pesquisa">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Salvar alterações
                        </x-action>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label>Descrição da Pesquisa:</label> 
                        <textarea 
                            name="collection_description" 
                            class="bg-gray-200 px-2 py-2 shadow-md rounded-md focus:outline-gray-300 text-base resize-none h-20"
                            placeholder="Digite a descrição da pesquisa"
                        >{{ $customCollection->description }}</textarea>
                    </div>
                </div>

                <div class="p-4 bg-sky-200 shadow-md rounded-md space-y-4">
                    <p>As respostas para todas as perguntas são avaliadas com base na seguinte pontuação:</p>

                    <ul>
                        <li>
                            <span class="font-medium">Discordo totalmente:</span>
                            1 ponto
                        </li>
                        <li>
                            <span class="font-medium">Discordo parcialmente:</span>
                            2 pontos
                        </li>
                        <li>
                            <span class="font-medium">Não tenho opinião definida:</span>
                            3 pontos
                        </li>
                        <li>
                            <span class="font-medium">Concordo parcialmente:</span>
                            4 pontos
                        </li>
                        <li>
                            <span class="font-medium">Concordo totalmente:</span>
                            5 pontos
                        </li>
                    </ul>
                </div>
            
                <div class="space-y-4">
                    <div class="flex gap-2">
                        <x-structure.page-title title="Testes" class="w-full text-base md:text-xl" />
                        <x-action variant="primary" tag="button" type="button" onclick="showAddTestModal()">
                            <i class="fa-solid fa-plus"></i>
                            Adicionar Teste
                        </x-action>
                    </div>

                    @foreach ($customCollection->tests as $test)
                        <div class="p-4 bg-gray-100 shadow-md rounded-md space-y-4">
                            <div class="flex gap-2 items-end">
                                <div class="w-full flex flex-col gap-1.5">
                                    <label for="test_{{ $test->id }}_name">Nome do teste: </label>
                                    <input 
                                        type="text" 
                                        class="bg-gray-200 px-2 py-1.5 shadow-md rounded-md focus:outline-gray-300 text-lg font-medium"
                                        name="tests[{{ $test->id }}][display_name]" 
                                        id="test_{{ $test->id }}_name"
                                        placeholder="Digite o nome do teste"
                                        value="{{ $test['display_name'] }}"
                                    >
                                </div>
                                <x-action href="{{ route('custom-collections.tests.destroy', ['customCollection' => $customCollection, 'customTest' => $test]) }}" variant="danger">
                                    <i class="fa-solid fa-trash pointer-events-none"></i>
                                    Excluir teste
                                </x-action>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="test_{{ $test->id }}_statement">Orientação para resposta: </label>
                                <input 
                                    type="text" 
                                    class="bg-gray-200 px-2 py-1.5 rounded-md shadow-md focus:outline-gray-300 text-base font-medium"
                                    name="tests[{{ $test->id }}][statement]" 
                                    id="test_{{ $test->id }}_statement"
                                    placeholder="Digite a orientação para resposta"
                                    value="{{ $test['statement'] }}"
                                >
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-4 justify-between items-center">
                                    <h2 class="font-medium text-lg flex-1">Questões</h2>
                                    <x-action variant="secondary" tag="button" type="button" data-test="{{ $test->id }}" onclick="showAddQuestionModal(event)">
                                        <i class="fa-solid fa-plus"></i>
                                        Adicionar Questão
                                    </x-action>
                                </div>
                                @foreach ($test->questions as $question)
                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            name="tests[{{ $test->id }}][questions][{{ $question->id }}]" 
                                            class="w-full bg-gray-200 shadow-md rounded-md px-2 py-1.5 focus:outline-gray-300 text-base font-normal"
                                            value="{{ $question['statement'] }}"
                                            placeholder="Digite o enunciado da questão"
                                        >
                                        <x-action href="{{ route('custom-collections.tests.questions.destroy', ['customCollection' => $customCollection, 'customTest' => $test, 'customQuestion' => $question]) }}" data-tippy-content="Excluir a questão" variant="danger">
                                            <i class="fa-solid fa-trash pointer-events-none"></i>    
                                        </x-action>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-form>
                
            <x-modal.background data-role="add-test-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Adicionar Teste</x-modal.title>

                    <x-form action="{{ route('custom-collections.tests.store', $customCollection) }}" post class="w-full space-y-2">
                        <input type="hidden" name="custom_collection_id" value="{{ $customCollection->id }}">
                        <x-form.input-text name="display_name" label="Nome" placeholder="Digite o nome do teste" />
                        <x-form.input-text name="statement" label="Enunciado/Frase" placeholder="Digite o enunciado do teste" />
                        <input type="hidden" name="order" value="{{ $customCollection->tests->max('order') + 1 }}">
                        <x-action tag="button" variant="secondary" width="full">Adicionar</x-action>
                    </x-form>
                </x-modal.wrapper>
            </x-modal.background>

            <x-modal.background data-role="add-question-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Adicionar Questão</x-modal.title>

                    <x-form action="{{ route('custom-collections.tests.questions.store', ['customCollection' => $customCollection]) }}" post class="w-full space-y-2">
                        <input type="hidden" name="custom_test_id" value="">
                        <x-form.input-text name="statement" label="Enunciado" placeholder="Digite o enunciado da questão" />
                        <x-action tag="button" variant="secondary" width="full">Adicionar</x-action>
                    </x-form>
                </x-modal.wrapper>
            </x-modal.background>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/custom-collections/show.js') }}"></script>
</x-layouts.app>