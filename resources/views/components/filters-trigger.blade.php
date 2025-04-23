@props([
    'modalFilters' => ['name', 'cpf', 'gender', 'department', 'occupation'],
    'filtersApplied' => [],
])

<button data-role="filter-modal-trigger" {{ $attributes->merge(['class' => 'bg-gray-100/50 aspect-square h-full rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition']) }}>
    <i class="fa-solid fa-filter"></i>
</button>

<div data-role="filter-modal" class="hidden z-50 left-0 top-0 fixed w-screen h-screen bg-gray-800/30 px-4 items-center justify-center">
    <div class="w-full max-w-[720px] bg-gray-100 py-8 px-4 rounded-md flex flex-col items-center gap-6">
        <h2 class="text-center text-3xl font-semibold text-gray-800">Filtros</h2>

        <x-form id="filter-form" class="w-full grid grid-cols-2 gap-2 items-center">
            @if(in_array('name', $modalFilters))
                <x-form.input-text icon="search" name="name" label="Nome" placeholder="Nome do colaborador" value="{{ $filtersApplied['name'] ?? null }}" />
            @endif
            
            @if(in_array('cpf', $modalFilters))
                <x-form.input-text icon="id" name="cpf" label="CPF" placeholder="CPF do colaborador" value="{{ $filtersApplied['CPF'] ?? null }}" />
            @endif
    
            @if(in_array('gender', $modalFilters))
                @php
                    $gendersToFilter = array_keys(session('company')->users->groupBy('gender')->toArray());
                @endphp
                @if(count($gendersToFilter) > 0)
                    <x-form.select name="gender" placeholder="Sexo" label="Sexo" value="{{ $filtersApplied['gender'] ?? null }}"  :options="$gendersToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('department', $modalFilters))
                @php
                    $departmentsToFilter = array_keys(session('company')->users->groupBy('department')->toArray());
                @endphp
                @if(count($departmentsToFilter) > 0)
                    <x-form.select name="department" placeholder="Setor" label="Setor" value="{{ $filtersApplied['department'] ?? null }}"  :options="$departmentsToFilter" defaultValue />
                @endif
            @endif

            @if(in_array('occupation', $modalFilters))
                @php
                    $occupationsToFilter = array_keys(session('company')->users->groupBy('occupation')->toArray());
                @endphp
                @if(count($occupationsToFilter) > 0)
                    <x-form.select name="occupation" placeholder="Cargo" label="Cargo" value="{{ $filtersApplied['occupation'] ?? null }}"  :options="$occupationsToFilter" defaultValue />
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
                    <x-form.select name="year" placeholder="Ano de realização do teste" label="Ano de realização do teste" value="{{ $filters['year'] ?? null }}"  :options="$yearsTofilter" defaultValue />
                @endif
            @endif
        </x-form>

        <x-action tag="button" form="filter-form">Filtrar</x-action>
    </div>
</div>
