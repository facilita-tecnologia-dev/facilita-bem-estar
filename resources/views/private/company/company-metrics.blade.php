<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Indicadores</h2>
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
                <x-form action="{{ route('company-metrics') }}" id="form-update-company-profile" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    <x-form.input-text type="number" icon="%" name="turnover" value="{{ $metrics['turnover'][0]['value'] ?? '' }}" label="Rotatividade" placeholder="Digite a porcentagem de rotatividade na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" name="absenteeism" value="{{ $metrics['absenteeism'][0]['value'] ?? '' }}" label="Absenteísmo" placeholder="Digite a porcentagem de absenteísmo na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" name="extra-hours" value="{{ $metrics['extra-hours'][0]['value'] ?? '' }}" label="Horas Extras / Horas Trabalhadas" type="number" placeholder="Digite a porcentagem de horas extras na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" name="accidents" value="{{ $metrics['accidents'][0]['value'] ?? '' }}" label="Acidentes" placeholder="Digite a porcentagem de acidentes na sua empresa"/>
                    
                    <x-form.input-text type="number" icon="%" name="absences" value="{{ $metrics['absences'][0]['value'] ?? '' }}" label="Afastamentos" placeholder="Digite a porcentagem de afastamentos na sua empresa"/>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action tag="button" form="form-update-company-profile">Salvar</x-action>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>