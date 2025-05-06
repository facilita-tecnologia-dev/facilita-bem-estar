<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\User;
use App\Services\User\UserElegibilityService;
use Illuminate\Support\Facades\Gate;

class LoginRedirectService
{
    protected UserElegibilityService $elegibilityService;

    public function __construct(UserElegibilityService $elegibilityService){
        $this->elegibilityService = $elegibilityService;
    }

    public function getRedirectRoute(User $user, ): string | bool
    {
        $isManager = $user->hasRole('internal-manager');

        $canAccessPsychosocial = Gate::allows('access-psychosocial');
        $canAccessOrganizational = Gate::allows('access-organizational');

        if($canAccessPsychosocial){
            if($isManager){
                return route('dashboard.psychosocial');
            } else{
                $hasAnsweredCurrentTest = $this->elegibilityService->hasAnsweredPsychosocialCollection($user);

                if($hasAnsweredCurrentTest){
                    return route('responder-teste.thanks');
                }

                return route('responder-teste', Collection::where('key_name', 'psychosocial-risks')->first());
            }
        }

        if($canAccessOrganizational){
            if($isManager){
                return route('dashboard.organizational-climate');
            } else{
                $hasAnsweredCurrentTest = $this->elegibilityService->hasAnsweredOrganizationalCollection($user);

                if($hasAnsweredCurrentTest){
                    return route('responder-teste.thanks');
                }

                return route('responder-teste', Collection::where('key_name', 'organizational-climate')->first());
            }
        }

        return false;
    }
}
