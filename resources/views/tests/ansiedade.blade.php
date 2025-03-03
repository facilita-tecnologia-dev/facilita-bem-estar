@php
    @dump(session()->all());

    $questions = [
        'Sentir-se nervoso, ansioso ou muito tenso',
        'Não ser capaz de impedir ou controlar as preocupações', 
        'Preocupar-se muito com diversas coisas', 
        'Dificuldade para relaxar',
        'Ficar tão agitado que se torna difícil permanecer sentado',
        'Ficar facilmente aborrecido ou irritado',
        'Sentir medo como se algo terrível fosse acontecer'
    ]
@endphp

<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            <div class="flex flex-col items-center">
                <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700 max-w-2/3 text-center">
                    GAD-7 - Ansiedade
                </h1>
                <p class="text-lg font-medium text-center text-teal-700">Nas últimas 2 semanas, com que frequência você foi incomodado pelos problemas abaixo?</p>
            </div>
            <form action="{{ route('test.submit', 'ansiedade')}}" id="test-form" class="flex-1 overflow-auto w-full space-y-5" method="post">
                @csrf

                <input type="hidden" name="next_test" value="depressao">


                @foreach ($questions as $key => $question)
                    @php
                        $questionNumber = $key + 1
                    @endphp

                    <div class="flex flex-col items-center gap-4">
                        <p class="text-center text-md font-normal text-teal-700">{{ $key + 1 }}. {{ $question }}</p>

                        <div class="w-full border border-zinc-700 rounded-md">
                            <label for="question_{{ $questionNumber }}_0" class="border-b border-b-zinc-300 bg-transparent px-4 py-1.5 rounded-t-md flex items-center gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
                                <input type="radio" name="question_{{ $questionNumber }}" id="question_{{$questionNumber}}_0" value="0" {{ old('question_'.($key + 1)) == '0' ? 'checked' : '' }} class="w-3.5 h-3 rounded-full accent-current">
                                Nunca
                            </label>
                        
                            <label for="question_{{ $questionNumber }}_1" class="border-b border-b-zinc-300 bg-transparent px-4 py-1.5 flex items-center gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
                                <input type="radio" name="question_{{ $questionNumber }}" id="question_{{$questionNumber}}_1" value="1" {{ old('question_'.($key + 1)) == '1' ? 'checked' : '' }} class="w-3.5 h-3 rounded-full accent-current">
                                Alguns dias
                            </label>
                        
                            <label for="question_{{ $questionNumber }}_2" class="border-b border-b-zinc-300 bg-transparent px-4 py-1.5 flex items-center gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
                                <input type="radio" name="question_{{ $questionNumber }}" id="question_{{$questionNumber}}_2" value="2" {{ old('question_'.($key + 1)) == '2' ? 'checked' : '' }} class="w-3.5 h-3 rounded-full accent-current">
                                Mais da metade dos dias
                            </label>
                        
                            <label for="question_{{ $questionNumber }}_3" class="bg-transparent px-4 py-1.5 rounded-b-md flex items-center gap-2 cursor-pointer hover:bg-teal-100 transition has-[:checked]:text-teal-700">
                                <input type="radio" name="question_{{ $questionNumber }}" id="question_{{$questionNumber}}_3" value="3" {{ old('question_'.($key + 1)) == '3' ? 'checked' : '' }} class="w-3.5 h-3 rounded-full accent-current">
                                Quase todos os dias
                            </label>
                        </div>

                        @error("question_$questionNumber")
                            <span class="text-rose-300 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
                    
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
        

    </main>
</x-layouts.app>