@props([
    'countFiltered' => null, // Indica se quer que apareça o contador "X usuários filtrados"
    'filtersApplied' => [],
    'filteredUsers' => [],
])

@if($filtersApplied)
    <div class="w-full flex items-start gap-2 justify-between">
        <div class="items-center gap-2 flex flex-wrap">
            @if($countFiltered)
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">
                    {{ count($filteredUsers) ?? '0' }} usuários filtrados
                </span>
            @endif
            @if(isset($filtersApplied['name']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Nome: {{ $filtersApplied['name'] }}</span>
            @endif
            @if(isset($filtersApplied['cpf']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">CPF: {{ $filtersApplied['cpf'] }}</span>
            @endif
            @if(isset($filtersApplied['department']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Setor: {{ $filtersApplied['department'] }}</span>
            @endif
            @if(isset($filtersApplied['occupation']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Cargo: {{ $filtersApplied['occupation'] }}</span>
            @endif
            @if(isset($filtersApplied['gender']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Sexo: {{ $filtersApplied['gender'] }}</span>
            @endif
            @if(isset($filtersApplied['work_shift']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Turno: {{ $filtersApplied['work_shift'] }}</span>
            @endif
            @if(isset($filtersApplied['marital_status']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Estado civil: {{ $filtersApplied['marital_status'] }}</span>
            @endif
            @if(isset($filtersApplied['education_level']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Grau de instrução: {{ $filtersApplied['education_level'] }}</span>
            @endif
            @if(isset($filtersApplied['age_range']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Faixa etária: {{ $filtersApplied['age_range'] }} anos</span>
            @endif
            @if(isset($filtersApplied['admission_range']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Tempo de admissão: {{ $filtersApplied['admission_range'] }} anos</span>
            @endif
            @if(isset($filtersApplied['year']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Ano de realização dos testes: {{ $filtersApplied['year'] }}</span>
            @endif
        </div>

        <x-action href="{{ route(Route::currentRouteName(), request()->route()->parameters()) }}">
            <i class="fa-solid fa-filter-circle-xmark"></i>
        </x-action>
    </div>
@endif