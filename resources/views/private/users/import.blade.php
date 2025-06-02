<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>  
            <x-structure.page-title 
                title="Importar colaboradores (.xls)"
                :back="route('user.index')"
                :breadcrumbs="[
                    'Lista de colaboradores' => route('user.index'),
                    'Importar colaboradores' => '',
                ]"
            />

            <x-structure.message>
                <i class="fa-solid fa-circle-info"></i>
                {{ session('message') ?? 'Os formatos das colunas no arquivo modelo devem ser mantidos sem alterações, pois qualquer modificação pode causar erros na importação dos dados.' }} 
            </x-structure.message>
    
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.import') }}" id="form-import-users" post enctype="multipart/form-data" class="flex gap-2">
                    <x-form.input-file name="import_users" accept=".xlsx" label="Escolha um arquivo Excel (.xlsx) com as informações dos funcionários"/>
                    
                    <x-action 
                        variant="secondary" 
                        tag="button"
                        data-tippy-content="Os formatos das colunas no arquivo modelo devem ser mantidos sem alterações, pois qualquer modificação pode causar erros na importação dos dados."
                    >
                        <i class="fa-solid fa-question text-base"></i>
                    </x-action>
                </x-form>    
                
                <div class="w-full flex justify-end items-center gap-2">
                    <x-action href="{{ asset('files/template-importacao-de-funcionarios-facilita.xlsx') }}" download="template-importacao-funcionarios-facilita.xlsx" variant="secondary">
                        Download do arquivo template
                    </x-action>
                    
                    <x-action tag="button" type="submit" form="form-import-users" variant="secondary">Importar arquivo</x-action>
                </div>
            </div>
        </x-structure.main-content-container>  
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>