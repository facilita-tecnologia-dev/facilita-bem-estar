<?php

namespace App\Services\User;

use App\Filters\AdmissionRangeFilter;
use App\Filters\AgeRangeFilter;
use App\Filters\CPFFilter;
use App\Filters\DepartmentFilter;
use App\Filters\EducationLevelFilter;
use App\Filters\GenderFilter;
use App\Filters\HasAnsweredOrganizationalFilter;
use App\Filters\HasAnsweredPsychosocialFilter;
use App\Filters\MaritalStatusFilter;
use App\Filters\NameFilter;
use App\Filters\OccupationFilter;
use App\Filters\WorkShiftFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

class UserFilterService
{
    /**
     * @var UserFilterInterface[]
     */
    protected array $filters = [
        NameFilter::class,
        CPFFilter::class,
        GenderFilter::class,
        DepartmentFilter::class,
        OccupationFilter::class,
        WorkShiftFilter::class,
        MaritalStatusFilter::class,
        EducationLevelFilter::class,
        AgeRangeFilter::class,
        AdmissionRangeFilter::class,
        HasAnsweredPsychosocialFilter::class,
        HasAnsweredOrganizationalFilter::class,
        // Adicione mais filtros aqui
    ];

    /**
     * @var array<string, string>
     */
    protected array $customSorts = [
        'age' => 'birth_date',
    ];

    public function apply(Builder $query): Builder
    {
        return app(Pipeline::class)
            ->send($query)
            ->through($this->filters)
            ->thenReturn();
    }

    public function sort(Builder $query): Builder{ 
        $orderBy = request('order_by') ?? null;
    
        if($orderBy){
            $direction = strtolower(request('order_direction', 'asc')) === 'desc' ? 'desc' : 'asc';
            
            $column = $this->customSorts[$orderBy] ?? $orderBy;
            
            return $query->orderBy($column, $direction);
        }

        return $query;
    }

    public function getFilterDisplayName(string $filterKeyName): string
    {
        return match($filterKeyName){
            'name' => 'Nome',
            'cpf' => 'CPF',
            'gender' => 'Gênero',
            'department' => 'Setor',
            'occupation' => 'Função',
            'work_shift' => 'Turno',
            'marital_status' => 'Estado Civil',
            'education_level' => 'Grau de Instrução',
            'age_range' => 'Faixa Etária',
            'admission_range' => 'Tempo de Empresa',
            'year' => 'Ano de realização dos testes',
            default => '',
        };
    }
}
