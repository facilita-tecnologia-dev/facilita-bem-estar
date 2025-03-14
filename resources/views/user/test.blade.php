<x-layouts.app>
    <x-box>
        <x-test.title-container>

            <x-heading>
                Etapa - {{ $testIndex }}
            </x-heading>

            <p class="text-sm md:text-base lg:text-lg font-medium text-center text-teal-700 mb-4 mt-4">
                {{ $testStatement }}
            </p>

        </x-test.title-container>
        
        <x-form action="{{ route('test.submit', $testIndex)}}" class="py-2 flex-1 overflow-auto w-full" id="test-form" post>
            
            <x-test.questions-list :testQuestions="$testQuestions" :pendingAnswers="$pendingAnswers" />

        </x-form>

        
        <div class="flex items-center justify-between w-full mt-4">

            <x-actions.anchor href="{{ route('test', $testIndex - 1) }}" variant="outline" color="danger">
                Voltar
            </x-actions.anchor>

            <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                Próximo teste
            </x-actions.button>

        </div>

        @if(session('errors'))
            <div class="bg-red-400 p-3 rounded-md fixed right-5 bottom-5">
                <p class="text-white font-medium" >Todas as perguntas devem ser respondidas!</p>
            </div>
        @endif

        <x-footer class="mt-4">
            {{ $testReference }}
        </x-footer>
    </x-box>
</x-layouts.app>