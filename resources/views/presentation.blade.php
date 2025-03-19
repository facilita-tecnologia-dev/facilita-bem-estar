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

    @vite('resources/css/app.css')
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-Cbhb992i.css') }}"> --}}
    <title>Bem Estar | Facilita</title>
</head>
<body class="bg-teal-50">
    <header class="max-w-[1440px] mx-auto flex justify-between items-center p-5">

        <img src="{{ asset('assets/logo-facilita.svg') }}" class="h-11 sm:h-14">

        <a href="{{ route('auth.initial') }}" class="sm:text-base font-medium bg-teal-700 text-gray-100 px-7 tracking-wider  py-1.5 rounded-md">Login</a>

    </header>

    <section class="flex flex-col items-center gap-8 px-4 py-12 sm:py-14">

        <div class="flex flex-col gap-2 items-center w-fit">

            <p class="text-xl md:text-[22px] lg:text-2xl font-medium text-gray-500 text-center w-fit">Apresentamos o</p>

            <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-semibold text-center w-fit">Facilita 

                <span class="relative text-teal-400">
                    Bem-estar
                    <img src="{{ asset('assets/svg-line.svg') }}" alt="" class="absolute top-full left-0 w-full">
                </span>

            </h1>

        </div>

        <p class="max-w-[900px] w-full text-base md:text-lg lg:text-xl leading-relaxed text-center">A NR-1 e a ISO 45003 são essenciais para a saúde mental no trabalho. Nosso sistema de avaliação do bem-estar dos trabalhadores ajuda empresas a cumprirem essas normas, promovendo um ambiente saudável e produtivo. Ao adotar nossa plataforma, você investe na saúde mental dos colaboradores, reduzindo afastamentos e aumentando a produtividade.</p>
    
    </section>
    

    <section class="w-full bg-teal-700 px-4 py-12 sm:py-14 flex justify-center">
        <div class="flex flex-col-reverse items-center lg:flex-row lg:items-start gap-8 lg:gap-12 max-w-[1180px]">

            <img src="{{ asset('assets/dashboard-svg.svg') }}" class="min-w-[300px] w-2/5">

            <div class="flex-1">
                <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-semibold text-center text-white w-fit mb-4">
                    Nossa solução
                </h1>
                <p class="text-white text-base font-regular leading-6 mb-3">
                    Para garantir um ambiente de trabalho saudável, é fundamental que as organizações adotem medidas preventivas e corretivas. Nosso sistema de gestão envolve:
                </p>
                <ul class="list-disc space-y-3">
                    <li class="text-white text-base font-regular leading-6 ml-4"><strong>Avaliação de riscos:</strong> Identifica e mapeia os riscos psicossociais existentes no ambiente de trabalho.</li>
                    <li class="text-white text-base font-regular leading-6 ml-4"><strong>Estratégias de prevenção:</strong> Cria programas de suporte psicológico, treinamento para lideranças e políticas que incentivem um ambiente de respeito e inclusão.</li>
                    <li class="text-white text-base font-regular leading-6 ml-4"><strong>Monitoramento e melhoria contínua:</strong> Implementa mecanismos para avaliar regularmente as condições de trabalho e ajustar as políticas conforme necessário.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="flex flex-col items-center gap-8 px-4 py-12 sm:py-14">

        <div class="flex flex-col gap-2 items-center w-fit">

            <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-semibold text-center w-fit">
                Como funciona?
            </h1>

        </div>

        <ul class="max-w-[900px] w-full flex flex-col gap-4 md:gap-6 lg:gap-10">
            <li class="flex items-start gap-4">
                <p class="text-teal-700 text-3xl md:text-4xl lg:text-[3.5rem] font-extrabold leading-normal lg:leading-none">01</p>
                <p class="text-left text-base md:text-lg lg:text-xl leading-relaxed">Os colaboradores participam de avaliações de bem-estar para fornecer insights sobre sua saúde e bem-estar no trabalho.</p>
            </li>
            <li class="flex items-start gap-4">
                <p class="text-teal-700 text-3xl md:text-4xl lg:text-[3.5rem] font-extrabold leading-normal lg:leading-none">02</p>
                <p class="text-left text-base md:text-lg lg:text-xl leading-relaxed">Os resultados são compartilhados individualmente com cada colaborador, promovendo autoconhecimento e engajamento.</p>
            </li>
            <li class="flex items-start gap-4">
                <p class="text-teal-700 text-3xl md:text-4xl lg:text-[3.5rem] font-extrabold leading-normal lg:leading-none">03</p>
                <p class="text-left text-base md:text-lg lg:text-xl leading-relaxed">Os gestores acessam dados agregados por meio de dashboards, gráficos e filtros, ajudando na tomada de decisões baseadas em evidências.</p>
            </li>
            <li class="flex items-start gap-4">
                <p class="text-teal-700 text-3xl md:text-4xl lg:text-[3.5rem] font-extrabold leading-normal lg:leading-none">04</p>
                <p class="text-left text-base md:text-lg lg:text-xl leading-relaxed">A partir dos dados é possível estabelecer medidas de prevenção, promovendo maior engajamento e fortalecendo uma cultura organizacional que visa a saúde e bem-estar dos colaboradores.</p>
            </li>
        </ul>   
    
    </section>


    <section class="flex flex-col items-center gap-8 px-4 py-12 sm:py-14">

        <div class="flex flex-col gap-2 items-center w-fit">


            <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-semibold text-center w-fit">
                Por que escolher o Facilita 

                <span class="relative text-teal-400">
                    Bem-estar
                    <img src="{{ asset('assets/svg-line.svg') }}" alt="" class="absolute top-full left-0 w-full">
                </span>

            </h1>

        </div>

        <div class="max-w-[900px] w-full flex flex-col gap-4 md:gap-6 lg:gap-8">

            <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                Ao <span class="font-bold text-teal-400">promover o bem-estar mental</span>, as empresas podem <span class="font-bold text-teal-400">reduzir os custos</span> associados a afastamentos por problemas de saúde mental.
            </p>
            
            <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                Em 2024, o Brasil registrou um <span class="font-bold">número recorde de afastamentos</span> por problemas de saúde mental, com mais de 470 mil casos. 
            </p>
            
            <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                Esse número representa um <span class="font-bold ">aumento de 68% em relação ao ano anterior</span> e é o maior registrado em pelo menos uma década.
            </p>
            
            <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                Um ambiente de trabalho que prioriza o bem-estar mental cria um clima organizacional mais <span class="font-bold text-teal-400">positivo</span>. 
            </p>
            
            <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                Isso melhora a <span class="font-bold text-teal-400">colaboração</span> entre equipes e aumenta a <span class="font-bold text-teal-400">satisfação</span> no trabalho, o que é essencial para o <span class="font-bold text-teal-400">sucesso a longo prazo</span>.
            </p>

        </div>
    </section>

    <footer class="flex items-center justify-center px-4 py-12 sm:py-14">
        <p class="text-base text-gray-800 font-regular text-center">© 2025 Facilita Tecnologia. Todos os direitos reservados.</p>
    </footer>
</body>
</html>