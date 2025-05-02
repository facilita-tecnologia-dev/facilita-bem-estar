@props([
    'modalFilters' => ['name', 'cpf', 'gender', 'department', 'occupation'],
    'filtersApplied' => [],
])

<div class="flex items-center w-full md:w-fit gap-2">
    @if(count($filtersApplied))
        <x-action href="{{ route(Route::currentRouteName(), request()->route()->parameters()) }}" width="full">
            <i class="fa-solid fa-filter-circle-xmark text-sm sm:text-base"></i>
        </x-action>
    @endif
    <x-action tag="button" data-role="filter-modal-trigger" width="full">
        <i class="fa-solid fa-filter text-sm sm:text-base"></i>
    </x-action>
</div>

<div data-role="filter-modal" class="hidden z-50 left-0 top-0 fixed w-screen h-screen bg-gray-800/30 px-4 py-8 items-center justify-center">
    <div class="w-full max-h-full max-w-[900px] bg-gray-100 py-8 px-4 rounded-md flex flex-col items-center gap-6">
        <h2 class="text-center text-3xl font-semibold text-gray-800">Filtros</h2>

        <x-form id="filter-form" class="w-full grid grid-cols-1 md:grid-cols-3 gap-2 items-center flex-1 overflow-auto">
            @if(in_array('name', $modalFilters))
                <x-form.input-text icon="search" name="name" label="Nome" placeholder="Nome do colaborador" value="{{ $filtersApplied['name'] ?? null }}" />
            @endif
            
            @if(in_array('cpf', $modalFilters))
                <x-form.input-text icon="id" name="cpf" label="CPF" placeholder="CPF do colaborador" value="{{ $filtersApplied['CPF'] ?? null }}" />
            @endif
    
            @if(in_array('gender', $modalFilters))
                <x-form.select name="gender" placeholder="Sexo" label="Sexo" value="{{ $filtersApplied['gender'] ?? null }}"  :options="$gendersToFilter" defaultValue />
            @endif

            @if(in_array('department', $modalFilters))
                <x-form.select name="department" placeholder="Setor" label="Setor" value="{{ $filtersApplied['department'] ?? null }}"  :options="$departmentsToFilter" defaultValue />
            @endif

            @if(in_array('occupation', $modalFilters))
                <x-form.select name="occupation" placeholder="Cargo" label="Cargo" value="{{ $filtersApplied['occupation'] ?? null }}"  :options="$occupationsToFilter" defaultValue />
            @endif

            @if(in_array('work_shift', $modalFilters))
                <x-form.select name="work_shift" placeholder="Turno" label="Turno" value="{{ $filtersApplied['work_shift'] ?? null }}"  :options="$workShiftsToFilter" defaultValue />
            @endif

            @if(in_array('marital_status', $modalFilters))
                <x-form.select name="marital_status" placeholder="Estado civil" label="Estado civil" value="{{ $filtersApplied['marital_status'] ?? null }}"  :options="$maritalStatusToFilter" defaultValue />
            @endif

            @if(in_array('education_level', $modalFilters))
                <x-form.select name="education_level" placeholder="Grau de instrução" label="Grau de instrução" value="{{ $filtersApplied['education_level'] ?? null }}"  :options="$educationLevelsToFilter" defaultValue />
            @endif
            
            @if(in_array('age_range', $modalFilters))
                <x-form.select name="age_range" placeholder="Faixa etária" label="Faixa etária" value="{{ $filtersApplied['age_range'] ?? null }}"  :options="$ageRangesToFilter" defaultValue />
            @endif

            @if(in_array('admission_range', $modalFilters))
                <x-form.select name="admission_range" placeholder="Tempo de admissão" label="Tempo de admissão" value="{{ $filtersApplied['admission_range'] ?? null }}"  :options="$admissionRangesToFilter" defaultValue />
            @endif

            @if(in_array('year', $modalFilters))
                <x-form.select name="year" placeholder="Ano de realização do teste" label="Ano de realização do teste" value="{{ $filtersApplied['year'] ?? null }}"  :options="$yearsTofilter" />
            @endif

            @if(in_array('hasAnsweredPsychosocialCollection', $modalFilters))
                <x-form.select name="has_answered_psychosocial" placeholder="Testes de Riscos Psicossociais" label="Testes de Riscos Psicossociais" value="{{ $filtersApplied['has_answered_psychosocial'] ?? null }}"  :options="$hasAnsweredPsychosocial" defaultValue />
            @endif

            @if(in_array('hasAnsweredOrganizationalCollection', $modalFilters))
                <x-form.select name="has_answered_organizational" placeholder="Testes de Clima Organizacional" label="Testes de Clima Organizacional" value="{{ $filtersApplied['has_answered_organizational'] ?? null }}"  :options="$hasAnsweredOrganizational" defaultValue />
            @endif
        </x-form>

        <x-action form="filter-form" type="submit" variant="secondary" tag="button">Filtrar resultados</x-action>
    </div>
</div>
