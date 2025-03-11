<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserInfoController
{
    public function index(User $user){
        
        $userInfo = [];

        $userInfo['Nome'] = $user->name;
        $userInfo['E-mail'] = $user->email;
        $userInfo['Idade'] = $user->age;
        $userInfo['Setor'] = $user->occupation;
        $userInfo['Sexo'] = $user->gender;

        // foreach($user->toArray() as $userDataName => $userData){
        //     if()

        //     dump([$userDataName => $userData]);
        // }

        return view('users.user-info', [
            'userInfo' => $userInfo,
        ]);
    }
}
