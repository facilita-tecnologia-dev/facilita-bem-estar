<?php

namespace App\Helpers;

use App\Models\Company;
use App\Models\Risk;
use App\Models\Test;
use App\Services\TestService;

class Helper
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    // public function getCompanyUsers($hasCollection = null)
    // {
    //     $company = Company::where('id', session('company')->id)
    //         ->with('users', function ($q) use ($hasCollection) {
    //             $q->when($hasCollection, fn ($i) => $i->has('latestCollection'));
    //         })
    //         ->first();

    //     return $company;
    // }

    // public static function getTestRisks(Test $testType)
    // {
    //     $risks = Risk::whereHas('relatedQuestions', function ($q) use ($testType) {
    //         $q->whereIn('question_id', $testType->questions->pluck('id'));
    //     })
    //         ->with(['relatedQuestions' => function ($q) use ($testType) {
    //             $q->whereIn('question_id', $testType->questions->pluck('id'));
    //         }])
    //         ->get();

    //     return $risks;
    // }

    public static function multiplyAnswer($answer)
    {
        $multiplied = 0;
        switch ($answer) {
            case 1:
                $multiplied = 0;
                break;
            case 2:
                $multiplied = 25;
                break;
            case 3:
                $multiplied = 50;
                break;
            case 4:
                $multiplied = 75;
                break;
            case 5:
                $multiplied = 100;
                break;
        }

        return $multiplied;
    }
}
