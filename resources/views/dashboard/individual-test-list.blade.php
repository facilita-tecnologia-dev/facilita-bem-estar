<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
               {{ $testName }}
            </h1>

            <div class="text-center">
                <p>Lista de pessoas em cada severidade no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
            </div>

            <div class="w-full flex flex-col gap-3">
                <x-form class="w-full flex items-center gap-2 justify-end">
                    <div class="flex items-center gap-1.5 px-2 border border-teal-700 rounded-md h-8 w-56 overflow-hidden">
                        <input type="text" name="search" class="w-full h-full focus:outline-none" placeholder="Pesquise...">
                        <i class="fa-solid fa-magnifying-glass text-teal-700"></i>
                    </div>
                    <select name="severidade" class="border border-teal-700 rounded-md h-8 w-56 px-2">
                        <option value="" selected>Todas</option>
                        @foreach ($severities as $severityName => $severityColor)
                            <option value="{{ $severityName }}">{{ $severityName }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="flex items-center gap-2 bg-teal-700 rounded-md h-8 px-6 text-white">
                        Filtrar
                        <i class="fa-solid fa-filter text-sm"></i>
                    </button>
                </x-form>

                <x-table id="tests-list">
                    <x-table.head>
                        <x-table.head.tr>
                            <x-table.head.th onclick="reorderTable(event, 0)">
                                Nome
                            </x-table.head.th>
                            <x-table.head.th onclick="reorderTable(event, 1)">
                                Idade
                            </x-table.head.th>
                            <x-table.head.th onclick="reorderTable(event, 2)">
                                Setor
                            </x-table.head.th>
                            <x-table.head.th onclick="reorderTable(event, 3)">
                                Total de Pontos
                            </x-table.head.th>
                            <x-table.head.th onclick="reorderTable(event, 4)">
                                {{ $testName }}
                            </x-table.head.th>
                        </x-table.head.tr>
                    </x-table.head>
                    <x-table.body class="block overflow-auto h-[365px] scrollbar-hide">
                        @foreach ($testStatsList as $testStats)
                            <x-table.body.tr :noBorder="$loop->last" anchor href="{{ route('user.info', $testStats['userId']) }}">
                                <x-table.body.td>
                                    {{ $testStats['name'] }}
                                </x-table.body.td>
                                <x-table.body.td>
                                    {{ $testStats['age'] }}
                                </x-table.body.td>
                                <x-table.body.td>
                                    {{ $testStats['occupation'] }}
                                </x-table.body.td>
                                <x-table.body.td>
                                    {{ $testStats['testTotalPoints'] }}
                                </x-table.body.td>
                                <x-table.body.td :severityColor="$testStats['testSeverityColor']" noBorder>
                                    {{ $testStats['testSeverityTitle'] }}
                                </x-table.body.td>
                            </x-table.body.tr>
                        @endforeach
                    </x-table.body>
                </x-table>
            </div>
        </div>
    </main>
</x-layouts.app>

<script>

    const tableHeaders = Array.from(document.querySelectorAll('[data-role="th"]'));
    
    let direcao = [true, true, true, true, true]; 

    function reorderTable(event, column) {
        // console.log(event.target);

        let tabela = document.getElementById("tests-list");
        let body = document.querySelector('[data-role="tbody"]'); 
        let rows = Array.from(body.querySelectorAll('[data-role="tr"]'));
        
        let ordemAscendente = direcao[column];
        direcao[column] = !ordemAscendente;


        rows.sort((a, b) => {
            let cellsA = a.querySelectorAll('[data-role="td"]');
            let cellsB = b.querySelectorAll('[data-role="td"]');

            let cellA = cellsA[column].innerText;
            let cellB = cellsB[column].innerText;

            if (!isNaN(cellA) && !isNaN(cellB)) {
                return ordemAscendente ? cellA - cellB : cellB - cellA;
            } else {
                return ordemAscendente
                    ? cellA.localeCompare(cellB)
                    : cellB.localeCompare(cellA);
            }
        });
    
        rows.forEach(row => body.appendChild(row));

        tableHeaders.forEach((item) => {
            const icon = item.querySelector('i');
            if(icon){
                icon.remove();
            }
        })

        event.target.innerHTML += `<i class="fa-solid fa-arrows-up-down ml-2"></i>`
    }
</script>