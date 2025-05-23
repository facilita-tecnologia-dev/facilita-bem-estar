<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $birth_date = $this->convertDate($row['data_de_nascimento']);
            $admission = $this->convertDate($row['admissao']);

            if ($row['nome_completo'] != null) {
                $user = User::firstWhere('cpf', $row['cpf']);

                if($user){
                    $user->companies()->syncWithoutDetaching([session('company')->id => ['role_id' => 2]]);
                    return;
                }

                $user = User::create([
                    'name' => $row['nome_completo'],
                    'birth_date' => $birth_date,
                    'cpf' => $row['cpf'],
                    'department' => $row['setor'],
                    'occupation' => $row['cargo'],
                    'work_shift' => $row['turno'],
                    'admission' => $admission,
                    'gender' => $row['sexo'],
                    'marital_status' => $row['estado_civil'],
                    'education_level' => $row['grau_de_instrucao'],
                ]);

                DB::table('company_users')->insert([
                    'user_id' => $user->id,
                    'company_id' => session('company')->id,
                    'role_id' => 2,
                ]);

                return $user;
            }

            return null;
        });
    }

    private function convertDate($value)
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // ou lançar exceção/logar
        }
    }
}
