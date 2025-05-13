<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Empresa" />
            
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="w-full grid gri-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <p class="font-semibold text-base sm:text-lg text-left">Logo da empresa</p>
                        <img src="{{ asset($company->logo) }}" alt="" class="h-11">
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">Razão social</p>
                        <p class="text-sm sm:text-base text-left">{{ $company->name }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold text-base sm:text-lg text-left">CNPJ</p>
                        <p class="text-sm sm:text-base text-left">{{ $company->cnpj }}</p>
                    </div>
                </div>

                @can('company-edit')
                    <div class="w-full flex flex-row justify-between gap-2">
                        <x-action href="{{ route('company.edit', session('company')) }}" variant="secondary">Editar</x-action>
                    </div>
                @endcan
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>