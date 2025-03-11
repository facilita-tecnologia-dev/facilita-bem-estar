<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700 text-left">
                {{ $userInfo['Nome'] }}
            </h1>

            <div class="text-left">
                <p class="text-xl font-medium">Informações do usuário</p>
            </div>

            <div class="w-full grid grid-cols-3 gap-3">
                @foreach ($userInfo as $userInfoName => $userInfoData)
                    <div class="flex items-center gap-1 flex-nowrap truncate whitespace-nowrap">
                        <span>{{ $userInfoName }}:</span>
                        <span class="font-bold">{{ $userInfoData }}</span>
                    </div>
                @endforeach
            </div>


            {{-- @dump($userInfo); --}}

        </div>
    </main>
</x-layouts.app>