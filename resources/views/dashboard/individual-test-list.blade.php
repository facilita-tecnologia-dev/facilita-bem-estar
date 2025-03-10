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

            <x-form>
                <input type="text" name="search" class="border border-teal-700 rounded-md">
            </x-form>

            <x-table>
                <x-table.thead>
                    <x-table.th>Nome</x-table.th>
                    <x-table.th>Idade</x-table.th>
                    <x-table.th>Cargo</x-table.th>
                    <x-table.th>Total de Pontos</x-table.th>
                    <x-table.th>Severidade</x-table.th>
                </x-table.thead>
                @foreach ($testStatsList as $testStats)
                    <x-table.tr>
                        <x-table.td>{{ $testStats['name'] }}</x-table.td>
                        <x-table.td>{{ $testStats['age'] }}</x-table.td>
                        <x-table.td>{{ $testStats['occupation'] }}</x-table.td>
                        <x-table.td>{{ $testStats['testTotalPoints'] }}</x-table.td>
                        <x-table.td :severityColor="$testStats['testSeverityColor']">{{ $testStats['testSeverityTitle'] }}</x-table.td>
                    </x-table.tr>
                @endforeach
            </x-table>

        </div>
    </main>
</x-layouts.app>