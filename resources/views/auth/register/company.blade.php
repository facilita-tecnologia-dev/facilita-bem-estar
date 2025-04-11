<x-layouts.app>

        <div class="flex h-full justify-end">
            <div class="w-full max-w-[600px] bg-gray-100 flex justify-center items-center px-4">
                <div class="w-full max-w-[400px] flex flex-col items-center gap-8">
                    <img src="{{ asset('assets/icon-facilita.svg') }}" alt="">

                    <div class="flex flex-col gap-4 items-center">
                        <h1 class="text-4xl md:text-5xl font-semibold text-center text-gray-800">Registro</h1>
                        <x-text-content>Registre sua empresa no Facilita Saúde Mental</x-text-content>
                    </div>

                    <x-form action="{{ route('auth.register.internal.company') }}" class="w-full flex flex-col gap-3 items-center" post>
                        <x-form.input-text name="name" placeholder="Razão Social" />
                        <x-form.input-text name="cnpj" placeholder="CNPJ apenas números" />
                        <x-action tag="button">Registrar</x-action>
                    </x-form>

                    <x-action href="{{ route('presentation') }}" variant="simple">Voltar para a Home</x-action>
                </div>
            </div>
        </div>

</x-layouts.app>