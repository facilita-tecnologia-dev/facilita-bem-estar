<?php

namespace App\Http\Controllers\Private;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeesListController
{
    protected $employees;

    public function __invoke(Request $request){
        $this->employees = User::where('company_id', '=', session('company')->id);

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $employees = User::
        where('company_id', '=', session('company')->id)
        ->when($queryStringName, function($query) use($queryStringName){
            return $query->where('name', 'like', "%$queryStringName%");
        })
        ->when($queryStringDepartment, function($query) use($queryStringDepartment){
            return $query->where('department', '=', "$queryStringDepartment");
        })
        ->when($queryStringOccupation, function($query) use($queryStringOccupation){
            return $query->where('occupation', '=', "$queryStringOccupation");
        })
        ->get();
        
        $departmentsToFilter = $this->getDepartmentsToFilter();
        $occupationsToFilter = $this->getOccupationsToFilter();

        return view('admin.employees-list', [
            'employees' => $employees,

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
