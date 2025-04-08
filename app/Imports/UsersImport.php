<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    private $company;

    public function __construct(Company $company)
    {
        $this->company = $company;  
    }
    public function model(array $row)
    {
        DB::transaction(function() use($row){
            $user = User::create([
                'name' => $row['nome_completo'],
                'age' => $row['idade'],
                'cpf' => $row['cpf'],
                'department' => $row['setor'],
                'occupation' => $row['cargo'],
                'admission' => $row['admissao'],
                'gender' => $row['sexo'],
            ]);
            
            DB::table("company_users")->insert([
                'user_id' => $user->id,
                'company_id' => session('company')->id,
                'role_id' => 2,
            ]);
        });
    }
}
