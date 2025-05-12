<?php

namespace App\Http\Controllers\Private\Dashboard\Demographics;

use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DemographicsMainController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request)
    {
        $this->pageData = $this->pageQuery();

        $companyMetrics = $this->getCompanyMetrics();
        $demographics = $this->getCompanyDemographics();

        return view('private.dashboard.demographics.index', [
            'companyMetrics' => $companyMetrics,
            'demographics' => $demographics,
        ]);
    }

    private function pageQuery()
    {
        $pageData = session('company')
            ->users()
            ->select('users.id', 'users.department', 'users.gender', 'users.admission', 'users.birth_date')
            ->get();

        return $pageData;
    }

    private function getCompanyMetrics()
    {
        $metrics = session('company')->metrics()->get()->groupBy('metricType.display_name');
        $metricsCompiled = [];
        
        foreach ($metrics as $metricName => $metric) {
            $metricsCompiled[$metricName] = (int) $metric[0]->value ?? 0;
        }

        return $metricsCompiled;
    }

    private function getCompanyDemographics()
    {
        $users = $this->pageData;

        if(!$users->count()){
            return null;
        }

        $usersByDepartmentCount = $users->groupBy('department')->map(function ($group) {
            return [
                'count' => $group->count(),
                'per_cent' => ($group->count() / session('company')->users->count()) * 100
            ];
        });


        $usersByGenderCount = $users->groupBy('gender')->map(function ($group) {
            return [
                'count' => $group->count(),
                'per_cent' => ($group->count() / session('company')->users->count()) * 100
            ];
        });

        $usersByAdmissionPeriod = $users->groupBy(function ($user) {
            $years = Carbon::parse($user->admission)->diffInYears(Carbon::now());

            return match (true) {
                $years <= 1 => '0-1 ano',
                $years <= 4 => '2-4 anos',
                $years <= 10 => '5-10 anos',
                default => 'Mais de 10 anos',
            };
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'per_cent' => ($group->count() / session('company')->users->count()) * 100
            ];
        });

        $usersByAgeGroup = $users->groupBy(function ($user) {
            $age = Carbon::parse($user->birth_date)->age;

            return match (true) {
                $age <= 25 => '18-25 anos',
                $age <= 35 => '26-35 anos',
                $age <= 45 => '36-45 anos',
                default => 'Mais de 45 anos'
            };
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'per_cent' => ($group->count() / session('company')->users->count()) * 100
            ];
        });

        $demographicsCompiled['Por sexo'] = $usersByGenderCount;
        $demographicsCompiled['Por período de admissão'] = $usersByAdmissionPeriod;
        $demographicsCompiled['Por faixa etária'] = $usersByAgeGroup;
        $demographicsCompiled['Por departamento'] = $usersByDepartmentCount;

        return $demographicsCompiled;
    }
}
