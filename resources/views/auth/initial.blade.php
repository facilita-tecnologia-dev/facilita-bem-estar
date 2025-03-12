<x-layouts.app>
    
        <div class="bg-white rounded-md w-full max-w-lg min-h-2/4 max-h-3/4 shadow-md p-10 flex flex-col items-center justify-center gap-8">
            
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
                <p class="text-lg font-medium">Escolha a forma de Login</p>
            </div>

            <div class="flex gap-4">
                <x-actions.anchor href="{{ route('auth.login.admin') }}" color='success' variant="solid">Login como gestor</x-actions.anchor>
                <x-actions.anchor href="{{ route('auth.login.user') }}" color='success' variant="solid">Login como colaborador</x-actions.anchor>
            </div>
            

        </div>

</x-layouts.app>