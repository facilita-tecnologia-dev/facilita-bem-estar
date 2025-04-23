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
            @if(isset($filtersApplied['year']))
                <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Ano de realização dos testes: {{ $filtersApplied['year'] }}</span>
            @endif
        </div>

        <a href="{{ route(Route::currentRouteName(), request()->route()->parameters()) }}" class="bg-gray-100/50 w-10 h-10 aspect-square rounded-md flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-200 transition">
            <i class="fa-solid fa-filter-circle-xmark"></i>
        </a>
    </div>
@endif