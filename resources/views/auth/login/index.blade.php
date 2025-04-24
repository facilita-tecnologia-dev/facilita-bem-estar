<x-layouts.app>

        <div class="flex h-full justify-center">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">

                    <div class="w-full flex flex-col items-center gap-4">
                        <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                        <div class="flex flex-col gap-2 items-center">
                            <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Login</h1>
                            <p class="text-base text-center text-gray-800">Faça login para responder à pesquisa.</p>
                        </div>
                    </div>

                    {{-- <div class="relative w-fit bg-gray-200 rounded p-1 shadow-inner grid grid-cols-2 gap-2">
                        <div data-role="toggle-bg" class="absolute w-[calc(50%-8px)] h-9 bg-gray-100 rounded-md left-1 top-1 transition-all"></div>
                        <button data-role="toggle-login-form" id="internal" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Gestor/Colaborador</button>
                        <button data-role="toggle-login-form" id="external" class="relative w-full px-2 h-9 rounded-md whitespace-nowrap">Profissional de Saúde</button>
                    </div> --}}

                    <x-form action="{{ route('auth.login.internal') }}" id="login-internal" class="w-full flex flex-col gap-4 items-center" post>
                        <x-form.input-text name="cpf" placeholder="CPF apenas números" />
                        <x-action tag="button" type="submit" variant="secondary">Fazer login</x-action>
                    </x-form>

                    {{-- External --}}
                    {{-- <x-form action="{{ route('auth.login.external') }}" id="login-external" class="hidden w-full flex-col gap-4 items-center" post>
                        <x-form.input-text name="cpf" placeholder="CPF apenas números" />
                        <x-form.input-text type="password" name="password" placeholder="Senha" />
                        <x-action tag="button" type="submit">Fazer login</x-action>
                    </x-form> --}}

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

                    {{-- <x-action href="{{ route('presentation') }}" variant="secondary">Voltar para a Home</x-action> --}}
                </div>
            </div>
        </div>

</x-layouts.app>
{{-- 
<script>
    const toggleLoginFormButtons = document.querySelectorAll('[data-role="toggle-login-form"]');

    toggleLoginFormButtons.forEach(button => {
        button.addEventListener('click', function(){
            const bgElement = button.parentElement.querySelector('[data-role="toggle-bg"]');

            if(this.id == 'external'){
                bgElement.classList.replace('left-1', 'left-[calc(50%+4px)]')
                document.querySelector('#login-internal').style.display = 'none';
                document.querySelector('#login-external').style.display = 'flex';
            } else{
                bgElement.classList.replace('left-[calc(50%+4px)]', 'left-1')
                document.querySelector('#login-external').style.display = 'none';
                document.querySelector('#login-internal').style.display = 'flex';
            }
        });
    });
</script> --}}