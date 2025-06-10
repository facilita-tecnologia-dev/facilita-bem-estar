<?php

namespace App\Services;

use App\Helpers\AuthGuardHelper;
use App\Helpers\SessionErrorHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class AuthService {
    public function authenticate(Authenticatable $actor, array $data){
        $redirectRoute = '';

        if($actor instanceof User){

            if($this->userHasManyCompanies($actor)){
                return route('auth.login.usuario-interno.escolher-empresa', $actor);
            }

            CompanyService::loadCompanyToSession($actor->companies->first());

            if($this->userIsManager($actor)){
                return route('auth.login.gestor.senha', $actor);
            }

            $this->login($actor);
        }

        if($actor instanceof Company){
            $this->checkPasswordHash($data['password'], $actor->password);

            $this->login($actor);

            CompanyService::loadCompanyToSession($actor);
        }

        return $this->getRedirectLoginRoute($actor);
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

    public function logout(Request $request)
    {
        $redirectRoute = $this->getRedirectLogoutRoute(AuthGuardHelper::user());

        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        }

        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $redirectRoute;
    }

    public function userHasManyCompanies(User $user)
    {
        return $user->companies->count() > 1;
    }

    public function userIsManager(User $user)
    {
        return $user->hasRole('manager');
    }

    public function getRedirectLoginRoute(Authenticatable $actor)
    {
        if($actor instanceof Company){
            return route('welcome.company');
        }

        if($actor instanceof User){
            $userRoleName = $actor->roleInCompany(session('company'))->name;

            if($userRoleName == 'manager'){
                return route('dashboard.psychosocial');
            }
            
            if($userRoleName == 'employee'){
                return route('welcome.user');
            }
        }
    }

    public function getRedirectLogoutRoute(Authenticatable $actor)
    {
        return $actor instanceof Company ? route('auth.login.empresa') : route('auth.login.usuario-interno');
    }

    public function checkPasswordHash(string $value, string $hashed)
    {
        if(!Hash::check($value, $hashed)){ 
            SessionErrorHelper::flash('password', 'A senha está incorreta.');
            return false;
        }
        
        return true;
    }

    public static function createTempPassword(): string
    {
        return 'temp_' . bin2hex(random_bytes(5));
    }
}