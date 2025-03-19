<x-layouts.app>
    <x-box class="w-full">
        <div class="mr-auto relative md:absolute md:left-10 md:top-4 mb-4">
            <x-actions.anchor href="{{ route('admin.welcome') }}" color="success" variant="solid">
                Voltar
            </x-actions.anchor>
        </div>
        

        <x-heading>
            {{ $testName }}
        </x-heading>

        <div class="text-center mt-4 mb-4">
            <p>Listagem de setores no teste de <span class="font-bold text-teal-700">{{ $testName }}</span></p>
        </div>

        
        <div class="flex flex-wrap gap-[1%] gap-y-3 overflow-auto justify-start items-start w-full">
            @foreach ($testStats as $occupationName => $testSeverity)
                    {{-- @dump($testStats) --}}

                    <x-table class="w-[100%] min-[575px]:w-[49%] md:w-[32%] lg:w-[24%]">
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

    </x-box>
</x-layouts.app>