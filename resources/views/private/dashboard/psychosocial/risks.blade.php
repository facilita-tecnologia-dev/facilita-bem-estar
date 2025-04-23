<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Riscos Psicossociais</h2>
            </div>

            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        Resultado detalhado da avaliação de riscos psicossociais que necessitam intervenção.
                    </p>
                </div>
                
                <a href="{{ route('dashboard.psychosocial-risks.pdf') }}" class="whitespace-nowrap py-2 px-4 bg-gray-100 rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
                    Visualizar Inventário de Riscos
                </a>
            </div>
    
            <div class="w-full space-y-8">
                @foreach ($risks as $testName => $test)
                    @if(count($test) > 0)
                        <div class="space-y-4">
                            <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                                <h2 class="text-xl font-semibold">{{ $testName }}</h2>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @foreach ($test as $key => $risk)
                                    <div class="flex flex-col gap-4 bg-white/25 w-full p-4 rounded-md shadow-md relative left-0 top-0 hover:left-0.5 hover:-top-0.5 transition-all">
                                        <div class="flex items-center justify-between bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md
                                            {{ $risk['score'] == 2.0 ? 'to-[#faed5d50]' : '' }}
                                            {{ $risk['score'] == 1.0 ? 'to-[#76fc7150]' : '' }}
                                            {{ $risk['score'] == 3.0 ? 'to-[#fc6f6f50]' : '' }}
                                        ">
                                            <p class="truncate">{{ $key }}</p>
                                            <p class="truncate">{{ $risk['risk'] }}</p>
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
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>