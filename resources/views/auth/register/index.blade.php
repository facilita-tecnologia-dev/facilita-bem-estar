<x-layouts.app>

        <div class="flex h-full justify-end">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <div class="w-full flex flex-col items-center gap-4">
                        <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                        <div class="flex flex-col gap-2 items-center">
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Registre-se</h1>
                            {{-- <p class="text-base text-center text-gray-800">Para se registrar como profissional de saúde, cadastre as suas credenciais nos campos abaixo.</p> --}}
                        </div>
                    </div>

                    <div class="relative w-fit bg-gray-200 rounded p-1 shadow-inner grid grid-cols-2 gap-2">
                        <div data-role="toggle-bg" class="absolute w-[calc(50%-8px)] h-9 bg-gray-100 rounded-md left-1 top-1 transition-all"></div>
                        <button data-role="toggle-register-form" id="internal" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Empresa</button>
                        <button data-role="toggle-register-form" id="external" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Profissional de Saúde</button>
                    </div>

                    <x-form action="{{ route('auth.register.internal.user') }}" id="register-internal" class="w-full flex flex-col gap-3 items-center" post>
                        <x-form.input-text name="name" placeholder="Digite o seu nome completo" />
                        <x-form.input-text name="cpf" placeholder="Digite o CPF" />
                        <x-action tag="button" type="submit">Registrar</x-action>
                    </x-form>

                    {{-- External --}}
                    <x-form action="{{ route('auth.register.external') }}" id="register-external" class="hidden w-full flex-col gap-3 items-center" post>
                        <x-form.input-text name="name" placeholder="Digite o seu nome completo" />
                        <x-form.input-text name="cpf" placeholder="Digite o CPF" />
                        <x-form.input-text type="password" name="password" placeholder="Digite a senha" />
                        <x-form.input-text type="password" name="password_confirmation" placeholder="Confirme a sua senha" />
                        <x-action tag="button" type="submit">Registrar</x-action>
                    </x-form>

                    <x-action href="{{ route('presentation') }}" variant="simple">Voltar para a Home</x-action>
                </div>
            </div>
        </div>

</x-layouts.app>

<script>
    const toggleLoginFormButtons = document.querySelectorAll('[data-role="toggle-register-form"]');

    toggleLoginFormButtons.forEach(button => {
        button.addEventListener('click', function(){
            const bgElement = button.parentElement.querySelector('[data-role="toggle-bg"]');

            if(this.id == 'external'){
                bgElement.classList.replace('left-1', 'left-[calc(50%+4px)]')
                document.querySelector('#register-internal').style.display = 'none';
                document.querySelector('#register-external').style.display = 'flex';
            } else{
                bgElement.classList.replace('left-[calc(50%+4px)]', 'left-1')
                document.querySelector('#register-external').style.display = 'none';
                document.querySelector('#register-internal').style.display = 'flex';
            }
        });
    });
</script>