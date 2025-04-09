<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\TestForm;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestResultsListController
{
    protected $employees;
    protected $test;

    public function __construct()
    {
        $this->employees = User::whereRelation('companies', 'companies.id', session('company')->id)->get();
    }

    public function __invoke(Request $request, $test){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $this->test = TestType::where('display_name', $test)->first();

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;
        
       

        $usersList = User::
            whereRelation('companies', 'companies.id', session('company')->id)
            ->has('collections')
            ->when($queryStringName, function($query) use($queryStringName) {
                $query->where('name', 'like', "%$queryStringName%");
            })
            ->when($queryStringDepartment, function($query) use($queryStringDepartment) {
                $query->where('department', '=', "$queryStringDepartment");
            })
            ->when($queryStringOccupation, function($query) use($queryStringOccupation) {
                $query->where('occupation', '=', "$queryStringOccupation");
            })
            ->select(['id', 'name', 'occupation', 'department'])
            ->with('collections', function($query) use($test){
                $query->latest()->with('tests', function($q) use($test){
                    $q->where('test_id', '=', $this->test->id)->select(['id', 'test_id', 'user_collection_id', 'severity_title', 'severity_color'])->get();
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        $departmentsToFilter = $this->getDepartmentsToFilter();
        $occupationsToFilter = $this->getOccupationsToFilter();
        // $severitiesToFilter = $this->getSeveritiesToFilter($test);

        return view('private.dashboard.test-results-list', [
            'testName' => $test,
            'usersList' => $usersList,

            'departmentsToFilter' => $departmentsToFilter,
            'occupationsToFilter' => $occupationsToFilter,

            'queryStringName' => $queryStringName,
            'queryStringDepartment' => $queryStringDepartment,
            'queryStringOccupation' => $queryStringOccupation,
        ]);
    }

    private function getDepartmentsToFilter(){
        $departments = array_unique($this->employees->pluck('department')->toArray());

        return $departments;
    }
    
    private function getOccupationsToFilter(){
        $occupations = array_unique($this->employees->pluck('occupation')->toArray());
        
        return $occupations;
    }

    // private function getSeveritiesToFilter($test){
    //     // $severities = 
    //     dd($test)
    // }
}
