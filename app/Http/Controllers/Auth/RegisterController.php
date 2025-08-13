<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterCompanyRequest;
use App\Models\ActionPlan;
use App\Models\Collection;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\ControlAction;
use App\Models\CustomCollection;
use App\Models\CustomControlAction;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use App\Models\CustomTest;
use App\Models\Metric;
use App\Models\RiskQuestionMap;
use App\Models\Test;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController
{
    public function showCompanyRegister()
    {
        return view('auth.register.company');
    }

    public function attemptCompanyRegister(RegisterCompanyRequest $request)
    {
        $company = DB::transaction(function () use ($request) {
            $company = Company::create([
                'name' => $request->validated('name'),
                'cnpj' => $request->validated('cnpj'),
                'email' => $request->validated('email'),
                'password' => Hash::make($request->validated('password')),
            ]);

            $this->createMetrics($company);
            $this->createCustomCollections($company);
            $this->createActionPlan($company);
            
            Auth::guard('company')->login($company);
            session()->regenerate();

            CompanyService::loadCompanyToSession($company);
        });

        return to_route('welcome.company');
    }

    private function createCustomCollections(Company $company)
    {
        return DB::transaction(function() use($company) {
            $collections = Collection::all();

            foreach($collections as $collection){
                $customCollection = CustomCollection::create([
                    'company_id' => $company['id'],
                    'collection_id' => $collection['id'],
                    'name' => $collection['name'],
                    'is_default' => true,
                ]);

                $tests = Test::where('collection_id', $collection['id'])->with('questions.options')->get();

                foreach($tests as $test){
                    $customTest = CustomTest::create([
                        'custom_collection_id' => $customCollection['id'],
                        'key_name' => $test['key_name'],
                        'display_name' => $test['display_name'],
                        'statement' => $test['statement'],
                        'order' => $test['order'],
                        'handler_type' => $test['handler_type'],
                        'reference' => $test['reference'],
                        'number_of_questions' => $test['number_of_questions'],
                    ]);
    
                    foreach($test['questions'] as $question){
                        $customQuestion = CustomQuestion::create([
                            'custom_test_id' => $customTest['id'],
                            'statement' => $question['statement']
                        ]);

                        $riskQuestion = RiskQuestionMap::where('question_Id', $question['id'])
                                                            ->where('company_id', 0)
                                                            ->first(); 
                        if($riskQuestion) {
                            RiskQuestionMap::create([
                                'risk_id' => $riskQuestion['risk_id'],
                                'question_Id' => $customQuestion['id'],
                                'company_id' => $company['id']
                            ]);
                        }
    
                        foreach($question['options'] as $option){
                            CustomQuestionOption::create([
                                'custom_question_id' => $customQuestion['id'],
                                'content' => $option['content'],
                                'value' => $option['value']
                            ]);
                        }
                    }
                }
            }
        });
    }

    private function createMetrics(Company $company)
    {
        DB::transaction(function() use($company) {

            $metrics = Metric::all();
            
            foreach ($metrics as $metric) {
                CompanyMetric::create([
                    'metric_id' => $metric->id,
                    'company_id' => $company->id,
                    'value' => null,
                ]);
            }
        });
    }

    private function createActionPlan(Company $company)
    {
        DB::transaction(function() use($company) {
            $actionPlan = ActionPlan::create([
                'company_id' => $company->id,
                'name' => 'Plano de Ação de Riscos Psicossociais'
            ]);
    
            $controlActions = ControlAction::all();
            
            $controlActions->each(function($ca) use($company, $actionPlan) {
                CustomControlAction::create([
                    'company_id' => $company->id,
                    'action_plan_id' => $actionPlan->id,
                    'risk_id' => $ca->risk_id,
                    'content' => $ca->content,
                    'severity' => $ca->severity,
                ]);
            });
        });
    }
}
