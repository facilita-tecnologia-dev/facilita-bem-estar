@props([
    'testQuestions',
    'pendingAnswers',
])

<div class="flex flex-col items-center gap-3 lg:gap-4">
    @foreach ($testQuestions->toArray() as $key => $question)
        @php
            $questionNumber = $key + 1;
            $pendingAnswer = $pendingAnswers[$key]->value ?? '';
        @endphp

        <p class="text-center text-sm sm:text-base font-normal text-teal-700">{{ $questionNumber }}. {{ $question['statement'] }}</p>
        
        <div class="w-full border border-zinc-700 rounded-md">
            @foreach ($question['question_options'] as $optionNumber => $option)
                <x-test.option :option="$option" questionNumber="{{ $questionNumber }}" optionNumber="{{ $optionNumber }}" :pendingAnswer="$pendingAnswer" />
            @endforeach
        </div>
        
        @error("question_$questionNumber")
            <span class="bg-rose-400 text-white text-base py-0.5 px-2 rounded-md">A questão {{ $questionNumber }} deve ser respondida</span>
        @enderror
    @endforeach
</div>