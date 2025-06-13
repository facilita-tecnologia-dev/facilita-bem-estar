<?php

namespace App\Http\Controllers\Private;

use Illuminate\Http\Request;

class WelcomeController
{
    public function welcomeCompany()
    {
        $neededActions = true;
        $currentStep = 1;

        $companyLogo = session('company')->logo;
        $companyUsers = session('company')->users->count();
        $companyManager = session('company')->roles->where('name', 'manager')->isNotEmpty();
        $companyMetrics = session('company')->metrics()->where('value', '!=', null)->exists();
        $companyCampaigns = session('company')->campaigns()->exists();

        if($companyLogo){$currentStep++;}
        if($companyUsers){$currentStep++;}
        if($companyManager){$currentStep++;}
        if($companyMetrics){$currentStep++;}
        if($companyCampaigns){$neededActions = false;}


        return view('private.welcome.company', [
            'neededActions' => $neededActions,
            'currentStep' => $currentStep,
        ]);
    }

    public function welcomeUser()
    {
        return view('private.welcome.user');
    }

}
