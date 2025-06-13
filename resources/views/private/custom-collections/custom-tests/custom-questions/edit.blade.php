<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>      
            <x-structure.page-title 
                title="Formulários de Pesquisa" 
                :breadcrumbs="[
                    'Formulários de Pesquisa' => '',
                    $customCollection->name => route('custom-collections.show', $customCollection),
                    $customTest->display_name => route('custom-collections.tests.show', ['customCollection' => $customCollection, 'customTest' => $customTest]),
                    'Editar questão' => ''
                ]"
            />

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('custom-collections.tests.questions.update', ['customCollection' => $customCollection, 'customTest' => $customTest, 'customQuestion' => $customQuestion]) }}" id="form-create-campaign" put class="w-full gap-4">
                    <x-form.input-text name="statement" label="Enunciado" placeholder="Digite o enunciado da questão" value="{{ old('statement', $customQuestion->statement) }}"/>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <div class="flex items-center gap-2" data-position="left">
                        <x-action :href="route('custom-collections.tests.show', ['customCollection' => $customCollection, 'customTest' => $customTest])" variant="danger">Cancelar</x-action>
                    </div>
                    <div class="flex items-center gap-2" data-position="right">
                        <x-action tag="button" type="submit" form="form-create-campaign" variant="secondary">Salvar</x-action>
                    </div>
                </div>
            </div>
        </x-structure.main-content-container>   
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>