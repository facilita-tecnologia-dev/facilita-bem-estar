<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    11 - Estresse
                </x-test.heading>
                
                <x-test.subtitle>
                    Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-test.questions-form action="{{ route('test.submit', 'estresse')}}" id="test-form" post>

                <x-test.questions-list :testInfo="$testInfo"  />

            </x-test.questions-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', 'burnout') }}" variant="outline" color="danger">
                    Refazer teste anterior
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>
            
        </div>

        <x-footer>
            Baseado na Perceived Stress Scale (PSS) [Cohen et al., 1983]
        </x-footer>

</x-layouts.app>