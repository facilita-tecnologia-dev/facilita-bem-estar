<x-layouts.app>
    <x-box>
        <x-heading>
            Bem vindo(a) aos Testes de 
            <span class="relative text-teal-400">
                Bem-estar
                <img src="{{ asset('assets/svg-line.svg') }}" alt="" class="absolute top-full left-0">
            </span>
                da Facilita!
        </x-heading>
        
        <div class="space-y-2 mb-6 mt-6">
            <x-actions.anchor href="{{ route('welcome.start') }}" variant="solid" color="success">
                @if($hasPendingAnswers)
                    Continuar de onde parou
                @else
                    Começar
                @endif
            </x-actions.anchor>

            @if($hasLatestCollection)
                <x-actions.anchor href="{{ route('test-results') }}" variant="outline" color="success">
                    Ver o resultado dos últimos testes
                </x-actions.anchor>
            @endif
        </div>

        @if($isAdmin)
            <a href="{{ route('admin.welcome') }}" class="underline text-sm text-rose-400">Voltar</a>
        @else
            <a href="{{ route('logout') }}" class="underline text-sm text-rose-400" onclick="return confirm('Você deseja mesmo sair?')">Fazer Logout / Sair</a>
        @endif
    </x-box>
</x-layouts.app>