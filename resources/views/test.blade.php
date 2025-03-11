<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    {{ $testIndex }} - {{ $testName }}
                </x-test.heading>
                
                <x-test.subtitle>
                    {{ $testStatement }}
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-form action="{{ route('test.submit', $testIndex)}}" class="flex-1 overflow-auto w-full space-y-5" id="test-form" post>

                <x-test.questions-list :testQuestions="$testQuestions"  />

            </x-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', $testIndex - 1) }}" variant="outline" color="danger">
                    Cancelar
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>

        </div>

        @if(session('errors'))
            <div class="bg-red-400 p-3 rounded-md fixed right-5 bottom-5">
                <p class="text-white font-medium" >Todas as perguntas devem ser respondidas!</p>
            </div>
        @endif

        
        <x-footer>
            {{ $testReference }}
        </x-footer>

</x-layouts.app>