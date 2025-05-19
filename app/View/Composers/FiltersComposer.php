<?php

namespace App\View\Composers;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use App\Helpers\AuthGuardHelper;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class FiltersComposer
{
    public function compose(View $view): void
    {
        /** @var User $user */
        $user = AuthGuardHelper::user();
        $departmentScopes = $user->departmentScopes()->where('allowed', true)->get()->pluck('department');
        $companyUsers = Company::firstWhere('id', session('company')->id)
        ->users()
        ->whereIn('department', $departmentScopes)
        ->get();

        $gendersToFilter = array_keys($companyUsers->groupBy('gender')->toArray());

        $departmentsToFilter = array_keys($companyUsers->groupBy('department')->toArray());
        $occupationsToFilter = array_keys($companyUsers->groupBy('occupation')->toArray());

        $workShiftsToFilter = array_filter(
            array_keys($companyUsers->groupBy('work_shift')->toArray()),
            fn ($q) => $q !== ''
        );
        $maritalStatusToFilter = array_filter(
            array_keys($companyUsers->groupBy('marital_status')->toArray()),
            fn ($q) => $q !== ''
        );
        $educationLevelsToFilter = array_filter(
            array_keys($companyUsers->groupBy('education_level')->toArray()),
            fn ($q) => $q !== ''
        );

        $ageRangesToFilter = collect(AgeRangeEnum::cases())->map(fn ($i) => [
            'option' => $i->value.' anos',
            'value' => $i->value,
        ]);

        $admissionRangesToFilter = collect(AdmissionRangeEnum::cases())->map(fn ($i) => [
            'option' => $i->value.' anos',
            'value' => $i->value,
        ]);

        $yearsTofilter = [
            Carbon::now()->year,
            Carbon::now()->subYear()->year,
            Carbon::now()->subYears(2)->year,
            Carbon::now()->subYears(3)->year,
            Carbon::now()->subYears(4)->year,
        ];

        $hasAnsweredPsychosocial = ['Não Realizado', 'Realizado'];
        $hasAnsweredOrganizational = ['Não Realizado', 'Realizado'];

        $view->with([
            'gendersToFilter' => count($gendersToFilter) ? $gendersToFilter : null,
            'departmentsToFilter' => count($departmentsToFilter) ? $departmentsToFilter : null,
            'occupationsToFilter' => count($occupationsToFilter) ? $occupationsToFilter : null,
            'workShiftsToFilter' => count($workShiftsToFilter) ? $workShiftsToFilter : null,
            'maritalStatusToFilter' => count($maritalStatusToFilter) ? $maritalStatusToFilter : null,
            'educationLevelsToFilter' => count($educationLevelsToFilter) ? $educationLevelsToFilter : null,
            'ageRangesToFilter' => count($ageRangesToFilter) ? $ageRangesToFilter : null,
            'admissionRangesToFilter' => count($admissionRangesToFilter) ? $admissionRangesToFilter : null,
            'yearsTofilter' => $yearsTofilter,
            'hasAnsweredPsychosocial' => $hasAnsweredPsychosocial,
            'hasAnsweredOrganizational' => $hasAnsweredOrganizational,
        ]);
    }
}
