<?php

namespace App\Http\Controllers\Admin;

use App\Models\TestCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class UserInfoController
{
    public function index(User $user){
        $admissionDate = Carbon::parse($user->admission);
        $now = Carbon::now();
        $admissionDiff = $now->diff($admissionDate)->format('%y anos, %m meses e %d dias.');

        $lastTestCollection = TestCollection::query()->where('user_id', '=', $user->id)->whereIn('created_at', function($query){
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


        $cpfFormatted = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $user->cpf);

        $userInfo = [];

        $userInfo['Nome'] = $user->name;
        $userInfo['CPF'] = $cpfFormatted;
        $userInfo['Idade'] = $user->age;
        $userInfo['Setor'] = $user->department;
        $userInfo['Cargo'] = $user->occupation;
        $userInfo['Sexo'] = $user->gender;
        $userInfo['Tempo de empresa'] = $admissionDiff;
        $userInfo['Último teste realizado'] = $lastTestCollectionDate;


        // --------------------------------------------------------


        $lastUserTestCollection = TestCollection::whereIn('created_at', function($query) use($user){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->where('user_id', '=', $user->id);
        })->with('tests')->first()->toArray();

        // dump($lastUserTestCollection);

        $testResults = $lastUserTestCollection['tests']; 


        return view('admin.user-info', [
            'user' => $user,
            'userInfo' => $userInfo,
            'testResults' => $testResults,
        ]);
    }
}
