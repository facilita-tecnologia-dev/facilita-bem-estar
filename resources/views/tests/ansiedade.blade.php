{{-- @php
    @dump(session()->all(), $testInfo);
@endphp --}}

<x-layouts.app>
    <main class="w-screen h-screen flex flex-col gap-5 items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            <div class="flex flex-col items-center">
                <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                    1 - Ansiedade
                </h1>
                <p class="text-lg font-medium text-center text-teal-700">Nas últimas 2 semanas, com que frequência você foi incomodado pelos problemas abaixo?</p>
            </div>
            <form action="{{ route('test.submit', 'ansiedade')}}" id="test-form" class="flex-1 overflow-auto w-full space-y-5" method="post">
                @csrf

                <div class="flex flex-col items-center gap-4">
                    <x-questions-list :testInfo="$testInfo"  />
                </div>
            </form>
            <div class="flex items-center justify-between w-full">
                <a href="{{ route('welcome') }}" class="px-4 py-1 bg-transparent border border-rose-700 rounded-md text-md font-medium text-rose-700 hover:bg-rose-700 hover:text-white transition">
                    Cancelar
                </a>
                <button form="test-form" type="submit" class="px-4 py-1 bg-teal-700 cursor-pointer rounded-md text-md font-medium text-teal-50 hover:bg-teal-400 transition">
                    Próximo teste
                </button>
            </div>
        </div>
        <footer>
            <p class="text-xs text-zinc-800">Baseado no GAD-7 (Generalized Anxiety Disorder-7) [Spitzer et al., 2006]</p>
        </footer>
    </main>

</x-layouts.app>