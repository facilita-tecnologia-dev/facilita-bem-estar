<x-layouts.app>

    <div class="flex h-full justify-center">
        <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
            <div class="w-full max-w-[400px] flex flex-col items-center gap-8">

                <div class="w-full flex flex-col items-center gap-4">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">
                    <div class="flex flex-col gap-2 items-center">
                        <h1 class="text-3xl md:text-4xl font-semibold text-center text-gray-800">Esqueci minha senha</h1>
                        <p class="text-base text-center text-gray-800">Digite seu e-mail para receber um link de redefinição de senha.</p>
                    </div>
                </div>

                
                <x-form action="{{ route('company.password.email') }}" class="w-full flex flex-col gap-4 items-center" post>
                    @if(session('message'))
                        <span class="text-green-500 text-sm text-center">{{ session('message') }}</span>
                    @endif
                    <x-form.input-text name="email" placeholder="Digite seu e-mail" />
                    <x-action tag="button" type="submit" variant="secondary">Enviar e-mail</x-action>
                </x-form>

            </div>
        </div>
    </div>

</x-layouts.app>

<script src="{{ asset('js/global.js') }}"></script>