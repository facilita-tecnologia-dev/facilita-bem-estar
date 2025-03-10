<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                Bem vindo(a) aos Testes de 
                <span class="relative text-teal-400">
                    Bem-estar
                    <img src="assets/svg-line.svg" alt="" class="absolute top-full left-0">
                </span>
                 da Facilita!
            </h1>
            
            <a href="{{ route('test', 1) }}" class="bg-teal-700 rounded-md px-4 py-1 text-base font-medium text-teal-50 text-center hover:bg-teal-400 transition">
                Começar
            </a>
        </div>
    </main>
</x-layouts.app>