<x-layouts.app>

        <div class="flex h-full justify-center">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">

                    <div class="flex flex-col gap-4 items-center">
                        <h1 class="text-4xl md:text-5xl font-semibold text-center text-gray-800">Registro</h1>
                        <x-structure.text-content>Registre sua empresa no Facilita Saúde Mental</x-structure.text-content>
                    </div>

                    <x-form action="{{ route('auth.cadastro.empresa') }}" class="w-full flex flex-col gap-3 items-center" post>
                        <x-form.input-text name="name" placeholder="Razão Social" />
                        <x-form.input-text name="cnpj" placeholder="CNPJ (00.000.000/0000-00)" />
                        <x-form.input-text type="password" name="password" placeholder="Senha" />
                        <x-form.input-text type="password" name="password_confirmation" placeholder="Confirme sua senha" />
                        <x-action tag="button" type="submit">Registrar</x-action>
                    </x-form>

                    <a href="{{ route('apresentacao') }}" class="text-sm underline">Voltar para a Home</a>
                </div>
            </div>
        </div>

</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>