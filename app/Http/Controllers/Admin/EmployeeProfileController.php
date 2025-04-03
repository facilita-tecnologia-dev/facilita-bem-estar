<?php

namespace App\Http\Controllers\Admin;

use App\Models\TestCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class EmployeeProfileController
{
    public function __invoke(User $employee){
        $admissionDate = Carbon::createFromFormat('d/m/Y', $employee->admission);
        $now = Carbon::now();
        $admissionDiff = $now->diff($admissionDate)->format('%y anos, %m meses e %d dias.');

        $lastTestCollection = TestCollection::query()->where('user_id', '=', $employee->id)->whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->first();

        $lastTestCollectionDateTime = $lastTestCollection->created_at;
        $lastTestCollectionDateTimeFormatted = $lastTestCollectionDateTime->format('d/m/Y');

        $lastTestCollectionDateDiffFromNow = Carbon::now()->startOfDay()->diff($lastTestCollectionDateTime->startOfDay());

        if ($lastTestCollectionDateDiffFromNow->y >= 1) {
            $lastTestCollectionDateDiffFromNowFormatted = $lastTestCollectionDateDiffFromNow->y . ' ano(s)';
        } elseif ($lastTestCollectionDateDiffFromNow->m >= 1) {
            $lastTestCollectionDateDiffFromNowFormatted = $lastTestCollectionDateDiffFromNow->m . ' mês(es)';
        } else {
            $lastTestCollectionDateDiffFromNowFormatted = $lastTestCollectionDateDiffFromNow->d . ' dia(s)';
        }

        $lastTestCollectionDate = $lastTestCollectionDateTimeFormatted . ' - '. $lastTestCollectionDateDiffFromNowFormatted . ' atrás.';

        $cpfFormatted = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $employee->cpf);

        $employeeInfo = [];

        $employeeInfo['Nome'] = $employee->name;
        $employeeInfo['CPF'] = $cpfFormatted;
        $employeeInfo['Idade'] = $employee->age;
        $employeeInfo['Setor'] = $employee->department;
        $employeeInfo['Cargo'] = $employee->occupation;
        $employeeInfo['Sexo'] = $employee->gender;
        $employeeInfo['Data de Admissão'] = $employee->admission;
        $employeeInfo['Tempo de empresa'] = $admissionDiff;
        $employeeInfo['Último teste realizado'] = $lastTestCollectionDate;


        // --------------------------------------------------------


        $lastUserTestCollection = TestCollection::whereIn('created_at', function($query) use($employee){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->where('user_id', '=', $employee->id);
        })->with('tests')->first()->toArray();

        // dump($lastUserTestCollection);

        $testResults = $lastUserTestCollection['tests']; 


        return view('admin.employee-profile', [
            'employee' => $employee,
            'employeeInfo' => $employeeInfo,
            'testResults' => $testResults,
        ]);
    }
}
