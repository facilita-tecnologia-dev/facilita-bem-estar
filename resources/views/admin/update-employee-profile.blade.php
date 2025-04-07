<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Colaborador | Editar</h2>
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
                <x-form action="{{ route('employee.update', $employee) }}" id="form-update-company-profile" put class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <x-form.input-text name="name" label="Nome completo" value="{{ $employee->name }}" placeholder="Digite o nome completo do colaborador"/>
                    
                    <x-form.input-text name="cpf" label="CPF apenas números" value="{{ $employee->cpf }}" placeholder="Digite o cpf do colaborador"/>
                    
                    <x-form.input-text name="age" label="Idade" value="{{ $employee->age }}" placeholder="Digite a idade do colaborador"/>
                    
                    <x-form.input-text name="gender" label="Sexo" value="{{ $employee->gender }}" placeholder="Digite o sexo do colaborador"/>
                    
                    <x-form.input-text name="department" label="Setor" value="{{ $employee->department }}" placeholder="Digite o departamento do colaborador"/>
                    
                    <x-form.input-text name="occupation" label="Cargo" value="{{ $employee->occupation }}" placeholder="Digite o cargo do colaborador"/>

                    <x-form.select name="role" value="{{ $currentUserRole }}" label="Gestor/Colaborador" :options="$rolesToSelect" />
                        

                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action href="{{ route('employee.show', $employee) }}">Cancelar</x-action>
                    <x-action tag="button" form="form-update-company-profile">Salvar</x-action>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>