<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Colaborador - Criar" 
                :back="route('user.index')"
                :breadcrumbs="[
                    'Lista de colaboradores' => route('user.index'),
                    'Colaborador - Criar' => '',
                ]"
            />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.store') }}" id="form-create-user" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex gap-1.5 items-end">
                            <x-form.input-text name="cpf" id="cpf" label="CPF" placeholder="Digite o cpf do usuário"/>
                            <x-action 
                                variant="secondary" 
                                id="check-cpf" 
                                data-tippy-content="Pesquise o CPF para verificar se o usuário já existe. Se existir, clique em 'Vincular usuário por CPF'.">
                                <i class="fa-solid fa-magnifying-glass text-base -scale-x-100"></i>
                            </x-action>
                        </div>
                        <div class="w-full">
                            <span class="text-xs text-rose-400" id="cpf-message"></span>
                        </div>
                    </div>

                    <x-form.input-text name="name" label="Nome completo" placeholder="Digite o nome completo do usuário"/>
                    
                    <x-form.input-date name="birth_date" max="{{ Carbon\Carbon::now()->subYears(16)->toDateString() }}" label="Data de nascimento"/>

                    <x-form.select name="gender" label="Sexo" :options="$gendersToSelect"/>

                    <x-form.input-text name="marital_status" label="Estado Civil" placeholder="Digite o estado civil do usuário"/>

                    <x-form.input-text name="education_level" label="Grau de Instrução" placeholder="Digite o grau de instrução do usuário"/>
                    
                    <x-form.input-text name="department" label="Setor" placeholder="Digite o departamento do usuário"/>
                    
                    <x-form.input-text name="occupation" label="Função" placeholder="Digite a função do usuário"/>
                    
                    <x-form.input-text name="work_shift" label="Turno" placeholder="Digite o turno do usuário"/>
                    
                    <x-form.input-date name="admission" max="{{ Carbon\Carbon::now()->toDateString() }}" label="Data de admissão"/>
                    
                    <x-form.select name="role" label="Gestor/Colaborador" :options="$rolesToSelect" />
                        
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-end gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action href="{{ route('user.index') }}" variant="danger">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        @can('user-create')
                            <x-action href="{{ route('user.add-existing') }}" variant="secondary">Vincular usuário por CPF</x-action>
                        @endcan
                        <x-action tag="button" type="submit" form="form-create-user" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkBtn = document.getElementById('check-cpf');
            const cpfInput = document.getElementById('cpf');
            const messageDiv = document.getElementById('cpf-message');

            checkBtn.addEventListener('click', async function () {
                const cpf = cpfInput.value.trim();
                messageDiv.textContent = '';
                
                if (!cpf) {
                    messageDiv.textContent = 'Digite um CPF para pesquisar';
                    return;
                }

                try {
                    const response = await fetch(`{{ route('user.create.checkCpf') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ cpf })
                    });

                    const data = await response.json();

                    console.log(data);

                    if (data.exists) {
                        messageDiv.textContent = 'CPF já está cadastrado.';
                        messageDiv.classList.add('text-red-500');
                        messageDiv.classList.remove('text-green-500');
                    } else {
                        messageDiv.textContent = 'CPF disponível.';
                        messageDiv.classList.add('text-green-500');
                        messageDiv.classList.remove('text-red-500');
                    }

                } catch (error) {
                    messageDiv.textContent = 'Erro ao verificar CPF.';
                }
            });
        });
    </script>
</x-layouts.app>