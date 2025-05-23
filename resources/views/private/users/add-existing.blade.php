<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Colaborador | Adicionar Existente" />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <p>Vincule um usuário existente no sistema pelo CPF:</p>
                </div>

                <x-form action="{{ route('user.add-existing') }}" id="form-add-existing-user" post class="w-full grid grid-cols-1 gap-4">
                    <x-form.input-text name="cpf" label="CPF" placeholder="Digite o cpf do usuário"/>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-end gap-2">
                    <x-action href="{{ route('user.create') }}" variant="danger">Cancelar</x-action>
                    <x-action tag="button" type="submit" form="form-add-existing-user" variant="secondary">Salvar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>