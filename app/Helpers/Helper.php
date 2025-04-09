<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\Risk;
use App\Models\Test;

class Helper
{
    /**
     * Retorna um objeto Company com a última coleção de testes de cada usuário da empresa.
     */
    public function getCompanyUsersCollections($justEssentials = null, $testName = null, $risks = null, $metrics = null, $tests = true, $queryStringName = null, $queryStringDepartment = null, $queryStringOccupation = null): Company
    {
        $companyUsersCollections = Company::where('id', session('company')->id)
            ->when($metrics, function ($q) {
                $q->with('metrics.metricType');
            })
            ->with('users', function ($user) use ($justEssentials, $testName, $risks, $tests, $queryStringName, $queryStringDepartment, $queryStringOccupation) {
                $user
                    ->has('collections')
                    ->when($queryStringName, function ($query) use ($queryStringName) {
                        $query->where('name', 'like', "%$queryStringName%");
                    })
                    ->when($queryStringDepartment, function ($query) use ($queryStringDepartment) {
                        $query->where('department', '=', "$queryStringDepartment");
                    })
                    ->when($queryStringOccupation, function ($query) use ($queryStringOccupation) {
                        $query->where('occupation', '=', "$queryStringOccupation");
                    })
                    ->with('latestCollection', function ($latestCollection) use ($justEssentials, $testName, $risks, $tests) {
                        $latestCollection
                            ->when($risks, function ($q) {
                                $q->with('risks', function ($risk) {
                                    $risk->with('parentRisk', fn($i) => $i->with('relatedTest', 'controlActions'));
                                });
                            })
                            ->when($tests, function ($q) use ($justEssentials, $testName) {
                                $q->with('tests', function ($userTest) use ($justEssentials, $testName) {
                                    $userTest
                                        ->when($testName, function ($q) use ($testName) {
                                            $q->whereHas('testType', function ($query) use ($testName) {
                                                $query->where('display_name', $testName);
                                            });
                                        })
                                        ->when($justEssentials, function ($q) {
                                            $q->with(['testType']);
                                        })
                                        ->when(! $justEssentials, function ($q) {
                                            $q->with(['answers', 'questions', 'testType']);
                                        });
                                });
                            });
                    });
            })
            ->first();

        return $companyUsersCollections;
    }

    public function getCompanyUsers($hasCollection = null)
    {
        $company = Company::where('id', session('company')->id)
            ->with('users', function ($q) use ($hasCollection) {
                $q->when($hasCollection, fn ($i) => $i->has('latestCollection'));
            })
            ->first();

        return $company;
    }

    public static function getTestRisks(Test $testType)
    {
        $risks = Risk::whereHas('relatedQuestions', function ($q) use ($testType) {
            $q->whereIn('question_id', $testType->questions->pluck('id'));
        })
            ->with(['relatedQuestions' => function ($q) use ($testType) {
                $q->whereIn('question_id', $testType->questions->pluck('id'));
            }])
            ->get();

        return $risks;
    }
}
