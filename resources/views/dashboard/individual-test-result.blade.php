<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                {{ $testName }}
            </h1>

            <div class="text-center">
                <p>Listagem de setores no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
            </div>

            
            <div class="flex flex-wrap gap-3 overflow-auto justify-center items-start">
                @foreach ($testStats as $occupationName => $testSeverity)
                        <x-table class="w-1/5">
                            <a href="{{ route('test-results-list.dashboard', $testName)}}">
                                <x-table.head>
                                    <x-table.head.tr flex>
                                        <x-table.head.th noBorder>
                                            {{ $occupationName }}
                                        </x-table.head.th>
                                    </x-table.head.tr>
                                </x-table.head>
                                <x-table.body>
                                    @foreach ($testSeverity['severities'] as $severityName => $testsQty )
                                        <x-table.body.tr :noBorder="$loop->last" flex>
                                            <x-table.body.td noBorder>
                                                {{ $severityName }}
                                            </x-table.body.td>
                                            <x-table.body.td :severityColor="$testsQty['severity_color']" noBorder>
                                                {{ round($testsQty['count'] / $testSeverity['total'] * 100) }}%
                                            </x-table.body.td>
                                        </x-table.body.tr>
                                    @endforeach
                                </x-table.body>
                            </a>
                        </x-table>
                @endforeach
            </div>

        </div>
    </main>
</x-layouts.app>