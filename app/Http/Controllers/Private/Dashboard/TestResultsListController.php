<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestResultsListController
{
    protected $company;

    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
        $this->company = $this->helper->getCompanyUsers(hasCollection: true);
    }

    public function __invoke(Request $request, string $testName)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $departmentsToFilter = $this->getDepartmentsToFilter();
        $occupationsToFilter = $this->getOccupationsToFilter();

        $usersList = $this->getCompiledResults($testName, $queryStringName, $queryStringDepartment, $queryStringOccupation);

        return view('private.dashboard.test-results-list', compact(
            'testName',
            'usersList',
            'departmentsToFilter',
            'occupationsToFilter',
            'queryStringName',
            'queryStringDepartment',
            'queryStringOccupation'
        ));
    }

    /**
     * Retorna um objeto Company com os usuarios e seus testes.
     */
    private function getCompiledResults(string $testName, ?string $queryStringName, ?string $queryStringDepartment, ?string $queryStringOccupation): Company
    {
        $testCompiled = $this->helper->getCompanyUsersCollections(
            justEssentials: true,
            testName: $testName,
            queryStringName: $queryStringName,
            queryStringDepartment: $queryStringDepartment,
            queryStringOccupation: $queryStringOccupation
        );

        return $testCompiled;
    }

    /**
     * Retorna um array com os setores para filtrar.
     */
    private function getDepartmentsToFilter(): array
    {
        $departments = array_unique($this->company->users->pluck('department')->toArray());

        return $departments;
    }

    /**
     * Retorna um array com os cargos para filtrar.
     */
    private function getOccupationsToFilter(): array
    {
        $occupations = array_unique($this->company->users->pluck('occupation')->toArray());

        return $occupations;
    }
}
