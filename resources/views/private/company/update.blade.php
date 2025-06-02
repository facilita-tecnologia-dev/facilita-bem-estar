<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>  
            <x-structure.page-title 
                title="Empresa - Editar" 
                :back="route('company.show', $company)" 
                :breadcrumbs="[
                    'Empresa' => route('company.show', $company),
                    'Editar' => ''
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
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

                <div class="w-full flex flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action href="{{ route('company.show', session('company')) }}" variant="secondary">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        <x-action tag="button" type="submit" form="form-update-company-profile" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>  
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>