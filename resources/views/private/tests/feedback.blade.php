<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container class="items-center justify-center">
            <x-structure.page-title title="Deixe seu comentário, crítica, elogio ou sugestão." centered />
            
            <div class="w-full max-w-[550px]">
                <x-form action="{{ route('feedbacks.create') }}" post class="flex flex-col gap-2 items-center">
                    <x-form.textarea name="feedback" placeholder="Deixe seu comentário aqui... (opcional)" resize />
                    <x-action tag="button" type="submit">Prosseguir</x-action>
                </x-form>
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>
</x-layouts.app>


<script src="{{ asset('js/global.js') }}"></script>