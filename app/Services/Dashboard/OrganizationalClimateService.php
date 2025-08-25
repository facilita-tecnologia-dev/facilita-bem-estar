<?php

namespace App\Services\Dashboard;

use App\Helpers\AuthGuardHelper;
use App\Models\User;
use App\Services\User\UserFilterService;
use Illuminate\Support\Collection;

class OrganizationalClimateService
{
    public function __construct(
        protected UserFilterService $filterService
    ) {}

    public function getOrganizationalData($request, $applyFilters = false): Collection
    {
        $query = session('company')->users()
            ->whereHas('latestOrganizationalClimateCollection')
            ->withLatestOrganizationalClimateCollection(fn($q) => 
                $q->whereYear('created_at', $request->year ?? now()->year)
                  ->withCollectionTypeName('organizational-climate')
                  ->withTests(fn($q) => $q->withAnswers()->withTestType())
                //   ->withCustomTests(fn($q) => $q->withAnswers()->withCustomTestType())
            );

        $users = $query->getQuery();

        return $applyFilters 
            ? $this->filterByRequestFilters($users) 
            : $users->get();
    }

    public function filterByRequestFilters($query)
    {
        return $this->filterService->apply($query)->get();
    }

    public function filterByDepartmentScope(Collection $users): Collection
    {
        $user = AuthGuardHelper::user();
        if (!($user instanceof User)) {
            return $users;
        }

        $allowedDepartments = $user->departmentScopes
            ->where('allowed', 1)
            ->pluck('department')
            ->toArray();
            

        return $users->filter(function ($u) use ($allowedDepartments) {
            return $u instanceof User && in_array($u->department, $allowedDepartments);
        });
    }
}
