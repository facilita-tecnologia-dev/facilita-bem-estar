@props([
    'testQuestions',
])

<div class="flex flex-col items-center gap-4">
@foreach ($testQuestions->toArray() as $key => $question)
    @php
        $questionNumber = $key + 1
    @endphp

    <p class="text-center text-md font-normal text-teal-700">{{ $questionNumber }}. {{ $question['statement'] }}</p>
    
    <div class="w-full border border-zinc-700 rounded-md">
        @foreach ($question['question_options'] as $optionNumber => $option)
            <x-test.option :option="$option" questionNumber="{{ $questionNumber }}" optionNumber="{{ $optionNumber }}" />
        @endforeach
    </div>
    
    @error("question_$questionNumber")
        <span class="text-rose-400 text-md">{{ $message }}</span>
    @enderror
@endforeach
</div>