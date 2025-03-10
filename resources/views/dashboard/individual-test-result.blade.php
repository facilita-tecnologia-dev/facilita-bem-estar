
<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                {{ array_keys($testStats)[0] }}
            </h1>

            <div class="text-center">
                <p>Lista de pessoas em cada severidade no teste de <span class="font-bold text-teal-700">{{ array_keys($testStats)[0] }}</span></p>
            </div>

            
            <div class="flex gap-4 items-start w-full justify-center">
                @foreach ($testStats as $testName => $testStat)
                    @foreach ($testStat as $severityColorKey => $severityData)
                        <x-individual-test-card href="{{ route('test-results-list.dashboard', $testName) }}?severidade={{ $severityData['severityName'] }}" :severityName="$severityData['severityName']" :severityColor="$severityColorKey">
                            <div class="flex flex-col w-full">
                                @foreach ($severityData['users'] as $username)
                                    <p class="w-full py-1.5 px-3 ">{{ $username }}</p>
                                @endforeach
                            </div>
                        </x-individual-test-card>
                    @endforeach
                @endforeach
            </div>

        </div>
    </main>
</x-layouts.app>