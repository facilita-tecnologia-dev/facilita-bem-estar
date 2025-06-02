<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Empresa" 
                :breadcrumbs="[
                    'Empresa' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif
            
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="w-full grid gri-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold text-base sm:text-lg text-left">Logo da empresa</p>
                        @if($company->logo)
                            <img src="{{ asset($company->logo) }}" alt="" class="h-11">
                        @else
                            <p class="text-sm sm:text-base text-left">Não cadastrado</p>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <p class="font-semibold text-base sm:text-lg text-left">Razão social</p>
                        <p class="text-sm sm:text-base text-left">{{ $company->name }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="font-semibold text-base sm:text-lg text-left">CNPJ</p>
                        <p class="text-sm sm:text-base text-left">{{ $company->cnpj }}</p>
                    </div>
                </div>

                @can('company-edit')
                    <div class="w-full flex flex-row justify-between gap-2">
                        <div class="flex items-center gap-2" data-position="left">
                            <x-action tag="button" variant="danger" data-role="delete-company-modal-trigger">Excluir conta</x-action>
                        </div>
                        <div class="flex items-center gap-2" data-position="right">
                            <x-action tag="button" variant="secondary" data-role="reset-password-modal-trigger">Redefinir senha</x-action>
                            <x-action href="{{ route('company.edit', session('company')) }}" variant="secondary">Editar</x-action>
                        </div>
                    </div>
                @endcan
            </div>

            <x-modal.background data-role="delete-company-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Excluir conta</x-modal.title>
                    
                    <p class="p-2 bg-red-300/50 rounded-md text-red-700 text-sm">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i>
                        Esta ação não pode ser desfeita e excluirá permanentemente a conta da empresa e todos os dados vinculados.
                    </p>
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-center">Digite a senha da empresa para excluir a conta:</p>
                        <x-form action="{{ route('company.destroy', session('company')) }}" delete class="w-full flex flex-col gap-2">
                            <x-form.input-text type="password" name="password" placeholder="Senha"/>
                            <x-action variant="danger" tag="button" width="full">Excluir conta</x-action>
                        </x-form>
                    </div>
                </x-modal.wrapper>
            </x-modal.background>
            
            <x-modal.background data-role="reset-password-modal">
                <x-modal.wrapper class="max-w-[450px]">
                    <x-modal.title>Redefinir senha</x-modal.title>
                    
                    <div class="w-full flex flex-col gap-2">
                        <x-form action="{{ route('company.reset-password', session('company')) }}" put class="w-full flex flex-col gap-2">
                            <p class="text-center">Digite a senha atual:</p>
                            <x-form.input-text type="password" name="current_password" placeholder="Senha atual"/>
                            <p class="text-center">Digite e confirme a nova senha:</p>
                            <x-form.input-text type="password" name="new_password" placeholder="Nova senha" oninput="checkPasswordSteps(event)"/>
                            <x-password-requirements />
                            <x-form.input-text type="password" name="new_password_confirmation" placeholder="Confirme sua nova senha"/>
                            <x-action variant="secondary" tag="button" width="full">Redefinir senha</x-action>
                        </x-form>
                    </div>
                </x-modal.wrapper>
            </x-modal.background>

        </x-structure.main-content-container>
    </x-structure.page-container>


    <script src="{{ asset('js/global.js') }}"></script>
    <script src="{{ asset('js/company/show.js') }}"></script>
    <script src="{{ asset('js/reset-password.js') }}"></script>
</x-layouts.app>