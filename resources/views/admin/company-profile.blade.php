<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Empresa</h2>
            </div>
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="w-full grid gri-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <p class="font-semibold text-lg text-left">Logo da empresa</p>
                        <img src="{{ asset($company->logo) }}" alt="" class="h-11">
                    </div>
                    <div class="">
                        <p class="font-semibold text-lg text-left">Razão social</p>
                        <p class="text-base text-left">{{ $company->name }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-lg text-left">CNPJ</p>
                        <p class="text-base text-left">{{ $company->cnpj }}</p>
                    </div>
                </div>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action href="{{ route('import-employees') }}">Importação de colaboradores</x-action>
                    <x-action href="{{ route('company-profile.update') }}">Editar</x-action>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>