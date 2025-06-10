<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Riscos Psicossociais" :back="route('dashboard.psychosocial')" />

            <div class="w-full flex flex-col md:flex-row gap-4">
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    Resultado detalhado da avaliação de riscos psicossociais que necessitam intervenção.
                </x-structure.message>

                <div class="w-full md:w-fit">
                    <x-action href="{{ route('dashboard.psychosocial.risks.pdf') }}" width="full">
                        Visualizar Inventário de Riscos
                    </x-action>
                </div>
            </div>
    
            <div class="w-full space-y-8">
                @foreach ($riskResults as $testName => $test)
                    @if(count($test['risks']) > 0)
                        <div class="space-y-4">
                            <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                                <h2 class="text-xl font-semibold">{{ $testName }}</h2>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @foreach ($test['risks'] as $riskName => $risk)
                                    <div class="flex flex-col gap-4 bg-white/25 w-full p-4 rounded-md shadow-md relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                                        <div class="flex items-center justify-between bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md
                                            {{ $risk['riskLevel'] == 2.0 ? 'to-[#faed5d50]' : '' }}
                                            {{ $risk['riskLevel'] == 1.0 ? 'to-[#76fc7150]' : '' }}
                                            {{ $risk['riskLevel'] == 3.0 ? 'to-[#dc933250]' : '' }}
                                            {{ $risk['riskLevel'] == 4.0 ? 'to-[#fc6f6f50]' : '' }}
                                        ">
                                            <p class="truncate">{{ $riskName }}</p>
                                            <p class="truncate">{{ $risk['riskCaption'] }}</p>
                                        </div>
                                        <p class="px-3 font-semibold">Medidas de Controle e Prevenção</p>
                                        <ul class="grid grid-cols-1 px-4 gap-y-3 gap-x-4 list-disc pl-5">
                                            @foreach ($risk['controlActions'] as $action)
                                                <li class="text-sm w-full rounded-md">
                                                    {{ $action->content }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>