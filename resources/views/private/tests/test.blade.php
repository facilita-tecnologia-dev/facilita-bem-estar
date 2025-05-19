<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container class="items-center">
            <x-structure.page-title title="{{ $collection->key_name == 'psychosocial-risks' ? 'Etapa - '.$testIndex : $test->display_name }}" centered />

            <div class="flex flex-col items-center gap-8">
                <div class="flex flex-col gap-3 w-full max-w-[550px] max-h-full">
                    <p class="text-lg font-semibold text-left text-gray-800">
                        {{ $test->statement }}
                    </p>
                    
                    <x-form action="{{ route('enviar-teste', [$collection, $testIndex]) }}" class="flex-1 flex flex-col gap-5" id="test-form" post>
                        @foreach ($test->questions->toArray() as $key => $question)
                            @php
                                $questionNumber = $key + 1;
                                $pendingAnswer = $pendingAnswers[$key]->value ?? '';
                            @endphp

                            <div data-role="test-question" class="w-full flex flex-col gap-2 items-center">
                                <div class="w-full px-4 py-2">
                                    <p class="text-base text-gray-800 text-left flex items-center gap-4 font-medium">
                                        <i class="fa-solid fa-question"></i>
                                        {{ $question['statement'] }}
                                    </p>
                                </div>
                                
                                @foreach ($question['options'] as $optionNumber => $option)
                                    <x-test.option 
                                        :option="$option" 
                                        questionNumber="{{ $question['id'] }}" 
                                        optionNumber="{{ $optionNumber }}" 
                                        :pendingAnswer="$pendingAnswer" 
                                    />
                                @endforeach

                                @error("question_" . $question['id'])
                                    <span class="bg-rose-400  text-white text-base py-0.5 px-2 rounded-md">A questão {{ $question['id'] }} deve ser respondida</span>
                                @enderror
                            </div>
                        @endforeach
                    </x-form>
                </div>
                <div class="w-full flex items-center justify-between">
                    <div class="{{ $testIndex == 1 ? 'pointer-events-none opacity-50' : '' }}">
                        <x-action href="{{ route('responder-teste', [$collection, $testIndex - 1]) }}" variant="primary">
                            Voltar
                        </x-action>
                    </div>
                    <x-action form="test-form" type="submit" tag="button" variant="primary">
                        Prosseguir
                    </x-action>
                </div>
            </div>
        </x-structure.main-content-container>

        @if(session('errors'))
            <div class="bg-gray-100 border border-red-400 p-3 rounded-md fixed right-5 bottom-5 shadow-md">
                <p class="text-gray-800 font-regular text-xs md:text-sm">Todas as perguntas devem ser respondidas!</p>
            </div>
        @endif
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
</x-layouts.app>