<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\TestForm;
use App\Models\User;
use Illuminate\Http\Request;

class TestResultsListController
{
    protected $employees;
    protected $test;

    public function __construct()
    {
        $this->employees = User::where('company_id', '=', session('company')->id)->get();
    }

    public function __invoke(Request $request, $test){
        $this->test = $test;

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;
        $queryStringSeverities = $request->severities;
        

        $usersList = User::query()
            ->where('company_id', '=', session('company')->id)
            ->has('testCollections')
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
            ->with('testCollections', function($query) use($test){
                $query->latest()->with('tests', function($q) use($test){
                    $q->where('test_name', '=', $test)->select(['id', 'test_collection_id', 'severity_title', 'severity_color'])->get();
                });
            })
            ->orderBy('name', 'asc')
            ->get();

        $departmentsToFilter = $this->getDepartmentsToFilter();
        $occupationsToFilter = $this->getOccupationsToFilter();

        return view('admin.dashboard.test-results-list', [
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
}
