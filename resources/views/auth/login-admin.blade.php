<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-md min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">

            <div class="bg-teal-700 p-1.5 rounded-md">
                <img src="{{ asset('assets/logo_company.png') }}" alt="" class="h-14">
            </div>

            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center"> 
                <span class="relative text-teal-400">
                    Bem-estar
                    <img src="{{ asset('assets/svg-line.svg') }}" alt="" class="absolute top-full left-0">
                </span> Facilita!
            </h1>

            <div class="text-center">
                <p class="text-lg font-medium">Login do Administrador</p>
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
            
            <a href="{{ route('auth.initial') }}" class="underline text-sm text-rose-400">Voltar para a página inicial</a>

        </div>

</x-layouts.app>