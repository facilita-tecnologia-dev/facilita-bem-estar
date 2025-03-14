<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLoginController
{
    public function index(){
        return view('auth.login-user');
    }

    public function attemptLogin(Request $request){
        $validatedData = $request->validate([
            'cpf' => ['required', 'size:11']
        ]);

        $user = User::query()->where('cpf', '=', $validatedData['cpf'])->first();

        $userRole = '';
        if($user){
            $userRole = DB::table('role_user')->where('role_id', '=', 2)->where('user_id', '=', $user->id)->get();
        }

        if(!$user || !(count($userRole) > 0)){
            return back()->with('errorMessage', 'O usuário não existe.');
        }

        Auth::login($user);

        return to_route('welcome');
    }
}
