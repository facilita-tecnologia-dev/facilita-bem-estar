<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Risk;
use App\RiskEvaluations\RiskEvaluatorFactory;
use App\RiskEvaluations\RiskEvaluatorInterface;

class CompanyService
{
    public static function loadCompanyToSession(Company $company): void
    {
        session(['company' => $company->load([
            'users', 
            'actionPlan.controlActions.risk', 
            'metrics', 
            'campaigns', 
            'customCollections'
        ])]);
    }
}
