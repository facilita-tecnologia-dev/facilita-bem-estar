<x-layouts.app>

        <div class="flex h-full justify-center">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">

                    <div class="w-full flex flex-col items-center gap-4">
                        <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                        <div class="flex flex-col gap-2 items-center">
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Login</h1>
                            <p class="text-base text-center text-gray-800">Faça login como gestor ou colaborador.</p>
                        </div>
                    </div>

                    <x-form action="{{ route('auth.login.usuario-interno') }}" class="w-full flex flex-col gap-4 items-center" post>
                        <x-form.input-text name="cpf" placeholder="CPF (000.000.000-00)" />
                        <x-action tag="button" type="submit" variant="secondary" width="full">Fazer login</x-action>
                    </x-form>

                    <a href="{{ route('apresentacao') }}" class="text-sm underline">Voltar para a Home</a>
                </div>
            </div>
        </div>

</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>