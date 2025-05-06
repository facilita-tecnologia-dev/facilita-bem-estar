<div data-role="sidebar-mobile-button" class="fixed z-20 left-4 md:left-8 top-2 cursor-pointer h-10 w-10 shadow-md rounded-full bg-gray-100 flex items-center justify-center">
    <i class="fa-solid fa-bars text-gray-800"></i>
</div>

<div id="sidebar" class="bg-gray-100 z-40 w-[280px] overflow-auto transition-all duration-200 shadow-lg h-screen flex flex-col pt-8 pb-12 absolute -left-full top-0">
    <header class="flex items-center justify-center px-4 py-2">
        @if(session('company')->logo)
            <img src="{{ asset(session('company')->logo) }}" alt="" class="h-10">
        @else
            <h2 class="text-lg font-semibold text-left">{{ session('company')->name }}</h2>
        @endif
    </header>

    <div class="flex-1 px-4 py-8 flex flex-col gap-6">
        @can('is-internal-manager', Auth::user())
            <div class="submenu space-y-3">
                <p class="uppercase text-xs font-semibold px-2">Dados</p>
                @can('access-psychosocial', Auth::user())
                    <a href="{{ route('dashboard.psychosocial') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('dashboard.psychosocial') ? 'bg-gray-200' : ''}} ">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-brain"></i>
                        </div>
                        Riscos Psicossociais
                    </a>
                @endcan

                @can('access-organizational', Auth::user())
                    <a href="{{ route('dashboard.organizational-climate') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('dashboard.organizational-climate') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-cloud"></i>
                        </div>
                        Clima Organizacional
                    </a>
                @endcan

                @can('access-organizational', Auth::user())
                    <a href="{{ route('feedbacks.index') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('feedbacks.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        Lista de comentários
                    </a>
                @endcan

                @can('access-psychosocial', Auth::user())
                    @can('update-metrics')
                        <a href="{{ route('company-metrics') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('company-metrics') ? 'bg-gray-200' : ''}}">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                            Dados de Desempenho Organizacional
                        </a>
                    @endcan
                @endcan

                
                <a href="{{ route('dashboard.demographics') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('dashboard.demographics') ? 'bg-gray-200' : ''}}">
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    Demografia
                </a>
            </div>
        @endcan
            
        @can('answer-tests')
            <div class="submenu space-y-3">
                <p class="uppercase text-xs font-semibold px-2">Testes</p>
                @can('access-psychosocial', Auth::user())
                    @if($hasAnsweredPsychosocial)
                        <div class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start opacity-20 cursor-not-allowed">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Riscos Psicossociais
                        </div>
                    @else
                        <a href="{{ route('responder-teste', App\Models\Collection::where('key_name', 'psychosocial-risks')->first()) }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Riscos Psicossociais
                        </a>
                    @endif
                @endcan

                @can('access-organizational', Auth::user())
                    @if($hasAnsweredOrganizational)
                        <div class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start opacity-20 cursor-not-allowed">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Clima Organizacional
                        </div>
                    @else
                        <a href="{{ route('responder-teste', App\Models\Collection::where('key_name', 'organizational-climate')->first()) }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                            <div class="w-5 flex justify-center items-center">
                                <i class="fa-solid fa-question"></i>
                            </div>
                            Clima Organizacional
                        </a>
                    @endif
                @endcan
            </div>
        @endcan

        @can('is-internal-manager')
            <div class="submenu space-y-3">
                <p class="uppercase text-xs font-semibold px-2">Empresa</p>

                @can('view', session('company'))
                    <a href="{{ route('company.show', session('company')) }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('company.show') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        Empresa
                    </a>
                @endcan

                @can('viewAny', Auth::user())
                    <a href="{{ route('user.index') }}" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition {{ request()->routeIs('user.index') ? 'bg-gray-200' : ''}}">
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        Colaboradores
                    </a>
                @endcan
            </div>
        @endcan

        {{-- <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Saúde</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                Buscar consulta
            </a>
        </div> --}}

        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Logout</p>
            <a href="{{ route('logout') }}" onclick="return confirm('Você deseja mesmo sair?')" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                Logout
            </a>
        </div>
    </div>

    <footer class="px-4 py-2 flex flex-col gap-2">
        {{-- <div class="flex items-center gap-2 justify-start">
            <x-form.select name="company_switch" label="Outras empresas" value="{{ session('company')->id }}"  :options="$companiesToSwitch" />
        </div> --}}
        <div class="px-2 py-1.5 flex items-center gap-2 justify-start hover:bg-gray-200 transition rounded-md">
            <div class="w-5 flex justify-center items-center">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="flex-1 truncate">{{ Auth::user()->name }}</span>
        </div>
    </footer>
</div>