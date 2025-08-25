<?php

namespace App\Services\Dashboard;

use Illuminate\Support\Collection;

class PsychosocialRiskService
{
    /* Participation */

    public function getParticipation($userTests)
    {   
        if(count($userTests) > 0){
            $activeUsers = session('company')->users()->wherePivot('status', 1)->get();
            $usersByDepartment = $activeUsers->groupBy('department');
            
            $usersWithCollection = $userTests->map(fn($item) => $item->parentCollection->userOwner)
                                            ->filter(fn($user) => $activeUsers->firstWhere('id', $user->id));

            if (! $usersWithCollection->count()) {
                return null;
            }

            
            $participation = $this->calculateGeneralParticipation($usersWithCollection, $activeUsers);
            $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

            return $participation;
        }

        return [];
    }

    private function calculateGeneralParticipation($usersWithCollection, $activeUsers)
    {
        return [
            'Geral' => [
                'count' => $usersWithCollection->count(),
                'per_cent' => floor(($usersWithCollection->count() / $activeUsers->count() ?? 1) * 100),
            ],
        ];
    }

    private function calculateDepartmentParticipation(Collection $usersWithCollection, Collection $usersByDepartment)
    {
        $departmentParticipation = [];
        foreach ($usersByDepartment as $departmentName => $department) {
            $usersInDepartmentWithCollection = $usersWithCollection->where('department', $departmentName)->count();

            $departmentParticipation[$departmentName] = [
                'count' => $usersWithCollection->where('department', $departmentName)->count(),
                'per_cent' => floor(($usersInDepartmentWithCollection / $department->count()) * 100),
            ];
        }

        return $departmentParticipation;
    }
}
