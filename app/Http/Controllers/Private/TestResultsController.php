<?php

namespace App\Http\Controllers\Private;

use App\Models\TestCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestResultsController
{
    public function index(){
        $lastUserTestCollection = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->where('user_id', '=', Auth::user()->id);
        })->with('tests')->first()->toArray();

        $testResults = $lastUserTestCollection['tests']; 
          
        $user = Auth::user();
        $userRole = DB::table('role_user')->where('user_id', '=', $user->id)->first();
        
        $isAdmin = $userRole->role_id === 1;

        return view('user.test-results', [
            'userInfo' => $user,
            'testResults' => $testResults,
            'isAdmin' => $isAdmin,
        ]);
    }
}
