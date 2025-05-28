@props([
    'filtersApplied' => [],
])

@if($filtersApplied)
    @if(isset($filtersApplied['name']))
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Nome: {{ $filtersApplied['name'] }}</span>
    @endif
    @if(isset($filtersApplied['cpf']))
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">CPF: {{ $filtersApplied['cpf'] }}</span>
    @endif
    @if(isset($filtersApplied['department']) && count($filtersApplied['department']) > 0)
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">
            Setor:
            @foreach ($filtersApplied['department'] as $department)
                {{ $department }}
                @if(!$loop->last), @endif
            @endforeach
        </span>
    @endif
    @if(isset($filtersApplied['occupation']) && count($filtersApplied['occupation']) > 0)
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">
            Função:
            @foreach ($filtersApplied['occupation'] as $occupation)
                @if(!$loop->first), @endif
                {{ $occupation }}
            @endforeach
        </span>
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
    @if(isset($filtersApplied['has_answered_psychosocial']))
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Testes de Riscos Psicossociais: {{ $filtersApplied['has_answered_psychosocial'] }}</span>
    @endif
    @if(isset($filtersApplied['has_answered_organizational']))
        <span class="block px-3 py-1 rounded-md bg-gray-100/50 text-xs sm:text-sm">Testes de Clima Organizacional: {{ $filtersApplied['has_answered_organizational'] }}</span>
    @endif
@endif