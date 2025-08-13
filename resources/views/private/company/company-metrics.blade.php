<x-layouts.app>
    <x-structure.page-container>
        <x-structure.sidebar />
        
        <x-structure.main-content-container>
            <x-structure.page-title 
                title="Dados de Desempenho Organizacional" 
                :breadcrumbs="[
                    'Dados de Desempenho Organizacional' => '',
                ]"
            />

            @if(session('message'))
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    {{ session('message') }}
                </x-structure.message>
            @else
                <x-structure.message>
                    <i class="fa-solid fa-circle-info"></i>
                    Os indicadores são importantes para avaliar os riscos psicossociais, mas, se não souber, deixe-os em branco.
                </x-structure.message>
            @endif

           

            <div class="w-full bg-gray-100 rounded-md shadow-md p-4 md:p-8 space-y-6">
                <x-form action="{{ route('company-metrics') }}" id="form-update-company-profile" post class="w-full grid grid-cols-1 md:grid-cols-2 gap-4" enctype="multipart/form-data">

                    <div class="flex gap-2 items-end">
                        <x-form.input-text type="number" icon="%" min="0" max="100" step="0.01" name="turnover" value="{{ $metrics['turnover']['value'] ?? '' }}" label="Rotatividade" placeholder="Digite a porcentagem de rotatividade na sua empresa"/>
                        <x-action 
                            tag="button" 
                            type="button" 
                            variant="secondary"
                            data-tippy-content="Cálculo: (Nº de funcionários que saíram durante o ano / quadro médio) * 100"
                        >
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <x-form.input-text type="number" icon="%" min="0" max="100" step="0.01" name="absenteeism" value="{{ $metrics['absenteeism']['value'] ?? '' }}" label="Absenteísmo" placeholder="Digite a porcentagem de absenteísmo na sua empresa"/>
                        <x-action 
                            tag="button"
                            type="button"
                            variant="secondary"
                            data-tippy-content="Cálculo: (Nº de horas de faltas, atestados / Nº de horas trabalhadas disponíveis) * 100 <br/>  
                                                OBS: Horas disponíveis: nº de funcionários * nº de horas produtivas disponíveis no mês">
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <x-form.input-text type="number" icon="%" min="0" max="100" step="0.01" name="extra-hours" value="{{ $metrics['extra-hours']['value'] ?? '' }}" label="Horas Extras / Horas Trabalhadas" type="number" placeholder="Digite a porcentagem de horas extras na sua empresa"/>
                        <x-action 
                            tag="button"
                            type="button"
                            variant="secondary"
                            data-tippy-content="Cálculo: Nº total de horas extras / Nº de horas trabalhadas * 100"
                        >
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <x-form.input-text type="number" icon="%" min="0" max="100" step="0.01" name="accidents" value="{{ $metrics['accidents']['value'] ?? '' }}" label="Acidentes" placeholder="Digite a porcentagem de acidentes na sua empresa"/>
                        <x-action 
                            tag="button"
                            type="button"
                            variant="secondary"
                            data-tippy-content="Número de acidentes no ano."
                        >
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <x-form.input-text type="number" icon="%" min="0" max="100" step="0.01" name="absences" value="{{ $metrics['absences']['value'] ?? '' }}" label="Afastamentos" placeholder="Digite a porcentagem de afastamentos na sua empresa"/>
                        <x-action 
                            tag="button"
                            type="button"
                            variant="secondary"
                            data-tippy-content="Número de afastamentos ao INSS no ano."
                        >
                            <i class="fa-solid fa-question text-base"></i>
                        </x-action>
                    </div>
                </x-form>

                <div class="w-full flex flex-col md:flex-row justify-between gap-2">
                    <x-action tag="button" type="submit" form="form-update-company-profile" variant="secondary">Salvar</x-action>
                </div>
            </div>
        </x-structure.main-content-container>
    </x-structure.page-container>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>