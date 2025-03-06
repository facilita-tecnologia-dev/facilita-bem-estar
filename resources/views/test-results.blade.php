{{-- @dump($testResults) --}}
<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                Resultado dos testes
            </h1>

            <div class="text-center">
                <p>Nome do colaborador: Júlio da Silva</p>
                <p>Função do colaborador: Operador de Prensa</p>
            </div>


            <div class="grid grid-cols-6 gap-5 w-full max-w-10/12 justify-center">            
                @foreach ($testResults as $testResult)
                    <x-result-card :testResult="$testResult" />
                @endforeach
            
            </div>
        </div>
    </main>
</x-layouts.app>