<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    6 - Conflitos
                </x-test.heading>
                
                <x-test.subtitle>
                    Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-form action="{{ route('test.submit', 'conflitos')}}" id="test-form" post>

                <x-test.questions-list :testQuestions="$testQuestions"  />

            </x-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', 'inseguranca') }}" variant="outline" color="danger">
                    Refazer teste anterior
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>
            
        </div>

        <x-footer>
            Baseado no Interpersonal Conflict at Work Scale (ICAWS) [Spector & Jex, 1998]
        </x-footer>

</x-layouts.app>