<x-layouts.app>

    <div class="flex h-full justify-center">
        <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
            <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
      
                <div class="w-full flex flex-col items-center gap-4">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                    <div class="flex flex-col gap-2 items-center">
                        @if(str_starts_with($user->password, 'temp_') && strlen($user->password) == 37)
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Digite sua senha provisória</h1>
                            <p class="text-base text-center text-gray-800">Entre com sua senha provisória para redefinir a sua senha.</p>
                        @else
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Digite sua senha</h1>
                            <p class="text-base text-center text-gray-800">Identificamos que você é um gestor, por isso, digite sua senha.</p>
                        @endif
                    </div>
                </div>

                <x-form action="{{ route('auth.login.usuario-interno.verificar-senha', $user) }}" class="w-full flex flex-col gap-4 items-center" post>
                    <x-form.input-text type="password" name="password" placeholder="Digite sua senha" />
                    <x-action tag="button" type="submit" variant="secondary" width="full">Fazer login</x-action>
                </x-form>

                <a href="{{ route('apresentacao') }}" class="text-sm underline">Voltar para a Home</a>
            </div>
        </div>
    </div>

</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>