<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Editar Coleção de Testes" />

            @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 text-sm sm:text-base">
                <div class="flex items-center justify-between gap-4">
                    <p>Adicione ou remova testes ou questões e depois clique em "Salvar" para armazenar suas mudanças.</p>
                    <x-action tag="button" form="collection-update" variant="secondary">Salvar</x-action>
                </div>

                <x-form class="flex flex-col gap-6 mt-6" id="collection-update" action="{{ route('company-collections.update', $collection) }}" post>                
                    <div data-role="tests-container" class="w-full flex flex-col gap-6">
                        @foreach ($testsToUpdate as $test)
                            <div data-role="test-section" class="flex flex-col gap-2">
                                <div class="bg-gray-200 px-4 p-2 rounded-md flex items-center justify-between gap-4" data-role="test-header">
                                    <h2 class="text-sm sm:text-base font-semibold">{{ $test->display_name }}</h2>
                                    <div class="flex gap-2">
                                        @php
                                            $testIsDeleted = $test->questions->every(function ($question) {
                                                return $question->is_deleted;
                                            });

                                            $testActive = $test->questions->every(function ($question) {
                                                return !$question->is_deleted;
                                            });
                                        @endphp
                                        <x-action tag="button" type="button" width="full" data-role="add-question">
                                            Adicionar questão
                                        </x-action>
                                        <x-action 
                                            tag="button" 
                                            type="button" 
                                            data-role="delete-test"
                                            style="{{ $testIsDeleted ? 'opacity: 50%;' : '' }}"
                                            :disabled="$testIsDeleted"
                                        >
                                            <i class="fa-solid fa-minus"></i>
                                        </x-action>
                                        <x-action 
                                            tag="button" 
                                            type="button" 
                                            data-role="restore-test"
                                            style="{{ $testActive ? 'opacity: 50%;' : '' }}"
                                            :disabled="$testActive"
                                            >
                                            <i class="fa-solid fa-plus"></i>
                                        </x-action>
                                    </div>
                                </div>
                                <div class="contents" data-role="questions-container">
                                    @foreach ($test->questions as $question)
                                        <div data-role="question" data-deleted="{{ $question->is_deleted }}" class="flex gap-2 items-center px-4">
                                            <x-form.input-text 
                                                name="{{ $test->display_name }}_questions[]" 
                                                value="{!! $question->statement == null ? $question->relatedQuestion->statement : $question->statement !!}" 
                                                placeholder="Digite o enunciado da questão..." 
                                                disabled="{{ $question->is_deleted == 1 ? true : false }}"
                                                style="{{ $question->is_deleted == 1 ? 'opacity: 50%;' : '' }}"
                                                readonly
                                            />
                                            <x-action variant="secondary" tag="button" type="button" data-role="delete" style="{{ $question->is_deleted == 1 ? 'opacity: 50%;' : '' }}">
                                                <i class="fa-solid fa-minus"></i>
                                            </x-action>
                                            <x-action variant="secondary" tag="button" type="button" data-role="restore" style="{{ $question->is_deleted == 1 ? 'opacity: 100%;' : '' }}">
                                                <i class="fa-solid fa-plus"></i>
                                            </x-action>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-4 px-4">
                        <x-action type="button" tag="button" variant="secondary" width="full" data-role="add-test">
                            Adicionar novo teste
                        </x-action>
                        <x-action tag="button" form="collection-update" variant="secondary" width="full">
                            Salvar
                        </x-action>
                    </div>
                </x-form>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/tests/collections/update.js') }}"></script>
</x-layouts.app>