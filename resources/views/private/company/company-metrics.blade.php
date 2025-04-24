<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title title="Indicadores" />

            @if(session('message'))
                <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                    <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                        <i class="fa-solid fa-circle-info text-lg"></i>
                        {{ session('message') }}
                    </p>
                </div>
            @endif

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('company-metrics') }}" id="form-update-company-profile" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <x-form.input-text type="number" icon="%" min="0" max="100" name="turnover" value="{{ $metrics['turnover'][0]['value'] ?? '' }}" label="Rotatividade" placeholder="Digite a porcentagem de rotatividade na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" min="0" max="100" name="absenteeism" value="{{ $metrics['absenteeism'][0]['value'] ?? '' }}" label="Absenteísmo" placeholder="Digite a porcentagem de absenteísmo na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" min="0" max="100" name="extra-hours" value="{{ $metrics['extra-hours'][0]['value'] ?? '' }}" label="Horas Extras / Horas Trabalhadas" type="number" placeholder="Digite a porcentagem de horas extras na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" min="0" max="100" name="accidents" value="{{ $metrics['accidents'][0]['value'] ?? '' }}" label="Acidentes" placeholder="Digite a porcentagem de acidentes na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" min="0" max="100" name="absences" value="{{ $metrics['absences'][0]['value'] ?? '' }}" label="Afastamentos" placeholder="Digite a porcentagem de afastamentos na sua empresa"/>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action tag="button" type="submit" form="form-update-company-profile" variant="secondary">Salvar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>