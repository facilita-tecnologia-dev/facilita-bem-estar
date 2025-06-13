<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>    
            <x-structure.page-title 
                title="Colaborador - Editar" 
                :back="route('user.show', $user)"
                :breadcrumbs="[
                    'Lista de colaboradores' => route('user.index'),
                    'Colaborador - Detalhe' => route('user.show', $user),
                    'Colaborador - Editar' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.update', $user) }}" id="form-update-company-profile" put class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">

                    <x-form.input-text name="name" label="Nome completo" value="{{ $user->name }}" placeholder="Digite o nome completo do usuário"/>
                    
                    <x-form.input-text name="cpf" label="CPF apenas números" value="{{ $user->cpf }}" readonly/>
                    
                    <x-form.input-text name="email" label="E-mail" value="{{ $user->email }}" placeholder="Digite o e-mail do usuário" />
                    
                    <x-form.input-date name="birth_date" max="{{ Carbon\Carbon::now()->subYears(16)->toDateString() }}" value="{{ $user->birth_date }}" label="Data de nascimento"/>

                    <x-form.select name="gender" label="Sexo" :options="$gendersToSelect" value="{{ $user->gender }}" />

                    <x-form.input-text name="marital_status" label="Estado Civil" placeholder="Digite o estado civil do usuário" value="{{ $user->marital_status }}"/>

                    <x-form.input-text name="education_level" label="Grau de Instrução" placeholder="Digite o grau de instrução do usuário" value="{{ $user->education_level }}"/>
                    
                    <x-form.input-text name="department" label="Setor" placeholder="Digite o departamento do usuário" value="{{ $user->department }}"/>
                    
                    <x-form.input-text name="occupation" label="Cargo" placeholder="Digite a cargo do usuário" value="{{ $user->occupation }}"/>
                    
                    <x-form.input-text name="work_shift" label="Turno" placeholder="Digite o turno do usuário" value="{{ $user->work_shift }}"/>

                    <x-form.input-date name="admission" max="{{ Carbon\Carbon::now()->toDateString() }}" label="Data de admissão" value="{{ $user->admission }}" />

                    <x-form.select name="role" label="Gestor/Colaborador" :options="$rolesToSelect" value="{{ $roleDisplayName }}" />
                    
                </x-form>

                <div class="w-full flex flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action href="{{ route('user.show', $user) }}" variant="danger">Cancelar</x-action>
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