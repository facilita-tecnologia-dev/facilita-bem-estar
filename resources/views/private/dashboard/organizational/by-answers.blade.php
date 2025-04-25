<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-structure.sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Divisão por respostas</h2>
            </div>

            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Resultado detalhado das respostas de Clima Organizacional.
                    </p>
                </div>
            </div>
    
            @foreach ($organizationalClimateResults as $testName => $test)
                <x-table>
                    <x-table.head class="flex items-center gap-3">
                        <x-table.head.th  class="flex-1">{{ $testName }}</x-table.head.th>

                        @foreach ($test[array_keys($test)[0]] as $departmentName => $department)
                            <x-table.head.th  class="flex-1 truncate max-w-14 lg:max-w-24 {{ $departmentName != 'Geral' ? 'hidden lg:block' : '' }}">
                                {{ $departmentName }}
                            </x-table.head.th>
                        @endforeach
                    </x-table.head>
                    <x-table.body>
                        @foreach ($test as $questionStatement => $question)
                            <x-table.body.tr  class="flex items-center gap-3">
                                <x-table.body.td class=" flex-1" title="{{ $questionStatement }}">{{ $questionStatement }}</x-table.body.td>

                                @foreach ($question as $departmentName => $department)
                                    <x-table.body.td class="flex-1 max-w-14 lg:max-w-24 {{ $departmentName != 'Geral' ? 'hidden lg:block' : '' }}
                                        {{ $department['total_average'] <= 75 ? 'text-[#ca3232] italic' : '' }}
                                    ">
                                        {{ number_format($department['total_average'], 0) }}%
                                    </x-table.body.td>
                                @endforeach
                            </x-table.body.tr>
                        @endforeach
                    </x-table.body>
                </x-table>
            @endforeach

            {{-- <div class="w-full space-y-8">
                @foreach ($organizationalClimateResults as $testName => $test)
                    <div class="space-y-4">
                        <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                            <h2 class="text-xl font-semibold">{{ $testName }}</h2>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-4 gap-y-8">
                            @foreach ($test as $questionStatement => $question)
                                <x-table>
                                    <x-table.head class="flex items-center justify-between gap-4">
                                        <x-table.head.th class="truncate" title="{{ $questionStatement }}">{{ $questionStatement }}</x-table.head.th>
                                    </x-table.head>
                                    <x-table.body>
                                        @foreach ($question as $departmentName => $department)
                                            <x-table.body.tr class="
                                                flex items-center justify-between gap-4
                                                {{ $department['total_average'] > 80 ? 'to-[#76fc7150]' : '' }}
                                                {{ $department['total_average'] > 60 && $department['total_average'] <= 80  ? 'to-[#8cf8c050]' : '' }}
                                                {{ $department['total_average'] > 40 && $department['total_average'] <= 60 ? 'to-[#faed5d50]' : '' }}
                                                {{ $department['total_average'] > 20 && $department['total_average'] <= 40 ? 'to-[#febc5350]' : '' }}
                                                {{ $department['total_average'] <= 20 ? 'to-[#fc6f6f50]' : '' }}
                                            ">
                                                <x-table.body.td class="truncate">{{ $departmentName }}</x-table.body.td>
                                                <x-table.body.td>{{ number_format($department['total_average'], 0) }}%</x-table.body.td>
                                            </x-table.body.tr>
                                        @endforeach
                                    </x-table.body>
                                </x-table>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div> --}}
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/dashboard/organizational/organizational-answers.js') }}"></script>
</x-layouts.app>