@props([
    'testInfo',
])


@foreach ($testInfo['questions'] as $key => $question)
    @php
        $questionNumber = $key + 1
    @endphp

    <p class="text-center text-md font-normal text-teal-700">{{ $questionNumber }}. {{ $question['statement'] }}</p>
    
    <div class="w-full border border-zinc-700 rounded-md">
        @foreach ($question['options'] as $optionNumber => $option)
            <x-option :option="$option" questionNumber="{{ $questionNumber }}" optionNumber="{{ $optionNumber }}" />
        @endforeach

        @error("question_$questionNumber")
            <span class="text-rose-400 text-sm">{{ $message }}</span>
        @enderror
    </div>
@endforeach
