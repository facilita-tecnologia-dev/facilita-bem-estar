<?php

namespace App\View\Composers;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use Carbon\Carbon;
use Illuminate\View\View;

class FiltersComposer
{
    public function compose(View $view): void
    {
        $companyUsers = session('company')->users;

        $gendersToFilter = array_keys($companyUsers->groupBy('gender')->toArray());
        $departmentsToFilter = array_keys($companyUsers->groupBy('department')->toArray());
        $occupationsToFilter = array_keys($companyUsers->groupBy('occupation')->toArray());
        $workShiftsToFilter = array_keys($companyUsers->groupBy('work_shift')->toArray());
        $maritalStatusToFilter = array_keys($companyUsers->groupBy('marital_status')->toArray());
        $educationLevelsToFilter = array_keys($companyUsers->groupBy('education_level')->toArray());

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

        $view->with([
            'gendersToFilter' => $gendersToFilter,
            'departmentsToFilter' => $departmentsToFilter,
            'occupationsToFilter' => $occupationsToFilter,
            'workShiftsToFilter' => $workShiftsToFilter,
            'maritalStatusToFilter' => $maritalStatusToFilter,
            'educationLevelsToFilter' => $educationLevelsToFilter,
            'ageRangesToFilter' => $ageRangesToFilter,
            'admissionRangesToFilter' => $admissionRangesToFilter,
            'yearsTofilter' => $yearsTofilter,
        ]);
    }
}
