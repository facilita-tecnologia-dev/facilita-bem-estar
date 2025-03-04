@php
    // dump($testResults)
@endphp

<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-xl min-h-2/4 shadow-md p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                Dashboard
            </h1>

            <div class="text-center">
                <p>Nome do colaborador: Júlio da Silva</p>
                <p>Função do colaborador: Operador de Prensa</p>
            </div>


            <div class="flex flex-wrap gap-5 w-full max-w-10/12 justify-center">
                
                @foreach ($testResults as $testResult)
                    @php
                        $textColor = $testResult['color'] ?? '#222'
                    @endphp

                    <x-result-card :testResult="$testResult" />
                @endforeach
            
            </div>
            {{-- <div class="grid grid-cols-2 gap-5 w-full max-w-9/12">
                
                @foreach ($testResults as $testResult)
                    <div class="bg-white border border-teal-700 flex flex-col p-4 rounded-md shadow-md w-full py-3 items-center">
                        <p class="text-md text-teal-700 text-center">{{ $testResult['testName'] }} </p>
                        <h1 class="text-md font-medium text-center flex-1 flex items-center">{{ $testResult['recommendations'][0] }}</h1>
                    </div>        
                @endforeach
            
            </div> --}}
        </div>
    </main>
</x-layouts.app>