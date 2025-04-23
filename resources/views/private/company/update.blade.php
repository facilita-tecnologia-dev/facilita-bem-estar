<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Empresa | Editar</h2>
            </div>

            @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('company.update', session('company')) }}" id="form-update-company-profile" put class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <div class="md:col-span-2">
                        <label class="block text-base font-semibold mb-1 text-gray-800" for="logo">
                            Logo
                        </label>
                        <x-form.input-file id="logo" name="logo" accept=".jpg, .jpeg, .avif, .png, .webp"/>
                        @error('logo')
                            <x-form.error-span text="{{ $message }}" />
                        @enderror
                    </div>
                    <x-form.input-text class="opacity-70" name="name" label="Razão Social" value="{{ $company->name }}" disabled placeholder="Digite a razão social da sua empresa"/>
                    <x-form.input-text class="opacity-70" name="cnpj" label="CNPJ" value="{{ $company->cnpj }}" disabled placeholder="Digite o cnpj da sua empresa"/>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action href="{{ route('company.show', session('company')) }}">Cancelar</x-action>
                    <x-action tag="button" form="form-update-company-profile">Salvar</x-action>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>