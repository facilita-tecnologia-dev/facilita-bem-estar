<div data-role="sidebar-mobile-button" class="fixed z-20 left-4 md:left-8 top-2 cursor-pointer h-10 w-10 shadow-md rounded-full bg-gray-100 flex items-center justify-center">
    <i class="fa-solid fa-bars text-gray-800"></i>
</div>

<div id="sidebar" class="bg-gray-100 z-40 w-[280px] overflow-auto transition-all duration-200 shadow-lg h-screen flex flex-col pt-8 pb-12 absolute -left-full top-0">
    <header class="w-full flex items-center justify-center px-8 py-2">
        @if(session('company')->logo)
            <img src="{{ asset(session('company')->logo) }}" alt="" class="w-full max-h-12 object-contain">
        @else
            <h2 class="text-lg font-semibold text-left">{{ session('company')->name }}</h2>
        @endif
    </header>

    <div class="flex-1 px-4 py-8 flex flex-col gap-6">
        <x-sidebar.menu title="Home">
            @if(Auth::guard('user')->check())
                <x-sidebar.item href="{{ route('welcome.user') }}" class="">
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    Home
                </x-sidebar.item>
            @elseif (Auth::guard('company')->check())
                <x-sidebar.item href="{{ route('welcome.company') }}" class="">
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    Home
                </x-sidebar.item>
            @endif

        </x-sidebar.menu>

        @canany(['psychosocial-dashboard-view', 'organizational-dashboard-view', 'feedbacks-index', 'metrics-edit', 'demographics-dashboard-view'])
            <x-sidebar.menu title="Dados">
                @can('psychosocial-dashboard-view')
                    <x-sidebar.item href="{{ route('dashboard.psychosocial') }}" class="{{ request()->routeIs('dashboard.psychosocial') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        Riscos Psicossociais
                    </x-sidebar.item>
                @endcan
            
                @can('organizational-dashboard-view')
                    <x-sidebar.item href="{{ route('dashboard.organizational-climate') }}" class="{{ request()->routeIs('dashboard.organizational-climate') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                        Clima Organizacional
                    </x-sidebar.item>
                @endcan
            
                @can('feedbacks-index')
                    <x-sidebar.item href="{{ route('feedbacks.index') }}" class="{{ request()->routeIs('feedbacks.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        Lista de comentários
                    </x-sidebar.item>
                @endcan
                    
                @can('metrics-edit')
                    <x-sidebar.item href="{{ route('company-metrics') }}" class="{{ request()->routeIs('company-metrics') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                        Dados de Desempenho Organizacional
                    </x-sidebar.item>
                @endcan

                @can('demographics-dashboard-view')
                    <x-sidebar.item href="{{ route('dashboard.demographics') }}">
                        <div class="w-5 flex justify-center items-center" class="{{ request()->routeIs('dashboard.demographics') ? 'bg-gray-200' : ''}}">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        Demografia
                    </x-sidebar.item>
                @endcan
            </x-sidebar.menu>
        @endcanany
            
        @canany(['campaign-index', 'answer-psychosocial-test', 'answer-organizational-test', 'custom-collections-index'])
            <x-sidebar.menu title="Testes">
                @can('campaign-index')
                    <x-sidebar.item href="{{ route('campaign.index') }}" class="{{ request()->routeIs('campaign.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        Campanhas
                    </x-sidebar.item>
                @endcan
                @can('custom-collections-index')
                    <x-sidebar.item href="{{ route('custom-collections.index') }}" class="{{ request()->routeIs('custom-collections.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-book"></i>
                        </div>
                        Formulários de Pesquisa
                    </x-sidebar.item>
                @endcan
                @can('answer-psychosocial-test')
                    @if($activePsychosocialCampaign)
                        <x-sidebar.item href="{{ route('responder-teste', $activePsychosocialCampaign->collection) }}" class="{{ $hasAnsweredPsychosocial  ? 'pointer-events-none opacity-50' : '' }}">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Riscos Psicossociais
                        </x-sidebar.item>
                    @endif
                @endcan
                @can('answer-organizational-test')
                    @if($activeOrganizationalCampaign)
                        <x-sidebar.item href="{{ route('responder-teste', $activeOrganizationalCampaign->collection) }}" class="{{ $hasAnsweredOrganizational  ? 'pointer-events-none opacity-50' : '' }}">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Clima Organizacional
                        </x-sidebar.item>
                    @endif
                @endcan
            </x-sidebar.menu>
        @endcanany

        @canany(['company-show', 'user-index'])
            <x-sidebar.menu title="Empresa">
                @can('company-show')
                    <x-sidebar.item href="{{ route('company.show', session('company')) }}" class="{{ request()->routeIs('company.show') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        Empresa
                    </x-sidebar.item>
                @endcan
                @can('user-index')
                    <x-sidebar.item href="{{ route('user.index') }}" class="{{ request()->routeIs('user.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        Colaboradores
                    </x-sidebar.item>
                @endcan
                @can('action-plan-edit')
                    <x-sidebar.item href="{{ route('action-plan.show', App\Models\ActionPlan::firstWhere('company_id', session('company')->id)) }}" class="{{ request()->routeIs('action-plan.show') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-list-ul"></i>
                        </div>
                        Plano de Ação
                    </x-sidebar.item>
                @endcan
            </x-sidebar.menu>
        @endcanany

        @can('documentation-show')
            <x-sidebar.menu title="Documentação">
                <x-sidebar.item href="{{ asset('files/criterios-para-avaliação-de-riscos-psicossociais.pdf') }}" target="_blank">
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    Classificação de Riscos
                </x-sidebar.item>
                <x-sidebar.item href="{{ route('privacy-policy') }}">
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    LGPD
                </x-sidebar.item>
            </x-sidebar.menu>
        @endcan
        
        <x-sidebar.menu title="Logout">
            <x-sidebar.item href="{{ route('logout') }}" onclick="return confirm('Você deseja mesmo sair?')">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                Logout
            </x-sidebar.item>
        </x-sidebar.menu>
        
        <x-modal.background data-role="logout-modal">
            <x-modal.wrapper class="max-w-[450px]">
                <x-modal.title>Fazer logout</x-modal.title>

                <div class="w-full flex flex-col gap-2">
                    <p class="text-center">Deseja fazer logout do sistema?</p>
                    <x-action href="{{ route('logout') }}" variant="danger" width="full" data-role="logout-modal-trigger">Fazer logout</x-action>
                </div>
            </x-modal.wrapper>
        </x-modal.background>

        @can('switch-companies')
            <x-sidebar.menu title="Login em outra empresa">
                <x-sidebar.switch-company :companies="$companiesToSwitch" />
            </x-sidebar.menu>
        @endcan
    </div>

    @if(Auth::guard('user')->check())
        <footer class="px-4 py-2 flex flex-col gap-2">
            <a href="{{ route('user.show', App\Helpers\AuthGuardHelper::user()) }}" class="px-2 py-1.5 flex items-center gap-2 justify-start hover:bg-gray-200 transition rounded-md">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span class="flex-1 truncate">{{ Auth::guard('user')->user()->name }}</span>
            </a>
        </footer>
    @endif
</div>


{{-- LGPD Bar --}}
<div data-role="lgpd-bar" class="hidden fixed z-30 bg-gray-100 bottom-0 left-0 w-full shadow-md py-4 px-4 md:px-8 items-center flex-col md:flex-row justify-between gap-4">
    <p class="text-sm sm:text-base text-center md:text-left">Ao utilizar este sistema, você declara estar ciente e de acordo com a nossa <a href="{{ route('privacy-policy') }}" class="underline text-blue-600">Política de Privacidade</a>.</p>
    <x-action tag="button" variant="secondary">Confirmar</x-action>
</div>