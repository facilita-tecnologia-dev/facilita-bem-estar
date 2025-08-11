<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsFailures, SkipsErrors;

    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            if ($row['nome_completo'] != null) {
                $birth_date = $this->convertDate($row['data_de_nascimento']) ?? '';
                $admission = $this->convertDate($row['admissao']);
                $user = User::firstWhere('cpf', $row['cpf']);

                if ($user) {
                    $user->update([
                        'name' => $row['nome_completo'],
                        'birth_date' => $birth_date,
                        'department' => $row['setor'],
                        'occupation' => $row['cargo'],
                        'work_shift' => $row['turno'],
                        'admission' => $admission,
                        'gender' => $row['sexo'] ?? '',
                        'marital_status' => $row['estado_civil'] ?? '',
                        'education_level' => $row['grau_de_instrucao'] ?? '',
                        'email' => $row['email'] ?? '',
                    ]);
                
                    $user->companies()->syncWithoutDetaching([
                        session('company')->id => ['role_id' => 2]
                    ]);
                
                    return $user;
                }

                $user = User::create([
                    'name' => $row['nome_completo'],
                    'birth_date' => $birth_date,
                    'cpf' => $row['cpf'],
                    'department' => $row['setor'],
                    'occupation' => $row['cargo'],
                    'work_shift' => $row['turno'],
                    'admission' => $admission,
                    'gender' => $row['sexo'] ?? '',
                    'marital_status' => $row['estado_civil'] ?? '',
                    'education_level' => $row['grau_de_instrucao'] ?? '',
                    'email' => $row['email'] ?? '',
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

    public function rules(): array
    {
        return [
            'nome_completo' => ['required'],
            'data_de_nascimento' => ['nullable'],
            'cpf' => ['required'],
            'setor' => ['required'],
            'cargo' => ['required'],
            'turno' => ['required'],
            'admissao' => ['required'],
            'sexo' => ['nullable'],
            'estado_civil' => ['nullable'],
            'grau_de_instrucao' => ['nullable'],
            'email' => ['nullable'],
        ];
    }

    private function convertDate($value)
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
