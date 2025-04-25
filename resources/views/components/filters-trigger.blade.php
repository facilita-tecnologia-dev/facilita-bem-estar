@props([
    'modalFilters' => ['name', 'cpf', 'gender', 'department', 'occupation'],
    'filtersApplied' => [],
])

<x-action tag="button" data-role="filter-modal-trigger">
    <i class="fa-solid fa-filter"></i>
</x-action>

<div data-role="filter-modal" class="hidden z-50 left-0 top-0 fixed w-screen h-screen bg-gray-800/30 px-4 items-center justify-center">
    <div class="w-full max-w-[900px] bg-gray-100 py-8 px-4 rounded-md flex flex-col items-center gap-6">
        <h2 class="text-center text-3xl font-semibold text-gray-800">Filtros</h2>

        <x-form id="filter-form" class="w-full grid grid-cols-1 md:grid-cols-3 gap-2 items-center">
            @if(in_array('name', $modalFilters))
                <x-form.input-text icon="search" name="name" label="Nome" placeholder="Nome do colaborador" value="{{ $filtersApplied['name'] ?? null }}" />
            @endif
            
            @if(in_array('cpf', $modalFilters))
                <x-form.input-text icon="id" name="cpf" label="CPF" placeholder="CPF do colaborador" value="{{ $filtersApplied['CPF'] ?? null }}" />
            @endif
    
            @if(in_array('gender', $modalFilters))
                @php
                    $gendersToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('gender')->toArray());
                @endphp
                @if(!empty(array_filter($gendersToFilter, fn($i) => $i !== '')))
                    <x-form.select name="gender" placeholder="Sexo" label="Sexo" value="{{ $filtersApplied['gender'] ?? null }}"  :options="$gendersToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('department', $modalFilters))
                @php
                    $departmentsToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('department')->toArray());
                @endphp
                @if(!empty(array_filter($departmentsToFilter, fn($i) => $i !== '')))
                    <x-form.select name="department" placeholder="Setor" label="Setor" value="{{ $filtersApplied['department'] ?? null }}"  :options="$departmentsToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('occupation', $modalFilters))
                @php
                    $occupationsToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('occupation')->toArray());
                @endphp
                @if(!empty(array_filter($occupationsToFilter, fn($i) => $i !== '')))
                    <x-form.select name="occupation" placeholder="Cargo" label="Cargo" value="{{ $filtersApplied['occupation'] ?? null }}"  :options="$occupationsToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('work_shift', $modalFilters))
                @php
                    $workShiftsToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('work_shift')->toArray());
                @endphp
                @if(!empty(array_filter($workShiftsToFilter, fn($i) => $i !== '')))
                    <x-form.select name="work_shift" placeholder="Turno" label="Turno" value="{{ $filtersApplied['work_shift'] ?? null }}"  :options="$workShiftsToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('marital_status', $modalFilters))
                @php
                    $maritalStatusToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('marital_status')->toArray());
                @endphp
                @if(!empty(array_filter($maritalStatusToFilter, fn($i) => $i !== '')))
                    <x-form.select name="marital_status" placeholder="Estado civil" label="Estado civil" value="{{ $filtersApplied['marital_status'] ?? null }}"  :options="$maritalStatusToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('education_level', $modalFilters))
                @php
                    $educationLevelsToFilter = array_keys(App\Models\Company::where('id', session('company')->id)->first()->users->groupBy('education_level')->toArray());
                @endphp
                @if(!empty(array_filter($educationLevelsToFilter, fn($i) => $i !== '')))
                    <x-form.select name="education_level" placeholder="Grau de instrução" label="Grau de instrução" value="{{ $filtersApplied['education_level'] ?? null }}"  :options="$educationLevelsToFilter" defaultValue />
                @endif
            @endif
            
            @if(in_array('age_range', $modalFilters))
                @php
                    $ageRangesToFilter = collect(App\Enums\AgeRangeEnum::cases())->map(fn($i) => [
                        'option' => $i->value . ' anos',
                        'value' => $i->value,
                    ]);
                @endphp
                @if(count($ageRangesToFilter) > 0)
                    <x-form.select name="age_range" placeholder="Faixa etária" label="Faixa etária" value="{{ $filtersApplied['age_range'] ?? null }}"  :options="$ageRangesToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('admission_range', $modalFilters))
                @php
                    $admissionRangesToFilter = collect(App\Enums\AdmissionRangeEnum::cases())->map(fn($i) => [
                        'option' => $i->value . ' anos',
                        'value' => $i->value,
                    ]);
                @endphp
                @if(count($admissionRangesToFilter) > 0)
                    <x-form.select name="admission_range" placeholder="Tempo de admissão" label="Tempo de admissão" value="{{ $filtersApplied['admission_range'] ?? null }}"  :options="$admissionRangesToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('year', $modalFilters))
                @php
                    $yearsTofilter = [
                        Carbon\Carbon::now()->year, 
                        Carbon\Carbon::now()->subYear()->year, 
                        Carbon\Carbon::now()->subYears(2)->year, 
                        Carbon\Carbon::now()->subYears(3)->year,
                        Carbon\Carbon::now()->subYears(4)->year
                    ];
                @endphp
                @if(count($yearsTofilter) > 0)
                    <x-form.select name="year" placeholder="Ano de realização do teste" label="Ano de realização do teste" value="{{ $filtersApplied['year'] ?? null }}"  :options="$yearsTofilter" />
                @endif
            @endif
        </x-form>

        <x-action form="filter-form" type="submit" variant="secondary" tag="button">Filtrar resultados</x-action>
    </div>
</div>
