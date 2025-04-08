<?php

namespace App\Http\Controllers\Private;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserController
{
    protected $employees;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $this->employees = User::whereRelation('companies', 'companies.id', session('company')->id);

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $employees = User::
        whereRelation('companies', 'companies.id', session('company')->id)
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

    public function createFirstUser($companyToCreate){
        dd($companyToCreate);
        return view('auth.register.user', [
            'companyToCreate' => $companyToCreate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rolesToSelect = $this->getRolesToSelect();

        return view('admin.create-employee-profile', [
            'rolesToSelect' => $rolesToSelect,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $userData = $request->only(['name', 'cpf', 'age', 'gender', 'department', 'occupation', 'admission']);
        $userRole = $request->only('role');

        $admissionDateFormated = Carbon::parse($userData['admission'])->format('d/m/Y');

        $employee = User::create([
            "name" => $userData['name'],
            "cpf" => $userData['cpf'],
            "age" => $userData['age'],
            "gender" => $userData['gender'],
            "department" => $userData['department'],
            "occupation" => $userData['occupation'],
            "admission" => $admissionDateFormated,
            "company_id" => session('company')->id,
        ]);

        DB::table('company_users')->insert([
            'role_id' => $userRole['role'],
            'user_id' => $employee->id,
            'company_id' => session('company')->id
        ]);

        return to_route('employee.index')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $admission = $this->getFormattedAdmissionDate($employee);

        $lastTestCollection = DB::table('user_collections')->where('user_id', $employee->id)
            ->latest('created_at')
            ->first();

        
        $lastTestCollectionDate = null;
        $testResults = null;
            
        if ($lastTestCollection) {
            $collectionDateTime = Carbon::parse($lastTestCollection->created_at);
            $formattedDate = $collectionDateTime->format('d/m/Y');

            $diff = Carbon::now()->startOfDay()->diff($collectionDateTime->startOfDay());

            if ($diff->y >= 1) {
                $timeAgo = $diff->y . ' ano(s)';
            } elseif ($diff->m >= 1) {
                $timeAgo = $diff->m . ' mês(es)';
            } else {
                $timeAgo = $diff->d . ' dia(s)';
            }

            $lastTestCollectionDate = "{$formattedDate} - {$timeAgo} atrás.";
        }

        $cpf = $this->getFormattedCPF($employee);

        $employeeInfo = [
            'Nome'              => $employee->name,
            'CPF'               => $cpf,
            'Idade'             => $employee->age,
            'Setor'             => $employee->department,
            'Cargo'             => $employee->occupation,
            'Sexo'              => $employee->gender,
            'Data de Admissão'  => $employee->admission,
            'Tempo de empresa'  => $admission,
        ];

        if ($lastTestCollectionDate) {
            $employeeInfo['Último teste realizado'] = $lastTestCollectionDate;
        }

        return view('admin.employee-profile', [
            'employee'     => $employee,
            'employeeInfo' => $employeeInfo,
            // 'testResults'  => $testResults,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, User $employee)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }
        
        $rolesToSelect = $this->getRolesToSelect();

        $currentUserRole = DB::table('company_users')->where('user_id', '=', $employee->id)->where('company_id', session('company')->id)->first()->role_id;
 
        return view('admin.update-employee-profile', [
            'employee' => $employee,
            'rolesToSelect' => $rolesToSelect,
            'currentUserRole' => $currentUserRole,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $validatedData = $request->validate([
            "name" => ['required', 'max:70'],
            "cpf" => ['required', 'max:70'],
            "age" => ['required', 'max:70'],
            "gender" => ['required', 'max:70'],
            "department" => ['required', 'max:70'],
            "occupation" => ['required', 'max:70'],
            "role" => ['required', 'max:70'],
        ]);

        $userData = $request->only(['name', 'cpf', 'age', 'gender', 'department', 'occupation']);
        $userRole = $request->only('role');
        
        DB::table('company_users')->where('user_id', '=', $employee->id)->where('company_id', session('company')->id)->update(['role_id' => $userRole['role']]);

        $employee->update($userData);


        if(Auth::user()->id == $employee->id){
            session(['user' => $employee]);
        }

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        $employee->delete();

        return to_route('employee.index');
    }


    private function getDepartmentsToFilter(){
        $departments = array_unique($this->employees->pluck('department')->toArray());

        return $departments;
    }
    
    private function getOccupationsToFilter(){
        $occupations = array_unique($this->employees->pluck('occupation')->toArray());
        
        return $occupations;
    }

    private function getFormattedAdmissionDate($employee){
        $admissionDate = Carbon::createFromFormat('d/m/Y', $employee->admission);
        $admissionDiff = Carbon::now()
                            ->diff($admissionDate)
                            ->format('%y anos, %m meses e %d dias.');

        return $admissionDiff;
    }

    private function getFormattedCPF($employee){
        $cpfFormatted =  preg_replace(
            '/(\d{3})(\d{3})(\d{3})(\d{2})/',
            '$1.$2.$3-$4',
            $employee->cpf
        );

        return $cpfFormatted;
    }

    private function getRolesToSelect(){
        $roles = DB::table('roles')->orderBy('id', 'desc')->get()->toArray();
        $rolesFormatted = [];

        foreach($roles as $role){
            $rolesFormatted[] = ['option'=> $role->name, 'value' => $role->id];
        }

        return $rolesFormatted;
    }
}
