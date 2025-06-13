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

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }} 
                </x-structure.message>
            @else
                <div class="bg-yellow-500/25 w-full px-6 rounded-md shadow-md py-2">
                    <p class="text-sm md:text-base text-yellow-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Os formatos das colunas do arquivo modelo devem ser mantidos, para evitar erros na importação. Se o arquivo contiver colaboradores já cadastrados, seus dados serão sobrescritos.
                    </p>
                </div>
            @endif
    
            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('user.import') }}" id="form-import-users" post enctype="multipart/form-data" class="flex gap-2">
                    <x-form.input-file name="import_users" accept=".xlsx" label="Escolha um arquivo Excel (.xlsx) com as informações dos funcionários"/>
                    
                    <x-action 
                        variant="secondary" 
                        tag="button"
                        type="button"
                        data-tippy-content="Os formatos das colunas do arquivo modelo devem ser mantidos, para evitar erros na importação. Se o arquivo contiver colaboradores já cadastrados, seus dados serão sobrescritos."
                    >
                        <i class="fa-solid fa-question text-base"></i>
                    </x-action>
                </x-form>    

                @if(isset($failures))
                <div class="px-4 py-2 bg-red-100 rounded-md text-sm space-y-2">
                    <h3 class="font-medium">Seu arquivo de importação contém erros. Por isso, os colaboradores das linhas com inconsistências não foram importados.</h3>
                    <ul class="list-disc pl-4">
                        @foreach ($failures as $failure)                        
                        <li>{{ $failure }}</li>
                        @endforeach
                    </ul>
                    <p class="font-medium">Por favor, corrija esses erros no arquivo de importação e tente novamente.</p>
                </div>
                @endif
                
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