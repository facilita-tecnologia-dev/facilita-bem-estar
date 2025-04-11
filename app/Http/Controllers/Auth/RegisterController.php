<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterExternalRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController
{
    public function __invoke(Request $request)
    {
        return view('auth.register.index');
    }

    public function attemptInternalRegister(Request $request){
        dd($request);
    }

    public function attemptExternalRegister(RegisterExternalRequest $request){
        DB::transaction(function() use($request){
            $user = User::create([
                'name' => $request->validated('name'), 
                'cpf' => $request->validated('cpf'), 
                'password' => Hash::make($request->validated('password')), 
            ]);

            DB::table('company_users')->insert([
                'role_id' => 3,
                'user_id' => $user->id,
                'company_id' => null,
            ]);

            Auth::login($user);
        });
        
        return to_route('choose-test');
    }
}
