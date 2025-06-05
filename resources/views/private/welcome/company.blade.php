<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Bem vindo(a)! - {{ session('company')->name }}" centered="true" />

            <x-structure.message>
                <i class="fa-regular fa-heart"></i>
                Obrigado por escolher a Facilita! Estamos felizes em tê-lo conosco. Você tem acesso a uma plataforma completa para cuidar da saúde e bem-estar da sua equipe.
            </x-structure.message>

            @if($neededActionsCount)
                <div class="w-full py-4 md:py-8 space-y-8 flex flex-col items-center mt-6">
                    <div class="text-center space-y-2">
                        <h2 class="text-base md:text-2xl font-medium">Vamos deixar tudo pronto?</h2>
                        <p class="text-sm md:text-base">Para começar a usar a plataforma da melhor forma possível, complete o perfil da sua empresa. Aqui estão os próximos passos:</p>
                    </div>
                    
                    <div class="w-full max-w-96 grid grid-cols-5 gap-2 rounded-md bg-gray-100/50 shadow-md p-4">
                        <p class="col-span-5 text-sm text-right">
                            {{ $neededActionsCount }} ações recomendadas
                        </p>

                        @for($i = 0; $i < ($totalActions - $neededActionsCount); $i++)
                            <div class="rounded-md h-1.5 bg-green-500"></div>
                        @endfor

                        @for($i = 0; $i < $neededActionsCount; $i++)
                            <div class="rounded-md h-1.5 bg-gray-100 border border-gray-800"></div>
                        @endfor
                        
                    </div>
                
                    @if($noCompanyLogo)
                        <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-fingerprint text-lg"></i>
                                Adicione o logotipo da sua empresa
                            </span>
                            <x-action variant="secondary" :href="route('company.edit', session('company'))">Editar perfil da empresa</x-action>
                        </div>
                    @endif

                    @if($noCompanyUsers)
                        <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-users text-lg"></i>
                                Importe ou crie colaboradores — assim você poderá começar a aplicar testes, acompanhar dados e gerar insights personalizados.
                            </span>
                            <div class="flex items-center flex-wrap sm:flex-nowrap gap-2">    
                                <x-action variant="secondary" :href="route('user.import')">Importar colaboradores</x-action>
                                <x-action variant="secondary" :href="route('user.create')">Criar colaboradores</x-action>
                            </div>
                        </div>
                    @endif

                    @if($noCompanyManager)
                        <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-briefcase"></i>
                                Atribua um usuário como gestor e ajuste as permissões ou a visualização de setores, se necessário.
                            </span>
                            <div class="flex items-center flex-wrap sm:flex-nowrap gap-2">    
                                <x-action variant="secondary" :href="route('user.index')">Lista de usuários</x-action>
                            </div>
                        </div>
                    @endif
                        
                    @if($noCompanyMetrics)
                        <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-percent text-lg"></i>
                                Cadastre os Dados de Desempenho Organizacional da sua empresa nos últimos 12 meses (%)
                            </span>
                            <div>
                                <x-action variant="secondary" :href="route('company-metrics')">Cadastrar Dados de Desempenho</x-action>
                            </div>
                        </div>
                    @endif

                    @if($noCompanyCampaigns)
                        <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-4 bg-gray-100 rounded-md shadow-md p-4">
                            <span class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-calendar-days text-lg"></i>
                                Programe uma Campanha de Testes
                            </span>
                            <div>
                                <x-action variant="secondary" :href="route('campaign.create')">Programar campanha</x-action>
                            </div>
                        </div>
                    @endif
                </div>

                <button data-role="next-steps-checklist-trigger" class="bg-gray-100 px-3 py-2 border border-r-0 border-[#FF8AAF] rounded-l-md shadow-md fixed right-0 top-1/2 -translate-y-1/2">
                    <i class="fa-solid fa-list-check text-lg"></i>
                </button>

                <div id="next-steps-checklist" class="fixed -right-full top-1/2 -translate-y-1/2 p-4 bg-gray-100 rounded-l-md border border-[#FF8AAF] shadow-md w-[300px] space-y-6 transition-all duration-200">
                    <h2 class="text-base font-semibold text-center underline">Próximos passos</h2>

                    <div class="w-full grid grid-cols-5 gap-1 px-2">
                        @for($i = 0; $i < ($totalActions - $neededActionsCount); $i++)
                            <div class="h-0.5 bg-green-500"></div>
                        @endfor

                        @for($i = 0; $i < $neededActionsCount; $i++)
                            <div class="h-0.5 bg-gray-500/50"></div>
                        @endfor
                    </div>
                    <x-sidebar.menu title="Realize uma campanha de testes">
                        <x-sidebar.item :href="route('user.create')" class="">
                            <input type="checkbox" class="peer hidden" {{ ! $noCompanyUsers ? 'checked' : '' }}>
                            <label class="bg-gray-100 peer-checked:bg-green-500 border border-gray-500 w-3.5 h-3.5 rounded-sm flex items-center justify-center">
                                <i class="fa-solid fa-check text-gray-100 text-xs"></i>
                            </label>
                            <span class="text-sm flex-1">Importe/crie seus colaboradores</span>
                        </x-sidebar.item>
                        <x-sidebar.item :href="route('user.index')" class="">
                            <input type="checkbox" class="peer hidden" {{ ! $noCompanyManager ? 'checked' : '' }}>
                            <label class="bg-gray-100 peer-checked:bg-green-500 border border-gray-500 w-3.5 h-3.5 rounded-sm flex items-center justify-center">
                                <i class="fa-solid fa-check text-gray-100 text-xs"></i>
                            </label>
                            <span class="text-sm flex-1">Atribua pelo menos um gestor</span>
                        </x-sidebar.item>
                        <x-sidebar.item :href="route('company-metrics')" class="">
                            <input type="checkbox" class="peer hidden" {{ ! $noCompanyMetrics ? 'checked' : '' }}>
                            <label class="bg-gray-100 peer-checked:bg-green-500 border border-gray-500 w-3.5 h-3.5 rounded-sm flex items-center justify-center">
                                <i class="fa-solid fa-check text-gray-100 text-xs"></i>
                            </label>
                            <span class="text-sm flex-1">Cadastre os Dados de Desempenho Organizacional</span>
                        </x-sidebar.item>
                        <x-sidebar.item :href="route('campaign.create')" class="">
                            <input type="checkbox" class="peer hidden" {{ ! $noCompanyCampaigns ? 'checked' : '' }}>
                            <label class="bg-gray-100 peer-checked:bg-green-500 border border-gray-500 w-3.5 h-3.5 rounded-sm flex items-center justify-center">
                                <i class="fa-solid fa-check text-gray-100 text-xs"></i>
                            </label>
                            <span class="text-sm flex-1">Programe uma Campanha de Testes</span>
                        </x-sidebar.item>
                    </x-sidebar.menu>
                    <x-sidebar.menu title="Complete o perfil da empresa">
                        <x-sidebar.item :href="route('company.edit', session('company'))" class="">
                            <input type="checkbox" class="peer hidden" {{ ! $noCompanyLogo ? 'checked' : '' }}>
                            <label class="bg-gray-100 peer-checked:bg-green-500 border border-gray-500 w-3.5 h-3.5 rounded-sm flex items-center justify-center">
                                <i class="fa-solid fa-check text-gray-100 text-xs"></i>
                            </label>
                            <span class="text-sm flex-1">Adicione o logotipo da empresa</span>
                        </x-sidebar.item>
                    </x-sidebar.menu>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                    <a href="{{ route('dashboard.psychosocial') }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Acesse o seu dashboard de <span class="font-medium">Riscos Psicossociais</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('dashboard.organizational-climate') }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Acesse o seu dashboard de <span class="font-medium">Clima Organizacional</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('dashboard.demographics') }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Acesse o seu dashboard de <span class="font-medium">Índices Demográficos</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('campaign.index') }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Verifique sua <span class="font-medium">Lista de Campanhas</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('company.show', session('company')) }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Visualize ou atualize o <span class="font-medium">Perfil da Empresa</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('user.index') }}" class="w-full bg-gray-100 rounded-md shadow-md p-4 flex items-center gap-3 relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="flex-1 text-base">
                            Visualize a <span class="font-medium">Lista dos Colaboradores</span>
                        </span>
                        <div class="w-10 aspect-square rounded-md border border-gray-300 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </a>
                </div>
            @endif
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/company/welcome.js') }}"></script>
</x-layouts.app>