<div data-role="sidebar-mobile-button" class="fixed z-20 left-8 top-2 cursor-pointer h-10 w-10 shadow-md rounded-full bg-gray-100 flex items-center justify-center">
    <i class="fa-solid fa-bars text-gray-800"></i>
</div>

<div id="sidebar" class="bg-gray-100 z-40 w-[280px] overflow-auto transition-all duration-200  shadow-lg h-screen flex flex-col pt-8 pb-12 absolute -left-full top-0">
    <div class="flex items-center justify-center px-4 py-2">
        @if(session('company')->logo)
            <img src="{{ asset(session('company')->logo) }}" alt="" class="h-10">
        @else
            <h2 class="text-lg font-semibold text-left">{{ session('company')->name }}</h2>
        @endif
    </div>

    <div class="flex-1 px-4 py-8 flex flex-col gap-6">
        @can('view-manager-screens', Auth::user())
            <div class="submenu space-y-3">
                <p class="uppercase text-xs font-semibold px-2">Dados</p>

                <a 
                    href="{{ route('dashboard.psychosocial') }}" 
                    class="{{ Route::currentRouteName() == 'dashboard.psychosocial' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                >
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    Riscos Psicossociais
                </a>
                <a 
                    href="{{ route('dashboard.organizational-climate') }}" 
                    class="{{ Route::currentRouteName() == 'dashboard.organizational-climate' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                >
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                    Clima Organizacional
                </a>
                <a 
                    href="{{ route('dashboard.demographics') }}" 
                    class="{{ Route::currentRouteName() == 'dashboard.demographics' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                >
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
                <a 
                    href="{{ route('choose-test') }}" 
                    class="{{ Route::currentRouteName() == 'choose-test' || Route::currentRouteName() == 'test' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                >
                    <div class="w-5 flex justify-center items-center">
                        <i class="fa-solid fa-question"></i>
                    </div>
                    Realizar testes
                </a>
            </div>
        @endcan

        @can('view-manager-screens')
            <div class="submenu space-y-3">
                <p class="uppercase text-xs font-semibold px-2">Empresa</p>
                @can('viewAny', Auth::user())
                    <a 
                        href="{{ route('user.index') }}" 
                        class="{{ Route::currentRouteName() == 'user.index' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                    >
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        Colaboradores
                    </a>
                @endcan
                @can('view', session('company'))
                    <a 
                        href="{{ route('company.show', session('company')) }}" 
                        class="{{ Route::currentRouteName() == 'company.show' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                    >
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        Empresa
                    </a>
                @endcan
                @can('update-metrics')
                    <a 
                        href="{{ route('company-metrics') }}" 
                        class="{{ Route::currentRouteName() == 'company-metrics' ? 'bg-gray-200' : ''}} px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition"
                    >
                        <div class="w-5 flex justify-center items-center">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                        Indicadores
                    </a>
                @endcan
            </div>
        @endcan

        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Saúde</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                Buscar consulta
            </a>
        </div>

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

    <div class="px-4 py-2">
        <div class="px-2 py-1.5 flex items-center gap-2 justify-start">
            <div class="w-5 flex justify-center items-center">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="flex-1 truncate">{{ Auth::user()->name }}</span>
        </div>
    </div>
</div>