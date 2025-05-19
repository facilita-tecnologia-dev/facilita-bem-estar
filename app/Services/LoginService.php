<?php

namespace App\Services;

use App\Helpers\SessionErrorHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class LoginService {
    public function attemptLogin(Authenticatable $actor, array $data){
        if($actor instanceof User){
            $user = $actor;
            
            $userCompanies = $user->companies;

            if ($user->companies->count() > 1) {
                return route('auth.login.usuario-interno.escolher-empresa', $user);
            }
        
            session(['company' => $userCompanies->first()]);

            if($user->hasRole('manager')){
                return route('auth.login.gestor.senha', $user);
            }

            $this->login($user);
        }

        if($actor instanceof Company){
            $company = $actor;

            if(!Hash::check($data['password'], $company->password)){ 
                SessionErrorHelper::flash('password', 'A senha está incorreta.');
                return url()->previous();
            }

            $this->login($company);

            session(['company' => $company]);
        }

        return $this->getRedirectRoute($actor);
    }


    public function getRedirectRoute(Authenticatable $actor){

        if($actor instanceof Company){
            $company = $actor;

            if($company->hasCompletedBasicData()){
                return route('dashboard.psychosocial');
            } else{
                return route('welcome.company');
            }
        }

        if($actor instanceof User){
            $user = $actor;

            $userRoleId = $user->roles[0]->id;
            
            if($userRoleId == 1){
                return route('dashboard.psychosocial');
            }
            
            if($userRoleId == 2){
                return route('welcome.user');
            }
        }
    }

    public function login(Authenticatable $actor)
    {
        if($actor instanceof User){
            Auth::guard('user')->login($actor);
        }
        
        if($actor instanceof Company){
            Auth::guard('company')->login($actor);
        }

        session()->regenerate();
    }
}