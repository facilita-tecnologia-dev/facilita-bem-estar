<?php

namespace App\Http\Controllers\Private;

use App\Models\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EmployeeProfileController
{
    protected $employee;

    public function __invoke(User $employee){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $this->employee = $employee;

        $admission = $this->getFormattedAdmissionDate();

        $lastTestCollection = DB::table('user_collections')->where('user_id', $employee->id)
            // ->with('tests')
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

            // $testResults = $lastTestCollection->tests->toArray();
        }

        $cpf = $this->getFormattedCPF();

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

    private function getFormattedAdmissionDate(){
        $admissionDate = Carbon::createFromFormat('d/m/Y', $this->employee->admission);
        $admissionDiff = Carbon::now()
                            ->diff($admissionDate)
                            ->format('%y anos, %m meses e %d dias.');

        return $admissionDiff;
    }

    private function getFormattedCPF(){
        $cpfFormatted =  preg_replace(
            '/(\d{3})(\d{3})(\d{3})(\d{2})/',
            '$1.$2.$3-$4',
            $this->employee->cpf
        );

        return $cpfFormatted;
    }

}
