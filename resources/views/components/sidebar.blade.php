<div data-role="sidebar-mobile-button" class="md:hidden fixed left-3 top-3 cursor-pointer h-10 w-10 shadow-md rounded-full bg-gray-100 flex items-center justify-center">
    <i class="fa-solid fa-bars text-gray-800"></i>
</div>

<div id="sidebar" class="bg-gray-100 z-40 w-[280px] transition-all duration-200 md:w-[240px] shadow-lg h-screen flex flex-col py-8 absolute -left-full top-0 md:relative md:left-0">
    <div class="flex items-center justify-between px-4 py-2">
        <img src="{{ asset('assets/icon-facilita.svg') }}" alt="" class="w-10">
    </div>

    <div class="flex-1 px-4 py-8 flex flex-col gap-6">
        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Dados</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                Dashboard
            </a>
        </div>

        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Testes</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-question"></i>
                </div>
                Realizar testes
            </a>
        </div>

        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Empresa</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-users"></i>
                </div>
                Colaboradores
            </a>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-building"></i>
                </div>
                Empresa
            </a>
        </div>

        <div class="submenu space-y-3">
            <p class="uppercase text-xs font-semibold px-2">Saúde</p>
            <a href="" class="px-2 py-1.5 rounded-md flex items-center gap-2 justify-start hover:bg-gray-200 transition">
                <div class="w-5 flex justify-center items-center">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                Buscar consulta
            </a>
        </div>
    </div>

    <div class="px-4 py-8">
        <div class="px-2 py-1.5 flex items-center gap-2 justify-start">
            <i class="fa-solid fa-user"></i>
            João da silva
        </div>
    </div>
</div>