<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container class="!py-0"> 
            <x-structure.page-title title="Divisão por respostas" :back="route('dashboard.organizational-climate')"/>

            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Resultado detalhado das respostas de Clima Organizacional.
                    </p>
                </div>
            </div>
            
            <div class="w-full flex flex-col gap-10">
                @foreach ($organizationalClimateResults as $testName => $test)
                    <x-table class="w-full px-1 pb-2">
                        <x-table.head class="min-w-full !w-fit flex items-center gap-6 sticky top-0 z-10">
                            <x-table.head.th class="w-[60vw] md:w-64 lg:w-80">{{ $testName }}</x-table.head.th>
    
                            @foreach ($test[array_keys($test)[0]] as $departmentName => $department)
                                <x-table.head.th  class="md:flex-1 truncate w-20 md:min-w-20">
                                    {{ $departmentName }}
                                </x-table.head.th>
                            @endforeach
                        </x-table.head>
                        <x-table.body>
                            @foreach ($test as $questionStatement => $question)
                                <x-table.body.tr class="min-w-full !w-fit flex items-center gap-6">
                                    <x-table.body.td class="w-[60vw] md:w-64 lg:w-80" title="{{ $questionStatement }}">{{ $questionStatement }}</x-table.body.td>
    
                                    @foreach ($question as $departmentName => $department)
                                        <x-table.body.td class="md:flex-1 w-20 md:min-w-20
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
            </div>
        </x-structure.main-content-container> 
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/dashboard/organizational/organizational-answers.js') }}"></script>
</x-layouts.app>