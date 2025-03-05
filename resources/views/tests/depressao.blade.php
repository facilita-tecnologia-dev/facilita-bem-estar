<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    2 - Depressão
                </x-test.heading>
                
                <x-test.subtitle>
                    Nas últimas 2 semanas, com que frequência você foi incomodado por algum dos problemas abaixo?
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-test.questions-form action="{{ route('test.submit', 'depressao')}}" id="test-form" post>

                <x-test.questions-list :testInfo="$testInfo"  />

            </x-test.questions-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', 'ansiedade') }}" variant="outline" color="danger">
                    Refazer teste anterior
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>
            
        </div>

        <x-footer>
            Baseado no PHQ-9 (Patient Health Questionnaire-9) [Kroenke et al., 2001]
        </x-footer>

</x-layouts.app>