<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/1cb73192b4.js" crossorigin="anonymous"></script>

    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-C9fhWCIr.css') }}">

    <title>Facilita Saúde Mental</title>
</head>
<body class="bg-gray-100">
    <img src="{{ asset('assets/top-gradient.png') }}" class="block absolute left-0 top-0 w-full h-[700px] object-cover" alt="">
    <header class="relative max-w-[1440px] mx-auto flex flew-row justify-between items-center p-5">

        <a href="https://facilitatecnologia.com.br">
            <img src="{{ asset('assets/logo-facilita.svg') }}" class="h-11 sm:h-14">
        </a>

        <div class="items-center gap-2 hidden lg:flex">
            <a href="{{ route('auth.login.empresa') }}" class="text-base font-medium bg-gray-100 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                <i class="fa-solid fa-building"></i>
                <span class="hidden sm:block">Login como empresa</span>
            </a>
            <a href="{{ route('auth.cadastro.empresa') }}" class="text-base font-medium bg-gray-100 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                <i class="fa-solid fa-building"></i>
                <span class="hidden sm:block">Registrar empresa</span>
            </a>
            <a href="{{ route('auth.login.usuario-interno') }}" class="text-base font-medium bg-gray-100 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                <i class="fa-solid fa-user"></i>
                <span class="hidden sm:block">Login como colaborador</span>
            </a>
        </div>

        <div data-role="login-submenu-button" class="z-20 flex lg:hidden cursor-pointer h-10 w-10 shadow-md rounded-full bg-gray-100 items-center justify-center">
            <i class="fa-solid fa-bars text-gray-800"></i>
        </div>

        <div id="login-submenu" class="flex lg:hidden bg-gray-100 z-40 w-[280px] transition-all duration-200 shadow-lg h-auto rounded-bl-md flex-col absolute -right-full pt-8 pb-12 top-0">
            <div class="flex flex-col gap-4 w-full px-4">
                <h2 class="font-medium text-lg text-center">Login/Registro</h2>
                <a href="{{ route('auth.login.empresa') }}" class="text-base font-medium bg-gray-200/75 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                    <i class="fa-solid fa-building"></i>
                    <span class="">Login como empresa</span>
                </a>
                <a href="{{ route('auth.cadastro.empresa') }}" class="text-base font-medium bg-gray-200/75 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                    <i class="fa-solid fa-building"></i>
                    <span class="">Registrar empresa</span>
                </a>
                <a href="{{ route('auth.login.usuario-interno') }}" class="text-base font-medium bg-gray-200/75 text-gray-800 px-4 tracking-wider  py-1.5 rounded-md flex items-center gap-2">
                    <i class="fa-solid fa-user"></i>
                    <span class="">Login como colaborador</span>
                </a>
            </div>
        </div>

    </header>

    <x-structure.section class="relative">
        <div class="flex flex-col gap-8 md:gap-12 items-center">
            <div class="flex flex-col gap-2 items-center">
                <x-structure.heading tag="h1" >Facilita Saúde Mental</x-structure.heading>
                <x-structure.text-content>seu parceiro que facilita a gestão da <span class="underline">saúde mental</span> e de <span class="underline">riscos psicossociais</span> no trabalho.</x-structure.text-content>
            </div>
            <img src="{{ asset('assets/mockup-saude-mental.png') }}" class="w-full max-w-[470px] h-fit object-contain" alt="">
        </div>
    </x-structure.section>

    <x-structure.section class="relative w-full max-w-[900px] mx-auto">
        <div class="w-full flex gap-8 lg:gap-12 items-center flex-col-reverse lg:flex-row lg:justify-between">
            <img src="{{ asset('assets/double-semi-circles.svg') }}" class="w-full max-w-[320px] lg:w-2/5 object-contain" alt="">

            <div class="flex flex-col gap-6 w-full max-w-[500px]">
                <div class="flex flex-col gap-2 items-center lg:items-start">
                    <h2 class="w-fit tracking-tight text-4xl lg:text-4xl font-semibold leading-12 text-gray-800 text-center lg:text-left">
                        Análise via indicadores subjetivos e objetivos
                    </h2>
                    <p class="text-gray-800 text-base lg:text-lg font-normal leading-relaxed text-center lg:text-left">Verificado por corpo técnico (RH, SESMT e Psicólogo) apoiada em 3 pilares:</p>
                </div>
                <ul class="list-disc pl-4">
                    <li>
                        <x-structure.text-content alignment="left" >Saúde Mental no Trabalho.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Riscos Psicossociais.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Clima organizacional.</x-structure.text-content>
                    </li>
                </ul>
            </div>
        </div>
    </x-structure.section>

    <x-structure.section class="relative w-full max-w-[900px] mx-auto">
        <div class="w-full flex gap-8 lg:gap-12 items-center flex-col-reverse lg:flex-row-reverse lg:justify-between">
            <img src="{{ asset('assets/horizontal-semi-circle.svg') }}" class="w-full max-w-[320px] lg:w-2/5 object-contain" alt="">

            <div class="flex flex-col gap-6 w-full max-w-[500px]">
                <div class="flex flex-col gap-2 items-center lg:items-start">
                    <h2 class="w-fit tracking-tight text-4xl lg:text-4xl lg:text-[3.5rem] font-semibold leading-12 text-gray-800 text-center lg:text-left">
                        Resultado
                    </h2>
                    <p class="text-gray-800 text-base lg:text-lg font-normal leading-relaxed text-center lg:text-left">Mostra a tendência dos riscos psicossociais:</p>
                </div>
                <ul class="list-disc pl-4">
                    <li>
                        <x-structure.text-content alignment="left" >Riscos relacionados à organização do trabalho.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Riscos associados às relações interpessoais.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Riscos relacionados às condições de emprego.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Riscos relacionados à conteúdo e significado do trabalho.</x-structure.text-content>
                    </li>
                    <li>
                        <x-structure.text-content alignment="left" >Riscos de equilíbrio entre vida pessoal e profissional.</x-structure.text-content>
                    </li>
                </ul>
            </div>
        </div>
    </x-structure.section>

    <x-structure.section class="w-full max-w-[1180px] mx-auto">
        <div class="w-full flex flex-col gap-8 md:gap-12 items-center">
            <div class="flex flex-col gap-2 items-center">
                <x-structure.heading>Método</x-structure.heading>
                {{-- <x-structure.text-content>Plano de ação da pesquisa, será dividida de 5 tipos de intervenções:</x-structure.text-content> --}}
            </div>
            
            <div class="w-full flex flex-col gap-6 md:gap-8">
                <div
                    style="
                        background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
                    "
                    class="w-full rounded-md p-6 flex flex-col items-start gap-4"
                >
                    <p class="text-xl md:text-2xl font-semibold text-left">1. Organização do trabalho</p>
                    {{-- <ul class="list-disc pl-4">
                        <li>
                            <x-structure.text-content alignment="left">Definição clara de funções de responsabilidades.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Flexibilidade.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Pausas e jornadas equilibradas.</x-structure.text-content>
                        </li>
                    </ul> --}}
                </div>
                <div
                    style="
                        background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
                    "
                    class="w-full rounded-md p-6 flex flex-col items-start gap-4"
                >
                    <p class="text-xl md:text-2xl font-semibold text-left">2. Desenvolvimento de lideranças</p>
                    {{-- <ul class="list-disc pl-4">
                        <li>
                            <x-structure.text-content alignment="left">Capacitação de gestores.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Comunicação.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Liderança participativa.</x-structure.text-content>
                        </li>
                    </ul> --}}
                </div>
                <div
                    style="
                        background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
                    "
                    class="w-full rounded-md p-6 flex flex-col items-start gap-4"
                >
                    <p class="text-xl md:text-2xl font-semibold text-left">3. Cultura organizacional</p>
                    {{-- <ul class="list-disc pl-4">
                        <li>
                            <x-structure.text-content alignment="left">Reconhecimento e valorização.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Ambiente respeitoso e seguro.</x-structure.text-content>
                        </li>
                        <li>
                            <x-structure.text-content alignment="left">Políticas contra assédio e violência.</x-structure.text-content>
                        </li>
                    </ul> --}}
                </div>
                <div
                    style="
                        background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
                    "
                    class="w-full rounded-md p-6 flex flex-col items-start gap-4"
                >
                    <p class="text-xl md:text-2xl font-semibold text-left">4. Bem-estar</p>
                    {{-- <ul class="list-disc pl-4">
                        <li>
                            <x-structure.text-content alignment="left">Adesão de atividades de bem estar.</x-structure.text-content>
                        </li>
                    </ul> --}}
                </div>
                <div
                    style="
                        background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
                    "
                    class="w-full rounded-md p-6 flex flex-col items-start gap-4"
                >
                    <p class="text-xl md:text-2xl font-semibold text-left">5. Psicológica</p>
                    {{-- <ul class="list-disc pl-4">
                        <li>
                            <x-structure.text-content alignment="left">Análise profissional</x-structure.text-content>
                        </li>
                    </ul> --}}
                </div>
            </div>
        </div>
    </x-structure.section>

    <x-structure.section
        style="
            background: linear-gradient(135deg, #CCB0F830 0%, #E8C6E030 50%, #DDCBE130 100%);
        "
        class="w-full rounded-t-3xl">
        <div class="w-full flex flex-col gap-8 md:gap-12 items-center">
            <div class="flex flex-col gap-2 items-center">
                <x-structure.heading>Mais ferramentas</x-structure.heading>
                <x-structure.text-content>que vão ajudar você a promover a saúde mental e gerenciar riscos psicossociais no trabalho.</x-structure.text-content>
            </div>

            <div class="flex flex-col items-center gap-4">
                <p class="text-xl md:text-2xl font-semibold text-center">Canal de denúncia anônima.</p>
                <div class="h-[2px] w-full max-w-[500px] bg-gray-800/50"></div>
                <p class="text-xl md:text-2xl font-semibold text-center">Canal de atendimento de profissionais de saúde.</p>
                <div class="h-[2px] w-full max-w-[500px] bg-gray-800/50"></div>
                <p class="text-xl md:text-2xl font-semibold text-center">Monitoramento / acompanhamento de ações.</p>
                <div class="h-[2px] w-full max-w-[500px] bg-gray-800/50"></div>
                <p class="text-xl md:text-2xl font-semibold text-center">Identificação do número de atendimentos realizados.</p>
                <div class="h-[2px] w-full max-w-[500px] bg-gray-800/50"></div>
                <p class="text-xl md:text-2xl font-semibold text-center">Apontamento de dados de treinamentos para gestores.</p>
                <div class="h-[2px] w-full max-w-[500px] bg-gray-800/50"></div>
                <p class="text-xl md:text-2xl font-semibold text-center">Dashboards editáveis com filtros parametrizáveis.</p>
            </div>

            <div class="mt-4">
                <x-structure.text-content>© 2025 Facilita Tecnologia. Todos os direitos reservados.</x-structure.text-content>
            </div>
        </div>
    </x-structure.section>

    {{-- <footer class="flex items-center justify-center px-4 py-12 sm:py-14">
        <p class="text-base text-gray-800 font-regular text-center">© 2025 Facilita Tecnologia. Todos os direitos reservados.</p>
    </footer> --}}
    
    <script src="{{ asset('js/presentation.js') }}"></script>
</body>
</html>