<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    1 - Ansiedade
                </x-test.heading>
                
                <x-test.subtitle>
                    Nas últimas 2 semanas, com que frequência você foi incomodado pelos problemas abaixo?
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-test.questions-form action="{{ route('test.submit', 'ansiedade')}}" id="test-form" post>

                <x-test.questions-list :testQuestions="$testQuestions"  />

            </x-test.questions-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('welcome') }}" variant="outline" color="danger">
                    Cancelar
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>

        </div>

        <x-footer>
            Baseado no GAD-7 (Generalized Anxiety Disorder-7) [Spitzer et al., 2006]
        </x-footer>

</x-layouts.app>