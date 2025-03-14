<?php

namespace App\Http\Controllers\Users;

use App\Models\PendingTestAnswer;
use App\Models\TestCollection;
use App\Models\TestType;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController
{
    
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function index(){
        $hasPendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->exists();

        $user = Auth::user();
        $userRole = DB::table('role_user')->where('user_id', '=', $user->id)->first();
        $userLatestCollection =  TestCollection::where('user_id', $user->id)->whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->get();


        $isAdmin = $userRole->role_id === 1;

        return view('user.welcome', [
            'isAdmin' => $isAdmin,
            'hasPendingAnswers' => $hasPendingAnswers ?? '',
            'hasLatestCollection' => count($userLatestCollection) > 0 ? true : false,
        ]);
    }

    public function startTests(){
        $testTypes = TestType::query()->get();

        $nextTest = ''; 

        foreach($testTypes as $testIndex => $testType){
            $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->where('test_type_id', '=', $testType->id)->get()->toArray();
            
            if(! (count($pendingAnswers) == $testType->number_of_questions)){
                return to_route('test', $testIndex + 1);
            }
            
            $pendingAnswersValues = array_map(function($answer){
                return $answer['value'];
            }, $pendingAnswers);

            $this->testService->processTest($pendingAnswersValues, $testType);
        }
    }

}
