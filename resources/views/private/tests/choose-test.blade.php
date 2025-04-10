<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-center justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-4xl text-gray-800 font-semibold text-left">Escolha o teste</h2>
            </div>
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">
                <div
                {{-- <a 
                    href="{{ route('test', 1) }}" --}}
                    style="
                        background: linear-gradient(0deg , #ffffff25 0%, #ffffff25 100%);
                    " 
                    class="opacity-40 relative overflow-hidden transition border border-white rounded-md py-6 lg:py-16 px-4 flex flex-col gap-2 lg:gap-8 items-center"
                >
                    <img src="{{ asset('assets/white-wave-1.svg') }}" alt="" class="absolute left-0 bottom-0 h-1/2 w-full object-cover object-top">
                    <h2 class="text-2xl tracking-tight font-semibold text-center text-gray-800">Saúde Mental no Trabalho</h2>
                    <p class="text-base leading-relaxed text-center text-gray-800">
                        Avalie o seu bem-estar emocional e encontre caminhos para fortalecer a saúde mental no dia a dia profissional.
                    </p>
                {{-- </a> --}}
                </div>

                <a 
                    href="{{ route('test', 1) }}"
                    style="
                        background: linear-gradient(0deg , #ffffff25 0%, #ffffff25 100%);
                    " 
                    class="overflow-hidden border border-white rounded-md py-6 lg:py-16 px-4 flex flex-col gap-2 items-center relative left-0 top-0 hover:left-1 hover:-top-1 transition-all"
                >
                    <img src="{{ asset('assets/white-wave-2.svg') }}" alt="" class="absolute left-0 bottom-0 h-1/2 w-full object-cover object-top">
                    <h2 class="text-2xl tracking-tight font-semibold text-center text-gray-800">Riscos Psicossociais</h2>
                    <p class="text-base leading-relaxed text-center text-gray-800">
                        Identifique quais fatores podem gerar estresse em sua rotina de trabalho e descubra formas de manter o equilíbrio.
                    </p>
                </a>

                <div
                {{-- <a 
                    href="{{ route('test') }}" --}}
                    style="
                        background: linear-gradient(0deg , #ffffff25 0%, #ffffff25 100%);
                    " 
                    class="opacity-40 overflow-hidden border border-white rounded-md py-6 lg:py-16 px-4 flex flex-col gap-2 items-center transition relative"
                >
                    <img src="{{ asset('assets/white-wave-3.svg') }}" alt="" class="absolute left-0 bottom-0 h-1/2 w-full object-cover object-top">
                    <h2 class="text-2xl tracking-tight font-semibold text-center text-gray-800">Clima organizacional</h2>
                    <p class="text-base leading-relaxed text-center text-gray-800">
                        Entenda como você percebe a cultura e práticas da empresa, contribuindo ativamente para um ambiente mais saudável e produtivo.
                    </p>
                {{-- </a> --}}
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>