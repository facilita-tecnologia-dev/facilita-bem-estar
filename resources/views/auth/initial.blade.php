<x-layouts.app>
    
        <x-box>            
            <div class="p-1.5 rounded-md mb-3">
                <img src="{{ asset('assets/logo_company.png') }}" alt="" class="h-14">
            </div>

            <x-heading>
                Facilita
                <span class="relative text-fuchsia-600">
                    Saúde Mental
                </span>
            </x-heading>

            <div class="text-center mt-6 mb-5">
                <p class="text-lg font-medium">Escolha a forma de Login</p>
            </div>

            <div class="flex gap-4 flex-col sm:flex-row">
                <x-actions.anchor href="{{ route('auth.login.admin') }}" color='success' variant="solid">Login como gestor</x-actions.anchor>
                <x-actions.anchor href="{{ route('auth.login.user') }}" color='success' variant="solid">Login como colaborador</x-actions.anchor>
            </div>
            

        </x-box>

</x-layouts.app>