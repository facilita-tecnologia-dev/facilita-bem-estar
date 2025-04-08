<x-layouts.app>

        <div class="flex h-full justify-end">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">

                    <div class="flex flex-col gap-4 items-center">
                        <h1 class="text-4xl md:text-5xl font-semibold text-center text-gray-800">Crie um usuário</h1>
                        <x-text-content>Para registrar uma empresa, você precisa criar um usuário administrador.</x-text-content>
                    </div>

                    <x-form action="{{ route('company.store') }}" class="w-full flex flex-col gap-3 items-center" post>
                        {{-- <x-form.input-text name="name" placeholder="Razão Social" label="Razão Social" />
                        <x-form.input-text name="cnpj" placeholder="CNPJ apenas números" label="CNPJ apenas números" /> --}}
                        <input type="hidden" name="company-name" value="">
                        <input type="hidden" name="company-cnpj">
                        <x-form.input-text name="cpf" placeholder="Digite o CPF" label="CPF apenas números (Conta administrador)" />
                        <x-form.input-text name="password" placeholder="Digite a senha" label="Senha (Conta administrador)" />
                        {{-- <x-form.input-text type="password" name="password" placeholder="Senha" /> --}}
                        <x-action tag="button">Registrar</x-action>
                    </x-form>

                    {{-- <div class="w-full px-6 md:px-8 flex flex-col gap-3 items-center">
                        <div class="w-full flex items-center gap-4">
                            <div class="flex-1 h-[1px] bg-gray-800"></div>
                            <span class="text-base font-semibold text-gray-800 leading-tight">ou</span>
                            <div class="flex-1 h-[1px] bg-gray-800"></div>
                        </div>
                        <x-action width="full">
                            <i class="fa-brands fa-google"></i> 
                            Fazer login com Google
                        </x-action>
                    </div> --}}

                    <x-action href="{{ route('presentation') }}" variant="simple">Voltar para a Home</x-action>
                </div>
            </div>
        </div>

</x-layouts.app>