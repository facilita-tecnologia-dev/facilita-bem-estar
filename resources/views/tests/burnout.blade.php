<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
            <x-test.title-container>

                <x-test.heading>
                    10 - Burnout
                </x-test.heading>
                
                <x-test.subtitle>
                    Responda numa escala de 0 (Nunca) a 6 (Todos os dias):
                </x-test.subtitle>

            </x-test.title-container>
            
            <x-test.questions-form action="{{ route('test.submit', 'burnout')}}" id="test-form" post>

                <x-test.questions-list :testInfo="$testInfo"  />

            </x-test.questions-form>
    
            
            <div class="flex items-center justify-between w-full">

                <x-actions.anchor href="{{ route('test', 'autonomia') }}" variant="outline" color="danger">
                    Refazer teste anterior
                </x-actions.anchor>

                <x-actions.button form="test-form" type="submit" variant="solid" color="success">
                    Próximo teste
                </x-actions.button>

            </div>
            
        </div>

        <x-footer>
            Baseado no Maslach Burnout Inventory (MBI) [Maslach et al., 1996]
        </x-footer>

</x-layouts.app>