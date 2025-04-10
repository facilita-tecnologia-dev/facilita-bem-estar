<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Colaborador | Criar</h2>
            </div>

            {{-- @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif --}}

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.store') }}" id="form-update-company-profile" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <x-form.input-text name="name" label="Nome completo" placeholder="Digite o nome completo do usuário"/>
                    
                    <x-form.input-text name="cpf" label="CPF apenas números" placeholder="Digite o cpf do usuário"/>
                    
                    <x-form.input-text name="birth_date" label="Idade" type="number" placeholder="Digite a idade do usuário"/>
                    
                    <x-form.input-text name="gender" label="Sexo" placeholder="Digite o sexo do usuário"/>
                    
                    <x-form.input-text name="department" label="Setor" placeholder="Digite o departamento do usuário"/>
                    
                    <x-form.input-text name="occupation" label="Cargo" placeholder="Digite o cargo do usuário"/>

                    <x-form.input-date name="admission" max="{{ date('Y-m-d') }}" label="Data de admissão"/>

                    <x-form.select name="role" label="Gestor/Colaborador" :options="$rolesToSelect" />
                        
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action href="{{ route('user.index') }}">Cancelar</x-action>
                    <x-action tag="button" form="form-update-company-profile">Salvar</x-action>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>