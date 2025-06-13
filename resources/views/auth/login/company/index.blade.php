<x-layouts.app>

        <div class="flex h-full justify-center">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">

                    <div class="flex flex-col gap-4 items-center">
                        <h1 class="text-4xl md:text-5xl font-semibold text-center text-gray-800">Login</h1>
                        <x-structure.text-content>Faça login com a conta da sua empresa no Facilita Saúde Mental</x-structure.text-content>
                    </div>

                    <x-form action="{{ route('auth.login.empresa') }}" class="w-full flex flex-col gap-3 items-center" post>
                        @if(session('message'))
                            <span class="text-green-500 text-sm text-center">{{ session('message') }}</span>
                        @endif
                        <x-form.input-text name="cnpj" placeholder="CNPJ (00.000.000/0000-00)" />
                        <x-form.input-text type="password" name="password" placeholder="Senha" />
                        <x-action tag="button" type="submit" variant="secondary" width="full">Login</x-action>
                    </x-form>

                    <div class="w-full flex justify-between gap-2">
                        <a href="{{ route('apresentacao') }}" class="text-sm underline">Voltar para a Home</a>
                        <a href="{{ route('company.password.request') }}" class="text-sm underline">Esqueci minha senha</a>
                    </div>
                </div>
            </div>
        </div>

</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>