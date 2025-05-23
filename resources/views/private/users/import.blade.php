<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>  
            <x-structure.page-title title="Importar colaboradores (.xls)" :back="route('user.index')" />

            {{-- @if(session('message')) --}}
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') ?? 'Os formatos das colunas no arquivo modelo devem ser mantidos sem alterações, pois qualquer modificação pode causar erros na importação dos dados.' }} 
                    </p>
                </div>
            {{-- @endif --}}
    
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.import') }}" id="form-import-users" post enctype="multipart/form-data">
                    <x-form.input-file name="import_users" accept=".xlsx" label="Escolha um arquivo Excel (.xlsx) com as informações dos funcionários"/>
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