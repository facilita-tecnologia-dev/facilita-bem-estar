<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title title="Colaborador | Criar" />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.store') }}" id="form-update-company-profile" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <x-form.input-text name="name" label="Nome completo" placeholder="Digite o nome completo do usuário"/>
                    
                    <x-form.input-text name="cpf" label="CPF" placeholder="Digite o cpf do usuário"/>
                    
                    <x-form.input-date name="birth_date" max="{{ Carbon\Carbon::now()->subYears(16)->toDateString() }}" label="Data de nascimento"/>

                    <x-form.select name="gender" label="Sexo" :options="$gendersToSelect" />
                    
                    <x-form.input-text name="department" label="Setor" placeholder="Digite o departamento do usuário"/>
                    
                    <x-form.input-text name="occupation" label="Cargo" placeholder="Digite o cargo do usuário"/>

                    <x-form.input-date name="admission" max="{{ Carbon\Carbon::now()->toDateString() }}" label="Data de admissão"/>

                    <x-form.select name="role" label="Gestor/Colaborador" :options="$rolesToSelect" />
                        
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action href="{{ route('user.index') }}" variant="secondary">Cancelar</x-action>
                    <x-action tag="button" type="submit" form="form-update-company-profile" variant="secondary">Salvar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>