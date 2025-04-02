<x-layouts.app>
    <x-box>
        <div class="mr-auto relative md:absolute md:left-10 md:top-4 mb-4">
            <x-actions.anchor href="{{ route('admin.welcome') }}" color="success" variant="solid">
                Voltar
            </x-actions.anchor>
        </div>
        

        <x-heading>
            {{ $testName }}
        </x-heading>

        <div class="text-center mt-5 mb-5">
            <p>Lista de pessoas em cada severidade no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
        </div>

        <div class="w-full flex flex-col gap-3 h-full max-h-[400px]">
            <x-form class="w-full flex items-end gap-2 justify-end h-fit">
                <div class="hidden md:flex flex-col gap-0.5">
                    <label for="search" class="text-sm">Nome do colaborador</label>
                    <div class="flex items-center gap-1.5 px-2 border border-teal-700 rounded-md text-sm lg:text-base h-7 lg:h-8 w-40 lg:w-48 xl:w-56 overflow-hidden">
                        <input id="search" type="text" name="search" class="text-sm lg:text-base w-full h-full focus:outline-none" placeholder="Pesquise...">
                        <i class="fa-solid fa-magnifying-glass text-teal-700"></i>
                    </div>
                </div>

                <div class="hidden md:flex flex-col gap-0.5">
                    <label for="severity" class="text-sm">Severidade</label>
                    <select id="severity" name="severidade" class="text-sm lg:text-base border border-teal-700 rounded-md h-7 lg:h-8 w-40 lg:w-48 xl:w-56 px-2">
                        <option value="" selected>Todas</option>
                        @foreach ($severities as $severityName => $severityColor)
                            <option value="{{ $severityName }}">{{ $severityName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden xl:flex flex-col gap-0.5">
                    <label for="gender" class="text-sm">Sexo</label>
                    <select id="gender" name="sexo" class="text-sm lg:text-base border border-teal-700 rounded-md h-7 lg:h-8 w-40 lg:w-48 xl:w-56 px-2">
                        <option value="" selected>Todos</option>
                        @foreach ($genders as $gender)
                            <option value="{{ $gender }}">{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-0.5">
                    <label for="deparment" class="text-sm">Setor</label>
                    <select id="deparment" name="setor" class="text-sm lg:text-base border border-teal-700 rounded-md h-7 lg:h-8 w-40 lg:w-48 xl:w-56 px-2">
                        <option value="" selected>Todos</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department }}">{{ $department }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="flex items-center gap-2 bg-fuchsia-600 rounded-md text-sm lg:text-base h-7 lg:h-8 px-6 text-white">
                    Filtrar
                    <i class="fa-solid fa-filter text-xs lg:text-sm"></i>
                </button>
            </x-form>

            <x-table id="tests-list" class="flex flex-col flex-1 max-w-full overflow-auto">
                <x-table.head>
                    <x-table.head.tr>
                        <x-table.head.th onclick="reorderTable(event, 0)">
                            Nome
                        </x-table.head.th>
                        <x-table.head.th onclick="reorderTable(event, 1)" lgNone>
                            Idade
                        </x-table.head.th>
                        <x-table.head.th onclick="reorderTable(event, 1)" mdNone>
                            Sexo
                        </x-table.head.th>
                        <x-table.head.th onclick="reorderTable(event, 2)" smNone>
                            Setor
                        </x-table.head.th>
                        <x-table.head.th onclick="reorderTable(event, 3)" smNone>
                            Cargo
                        </x-table.head.th>
                        <x-table.head.th onclick="reorderTable(event, 4)">
                            Severidade
                        </x-table.head.th>
                    </x-table.head.tr>
                </x-table.head>
                <x-table.body class="block overflow-auto scrollbar-hide">
                    @foreach ($testStatsList as $testStats)
                        <x-table.body.tr :noBorder="$loop->last" anchor href="{{ route('user.info', $testStats['userId']) }}">
                            <x-table.body.td>
                                {{ $testStats['name'] }}
                            </x-table.body.td>
                            <x-table.body.td lgNone>
                                {{ $testStats['age'] }}
                            </x-table.body.td>
                            <x-table.body.td mdNone>
                                {{ $testStats['gender'] }}
                            </x-table.body.td>
                            <x-table.body.td smNone>
                                {{ $testStats['department'] }}
                            </x-table.body.td>
                            <x-table.body.td smNone>
                                {{ $testStats['occupation'] }}
                            </x-table.body.td>
                            <x-table.body.td :severityColor="$testStats['testSeverityColor']" noBorder>
                                {{ $testStats['testSeverityTitle'] }}
                            </x-table.body.td>
                        </x-table.body.tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </div>
    </x-box>
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