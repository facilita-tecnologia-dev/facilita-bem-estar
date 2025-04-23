<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
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

                {{-- <x-form>
                    <x-form.select name="test" label="Tipo de teste" value=""  :options="[1,2]" defaultValue />
                </x-form> --}}

                
            </div>
    
            <div class="w-full space-y-8">
                @foreach ($organizationalClimateResults as $testName => $test)
                    <div class="space-y-4">
                        <div class="bg-gray-100 px-4 py-2 w-full rounded-md shadow-md">
                            <h2 class="text-xl font-semibold">{{ $testName }}</h2>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            @foreach ($test as $questionStatement => $question)
                                <div class="px-4 py-2">
                                    <div class="flex items-center justify-between bg-gray-100 gap-4 px-4 py-2 w-full rounded-md shadow-md">
                                        <p class="truncate font-semibold">{{ $questionStatement }}</p>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        @foreach ($question as $departmentName => $department)
                                            <div class="flex items-center justify-between bg-gradient-to-b from-[#FFFFFF25] gap-4 px-4 py-2 w-full rounded-md shadow-md
                                                {{ $department['total_average'] > 80 ? 'to-[#76fc7150]' : '' }}
                                                {{ $department['total_average'] > 60 && $department['total_average'] <= 80  ? 'to-[#8cf8c050]' : '' }}
                                                {{ $department['total_average'] > 40 && $department['total_average'] <= 60 ? 'to-[#faed5d50]' : '' }}
                                                {{ $department['total_average'] > 20 && $department['total_average'] <= 40 ? 'to-[#febc5350]' : '' }}
                                                {{ $department['total_average'] <= 20 ? 'to-[#fc6f6f50]' : '' }}
                                            ">
                                                <p class="truncate">{{ $departmentName }}</p>
                                                <p>{{ number_format($department['total_average'], 0) }}%</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/dashboard/organizational/organizational-answers.js') }}"></script>
</x-layouts.app>