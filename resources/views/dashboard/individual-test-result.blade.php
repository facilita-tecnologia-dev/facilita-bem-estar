@dump($testStats)
<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                {{ $testName }}
            </h1>

            <div class="text-center">
                <p>Listagem de setores no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
            </div>

            
            <div class="grid grid-cols-5 gap-4 w-full justify-center items-start">
                @foreach ($testStats as $occupationName => $testSeverity)
                        <x-table>
                            <x-table.thead>
                                <x-table.th>{{ $occupationName }}</x-table.th>
                            </x-table.thead>
                            @foreach ($testSeverity['severities'] as $severityName => $testsQty )
                                <div class="
                                    w-full flex items-center justify-between
                                    @if(!$loop->last) border-b border-zinc-300 @endif
                                ">
                                
                                    <x-table.td>{{ $severityName }}</x-table.td>
                                    <x-table.td severity_color="{{ $testsQty['severity_color'] }}">
                                        {{ round($testsQty['count'] / $testSeverity['total'] * 100) }}%
                                    </x-table.td>
                                
                                </div>
                            @endforeach
                        </x-table>
                @endforeach
            </div>

        </div>
    </main>
</x-layouts.app>