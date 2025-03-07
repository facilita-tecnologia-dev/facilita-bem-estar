{{-- @dump($testStatsList) --}}
<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
               {{ $testName }}
            </h1>

            <div class="text-center">
                <p>Lista de pessoas em cada severidade no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
            </div>

            <div class="border border-gray-300 rounded-md">
                <div class="grid grid-cols-5 bg-teal-700 p-2 rounded-t-md">
                    <span class="px-2 text-center text-white">Nome</span>
                    <span class="px-2 text-center text-white">Idade</span>
                    <span class="px-2 text-center text-white">Cargo</span>
                    <span class="px-2 text-center text-white">Total de Pontos</span>
                    <span class="px-2 text-center text-white">Severidade</span>
                </div>
                @foreach ($testStatsList as $testStats)
                    <a href="" class="grid grid-cols-5 p-2 hover:bg-gray-200 transition">
                        <span class="px-2 py-1 text-sm text-center">{{ $testStats['name'] }}</span>
                        <span class="px-2 py-1 text-sm text-center">{{ $testStats['age'] }}</span>
                        <span class="px-2 py-1 text-sm text-center">{{ $testStats['occupation'] }}</span>
                        <span class="px-2 py-1 text-sm text-center">{{ $testStats['testTotalPoints'] }}</span>
                        <span class="px-2 py-1 text-sm text-center">{{ $testStats['testSeverityTitle'] }}</span>
                    </a>
                @endforeach
            </div>

        </div>
    </main>
</x-layouts.app>