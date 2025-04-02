<x-layouts.app>
    <x-box>
        <x-heading>
            Facilita
            <span class="relative text-fuchsia-600">
                Saúde Mental
            </span>
        </x-heading>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-8 justify-items-center mb-4 mt-4 sm:mb-6 sm:mt-6">
            <div class="flex-1 py-4 sm:py-8 px-4 flex flex-col items-center justify-center gap-4">
                <div class="text-center">
                    <p class="text-md md:text-lg font-medium">Acessar o dashboard de resultados</p>
                </div>
                <div class="flex gap-4">
                    <x-actions.anchor href="{{ route('general-results.dashboard') }}" color='success' variant="solid">Dashboard</x-actions.anchor>
                </div>
        
            </div>

            <div class="w-full h-0.5 sm:h-full sm:w-0.5 bg-teal-700"></div>

            <div class="flex-1 py-4 sm:py-8 px-4 flex flex-col items-center justify-center gap-4">
                <div class="text-center">
                    <p class="text-md md:text-lg font-medium">Realizar testes de bem estar</p>
                </div>
                <div class="flex gap-4">
                    <x-actions.anchor href="{{ route('welcome') }}" color='success' variant="solid">Começar testes</x-actions.anchor>
                </div>
        
            </div>
        </div>

        <x-actions.anchor href="{{ route('logout') }}" onclick="return confirm('Você deseja mesmo sair?')" variant="underline" color="danger">
            Fazer Logout / Sair
        </x-actions.anchor>

    </x-box>
</x-layouts.app>