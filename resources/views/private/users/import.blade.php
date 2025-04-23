<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-12">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto px-4 py-2 md:px-8 md:py-4 flex flex-col items-start justify-start gap-6">   
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Importar</h2>
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
                <x-form action="{{ route('user.import', session('company')) }}" id="form-import-users" post enctype="multipart/form-data">
                    <x-form.input-file name="import_users" accept=".xlsx"/>
                </x-form>                

                <x-action tag="button" form="form-import-users">Importar</x-action>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>