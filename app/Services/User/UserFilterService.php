<?php

namespace App\Services\User;

use App\Filters\AdmissionRangeFilter;
use App\Filters\AgeRangeFilter;
use App\Filters\CPFFilter;
use App\Filters\DepartmentFilter;
use App\Filters\DepartmentScopeFilter;
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
use Illuminate\Support\Facades\DB;

class UserFilterService
{
    /**
     * @var UserFilterInterface[]
     */
    protected array $filters = [
        DepartmentScopeFilter::class,
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

    public function sort(Builder $query): Builder
    {
        $orderBy = request('order_by') ?? null;

        if ($orderBy) {

            $direction = strtolower(request('order_direction', 'asc')) === 'desc' ? 'desc' : 'asc';

            if ($orderBy == 'psychosocial-risks') {
                $latestPsychosocialSub = DB::table('user_collections')
                    ->select('user_id', DB::raw('MAX(created_at) as latest_created_at'))
                    ->where('collection_id', 1)
                    ->groupBy('user_id');

                return $query->leftJoinSub($latestPsychosocialSub, 'latest_psychosocial', function ($join) {
                    $join->on('users.id', '=', 'latest_psychosocial.user_id');
                })->orderBy('latest_psychosocial.latest_created_at', $direction);
            }

            if ($orderBy == 'organizational-climate') {
                $latestOrganizationalSub = DB::table('user_collections')
                    ->select('user_id', DB::raw('MAX(created_at) as latest_created_at'))
                    ->where('collection_id', 2)
                    ->groupBy('user_id');

                return $query->leftJoinSub($latestOrganizationalSub, 'latest_organizational', function ($join) {
                    $join->on('users.id', '=', 'latest_organizational.user_id');
                })->orderBy('latest_organizational.latest_created_at', $direction);
            }

            $column = $this->customSorts[$orderBy] ?? $orderBy;

            return $query->orderBy($column, $direction);
        }

        return $query;
    }

    public function getFilterDisplayName(string $filterKeyName): string
    {
        return match ($filterKeyName) {
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
