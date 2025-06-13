<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            @if($neededActions)
                <div class="w-full flex-1 flex flex-col gap-6 items-center justify-center">
                    <x-structure.page-title title="Vamos começar?" centered="true" />

                    <x-structure.message class="!w-fit">
                        Antes de começar a aplicar os testes, é necessário realizar algumas configurações e ações no sistema.
                    </x-structure.message>
                    
                    @if($currentStep == 1)
                        <div class="w-full max-w-[450px] flex flex-col items-center p-6 gap-6 bg-gray-100 rounded-md shadow-md">
                            <div class="w-full grid grid-cols-5 gap-1.5">
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                            </div>

                            <h2 class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-fingerprint text-lg"></i>
                                Adicione o logotipo da sua empresa
                            </h2>
                            <p class="text-sm text-center w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto odio, ullam assumenda beatae.</p>
                            <x-action variant="secondary" width="full" :href="route('company.edit', session('company'))">Editar perfil da empresa</x-action>
                        </div>
                    @endif

                    @if($currentStep == 2)
                        <div class="w-full max-w-[450px] flex flex-col items-center p-6 gap-6 bg-gray-100 rounded-md shadow-md">
                            <div class="w-full grid grid-cols-5 gap-1.5">
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                            </div>

                            <h2 class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-users text-lg"></i>
                                Importe ou crie colaboradores — assim você poderá começar a aplicar testes, acompanhar dados e gerar insights personalizados.
                            </h2>
                            <p class="text-sm text-center w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto odio, ullam assumenda beatae.</p>

                            <div class="flex items-center flex-wrap sm:flex-nowrap gap-2">    
                                <x-action variant="secondary" :href="route('user.import')">Importar colaboradores</x-action>
                                <x-action variant="secondary" :href="route('user.create')">Criar colaboradores</x-action>
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 3)
                        <div class="w-full max-w-[450px] flex flex-col items-center p-6 gap-6 bg-gray-100 rounded-md shadow-md">
                            <div class="w-full grid grid-cols-5 gap-1.5">
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                            </div>

                            <h2 class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-briefcase"></i>
                                Atribua um usuário como gestor e ajuste as permissões ou a visualização de setores, se necessário.
                            </h2>
                            <p class="text-sm text-center w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto odio, ullam assumenda beatae.</p>
                            <x-action variant="secondary" width="full" :href="route('user.index')">Lista de usuários</x-action>
                        </div>
                    @endif
                        
                    @if($currentStep == 4)
                        <div class="w-full max-w-[450px] flex flex-col items-center p-6 gap-6 bg-gray-100 rounded-md shadow-md">
                            <div class="w-full grid grid-cols-5 gap-1.5">
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-gray-400"></div>
                            </div>

                            <h2 class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-percent text-lg"></i>
                                Cadastre os Dados de Desempenho Organizacional da sua empresa nos últimos 12 meses (%)
                            </h2>
                            <p class="text-sm text-center w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto odio, ullam assumenda beatae.</p>
                            <x-action variant="secondary" width="full" :href="route('company-metrics')">Cadastrar Dados de Desempenho</x-action>
                        </div>
                    @endif

                    @if($currentStep == 5)
                        <div class="w-full max-w-[450px] flex flex-col items-center p-6 gap-6 bg-gray-100 rounded-md shadow-md">
                            <div class="w-full grid grid-cols-5 gap-1.5">
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                                <div class="rounded-md h-1.5 bg-green-500"></div>
                            </div>

                            <h2 class="text-sm md:text-base flex items-center gap-3">
                                <i class="fa-solid fa-calendar-days text-lg"></i>
                                Programe uma Campanha de Testes
                            </h2>
                            <p class="text-sm text-center w-full">Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto odio, ullam assumenda beatae.</p>
                            <x-action variant="secondary" width="full" :href="route('campaign.create')">Programar campanha</x-action>
                        </div>
                    @endif
                </div>
            @else
                <x-structure.page-title title="Bem vindo(a)! - {{ session('company')->name }}" centered="true" />

                <x-structure.message>
                    <i class="fa-regular fa-heart"></i>
                    Obrigado por escolher a Facilita! Estamos felizes em tê-lo conosco. Você tem acesso a uma plataforma completa para cuidar da saúde e bem-estar da sua equipe.
                </x-structure.message>

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