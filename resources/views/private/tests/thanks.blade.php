<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container class="items-center justify-center">
            <x-structure.page-title title="Obrigado por responder ao teste!" centered />
            
            <div class="flex flex-col items-center gap-6">
                <img src="{{ asset('assets/thanks.svg') }}" class="block max-w-32 sm:max-w-56">
                <div class="space-y-2 text-center">
                    <p>Sua resposta é muito importante para o crescimento da empresa, obrigado.</p>
                    <p>Por favor, clique no botão abaixo para sair</p>
                </div>

                <x-action href="{{ route('logout') }}" onclick="return confirm('Você deseja mesmo sair?')">
                    Sair do sistema
                </x-action>

                <div class="w-full text-center">
                    <p class="text-xs sm:text-sm">Este sistema assegura o anonimato dos usuários e realiza o tratamento de dados de forma ética e responsável, em total conformidade com a LGPD.</p>
                </div>
            </div>
            
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>