<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Comentário - Detalhe"
                :back="route('feedbacks.index')"
                :breadcrumbs="[
                    'Comentários' => route('feedbacks.index'),
                    'Comentário - Detalhe' => '',
                ]"
            />
            
            <div class="w-full bg-gray-100/50 p-4 rounded-md shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Setor</p>
                        {{ $parentUser->department }}
                    </div>
                    <div>
                        <p class="font-semibold">Turno</p>
                        {{ $parentUser->work_shift }}
                    </div>
                    <div class="sm:col-span-2">
                        <p class="font-semibold">Comentário</p>
                        {{ $feedback->content }}
                    </div>
                </div>

            </div>

        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>