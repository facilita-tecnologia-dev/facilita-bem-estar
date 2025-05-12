<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Bem vindo(a)!" />

            <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                    <i class="fa-regular fa-heart text-lg"></i>
                    Obrigado por escolher a Facilita! Estamos felizes em tê-lo conosco. Agora você tem acesso a uma plataforma completa para cuidar da saúde e bem-estar da sua equipe.
                </p>
            </div>
            <div class="w-full py-4 md:py-8 space-y-8 flex flex-col items-center mt-6">
                <div class="text-center space-y-2">
                    <h2 class="text-base md:text-2xl font-medium">Vamos deixar tudo pronto?</h2>
                    <p class="text-sm md:text-base">Para começar a usar a plataforma da melhor forma possível, complete o perfil da sua empresa. Aqui estão os próximos passos:</p>
                </div>

                @php
                    $totalActions = 3;
                    $neededActionsCount = 0;

                    if(!session('company')->logo){$neededActionsCount++;}
                    if(!session('company')->users->count()){$neededActionsCount++;}
                    if(!session('company')->metrics()->where('value', '>', 0)->exists()){$neededActionsCount++;}
                @endphp
                @if($neededActionsCount)
                    <div class="w-full max-w-96 grid grid-cols-3 gap-2 rounded-md bg-gray-100/50 shadow-md p-4">
                        <p class="col-span-3 text-sm text-right">
                            {{ $neededActionsCount }} ações recomendadas
                        </p>

                        @for($i = 0; $i < ($totalActions - $neededActionsCount); $i++)
                            <div class="rounded-md h-1.5 bg-green-500"></div>
                        @endfor

                        @for($i = 0; $i < $neededActionsCount; $i++)
                            <div class="rounded-md h-1.5 bg-gray-100 border border-gray-800"></div>
                        @endfor
                        
                    </div>
                @endif
            
                @if(!session('company')->logo)
                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                        <span class="text-sm md:text-base flex items-center gap-3">
                            <i class="fa-solid fa-fingerprint text-lg"></i>
                            Adicione o logotipo da sua empresa
                        </span>
                        <x-action variant="secondary" :href="route('company.edit', session('company'))">Editar perfil da empresa</x-action>
                    </div>
                @endif
                @if(!session('company')->users->count())
                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                        <span class="text-sm md:text-base flex items-center gap-3">
                            <i class="fa-solid fa-users text-lg"></i>
                            Importe ou registre colaboradores — assim você poderá começar a aplicar testes, acompanhar dados e gerar insights personalizados.
                        </span>
                        <x-action variant="secondary" :href="route('user.import')">Importar colaboradores</x-action>
                    </div>
                @endif
                @if(!session('company')->metrics()->where('value', '>', 0)->exists())
                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                        <span class="text-sm md:text-base flex items-center gap-3">
                            <i class="fa-solid fa-percent text-lg"></i>
                            Cadastre os Dados de Desempenho Organizacional da sua empresa nos últimos 12 meses (%)
                        </span>
                        <x-action variant="secondary" :href="route('company-metrics')">Cadastrar Dados de Desempenho</x-action>
                    </div>
                @endif
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>