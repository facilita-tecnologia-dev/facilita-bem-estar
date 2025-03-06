<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    7 - Relações Sociais
                </x-test.heading>
                
                <x-test.subtitle>
                    Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-test.questions-form action="{{ route('test.submit', 'relacoes-sociais')}}" id="test-form" post>

                <x-test.questions-list :testQuestions="$testQuestions"  />

            </x-test.questions-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', 'conflitos') }}" variant="outline" color="danger">
                    Refazer teste anterior
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>
            
        </div>

        <x-footer>
            Baseado no COPSOQ II [Pejtersen et al., 2010] e ISO 45003:2021
        </x-footer>

</x-layouts.app>