<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['nome_completo'],
            'age' => $row['idade'],
            'cpf' => $row['cpf'],
            'department' => $row['setor'],
            'occupation' => $row['cargo'],
            'admission' => $row['admissao'],
            'gender' => $row['sexo'],
            'company_id' => session('company')->id,
        ]);
    }
}
