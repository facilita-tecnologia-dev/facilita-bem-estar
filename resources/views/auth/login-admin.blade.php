<x-layouts.app>
    
        <x-box class="!w-full max-w-sm">
            <div class="bg-teal-700 p-1.5 rounded-md mb-3">
                <img src="{{ asset('assets/logo_company.png') }}" alt="" class="h-10 sm:h-12 md:h-14">
            </div>

            <x-heading>
                <span class="relative text-teal-400">
                    Bem-estar
                    <img src="{{ asset('assets/svg-line.svg') }}" alt="" class="absolute top-full left-0">
                </span> Facilita!
            </x-heading>

            <div class="text-center mt-6 mb-5">
                <p class="text-lg font-medium">Login do gestor</p>
            </div>


            <x-form action="{{ route('auth.login.admin') }}" class="w-full flex flex-col items-stretch gap-2" post>
                <div class="flex flex-col items-start w-full space-y-0.5">
                    <label for="cpf">CPF apenas números</label>
                    <input type="text" id="cpf" name="cpf" class=" w-full px-2 h-8 rounded-md border border-teal-700 focus:outline-none" placeholder="Digite aqui seu cpf...">
                    @error('cpf')
                        <span class="text-rose-400 text-xs rounded-md">{{ $message }}</span>
                    @enderror

                    @if(session('errorMessage'))    
                        <span class="text-rose-400 text-xs rounded-md">{{ session('errorMessage') }}</span>
                    @endif
                </div>

                <x-actions.button type="submit" color='success' variant="solid">Entrar</x-actions.button>
            </x-form>
            

            <x-actions.anchor href="{{ route('auth.initial') }}" variant="underline" color="danger">
                Voltar para a página inicial
            </x-actions.anchor>

        </x-box>

</x-layouts.app>