<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-center justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Etapa - {{ $testIndex }}</h2>
            </div>

            <div class="flex flex-col items-center gap-8">
                <div class="flex flex-col gap-3 w-full max-w-[550px] max-h-full">
                    <p class="text-lg font-semibold text-left text-gray-800">
                        {{ $test->statement }}
                    </p>
                    
                    <x-form action="{{ route('test.submit', [$collection, $testIndex]) }}" class="flex-1  flex flex-col gap-5" id="test-form" post>
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
                    <x-action href="{{ $testIndex == 1 ? route('choose-test') : route('test', [$collection, $testIndex - 1]) }}" variant="secondary">
                        Voltar
                    </x-action>
                    <x-action form="test-form" tag="button" variant="secondary">
                        Prosseguir
                    </x-action>
                </div>
            </div>

            {{-- <div class="w-full max-w-[550px]">
                <p class="text-xs md:text-sm text-center">{{ $test->reference }}</p>
            </div> --}}
        </div>

        @if(session('errors'))
            <div class="bg-gray-100 border border-red-400 p-3 rounded-md fixed right-5 bottom-5 shadow-md">
                <p class="text-gray-800 font-regular text-xs md:text-sm">Todas as perguntas devem ser respondidas!</p>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
</x-layouts.app>