<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Collection;

class PsychosocialRiskService
{
    /* Participation */

    public function getParticipation($userTests)
    {
        $usersWithCollection = $userTests->map(fn($item) => $item->parentCollection->userOwner);
        $usersByDepartment = session('company')->users->groupBy('department');

        if (! $usersWithCollection->count()) {
            return null;
        }

        $participation = $this->calculateGeneralParticipation($usersWithCollection);
        $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

        return $participation;
    }

    private function calculateGeneralParticipation($usersWithCollection)
    {
        return [
            'Geral' => [
                'count' => $usersWithCollection->count(),
                'per_cent' => ($usersWithCollection->count() / session('company')->users->count() ?? 1) * 100,
            ],
        ];
    }

    private function calculateDepartmentParticipation(Collection $usersWithCollection, Collection $usersByDepartment)
    {
        $departmentParticipation = [];

        foreach ($usersByDepartment as $departmentName => $department) {
            $departmentParticipation[$departmentName] = [
                'count' => $usersWithCollection->where('department', $departmentName)->count(),
                'per_cent' => ($usersWithCollection->where('department', $departmentName)->count() / $department->count()) * 100,
            ];
        }

        return $departmentParticipation;
    }
}
