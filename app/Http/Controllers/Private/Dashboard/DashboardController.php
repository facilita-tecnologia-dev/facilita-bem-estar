<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController
{
    protected $helper;
    protected $usersLatestCollections;

    public function __construct(Helper $helper)
    {  
        $this->helper = $helper; 
        $this->usersLatestCollections = $helper->getUsersLatestCollections(); 
    }

    public function __invoke(){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $generalResults = $this->getCompiledResults();
        $testsParticipation = $this->getTestsParticipation(); 

        $subfactorResults = $this->compileTestResults();
        
        return view('admin.dashboard.index', [
            'generalResults' => $generalResults,
            'subfactorResults' => $subfactorResults,
            'testsParticipation' => $testsParticipation
        ]);
    }

    /**
     * Retorna um array com os dados compilados de todos os testes.
     * @return array
     */
    private function getCompiledResults(): array{
        $usersLatestCollections = $this->usersLatestCollections;
        $compiledResults = [];

        foreach($usersLatestCollections as $user){
            if(count($user->testCollections) > 0){
                
                foreach($user->testCollections[0]->tests as $test){
                   // Get all answers for this test across all users (remove dd)
                    $answers = TestAnswer::where('test_form_id', $test->id)->get();
                    $questions = TestQuestion::where('test_type_id', $test->test_type_id)->get();

                    $testName = $test['test_name'];
                    $severity = $test['severity_title'];

                    // Initialize arrays if not set
                    if (!isset($compiledResults[$testName])) {
                        $compiledResults[$testName] = [
                            'subfactors' => [],
                            'general' => []
                        ];
                    }

                    // Group answers by factor
                    $factorDivision = [];
                    foreach ($answers as $answer) {
                        $question = $questions->where('id', $answer->test_question_id)->first();
                        if ($question) {
                            $factorDivision[$question->factor][] = $answer;
                        }
                    }

                    // Calculate averages for each factor
                    // $totalAverages = $sumAverages / ;
                    foreach ($factorDivision as $factor => $factorAnswers) {
                        $sum = 0;
                        $count = count($factorAnswers);

                        foreach ($factorAnswers as $answer) {
                            // dump($factorAnswers);
                            $sum += (int) $answer->value;
                        }

                        $av1 = $count > 0 ? $sum / $count : 0;
                        $average[$factor][] = $av1;
                    }


                    // Initialize severity tracking if not set
                    if (!isset($compiledResults[$testName]['general'][$severity])) {
                        $compiledResults[$testName]['general'][$severity] = [
                            'count' => 0,
                            'severity_color' => $test['severity_color']
                        ];
                    }

                    // Increment severity count
                    $compiledResults[$testName]['general'][$severity]['count'] += 1;
                }
            }
            
        }

        return $compiledResults;
    }

    private function compileTestResults(){
        $userTestCollections = User::
        where('company_id', session('company')->id)
        ->has('testCollections')
        ->with('testCollections', function($query){
            $query->latest()->limit(1)
            ->with('tests', function($q){
                $q->with(['answers', 'questions']);
            });
        })
        ->get();
        // $factorAverages = User::query()
        //     ->where('company_id', session('company')->id)
        //     ->whereHas('testCollections')
        //     ->join('test_collections', 'users.id',  '=', 'test_collections.user_id')
        //     ->join('test_forms',            'test_collections.id', '=', 'test_forms.test_collection_id')
        //     ->join('test_answers',          'test_forms.id',            '=', 'test_answers.test_form_id')
        //     ->join('test_questions',        'test_answers.test_question_id', '=', 'test_questions.id')

        //     // ✔ sub‑query escalar permitida
        //     ->where('test_collections.id', '=', function ($query) {
        //         $query->select('id')
        //             ->from('test_collections')
        //             ->whereColumn('test_collections.user_id', 'users.id')
        //             ->latest()      // ORDER BY created_at DESC
        //             ->limit(1);     // OK dentro de sub‑query escalar
        //     })

        //     ->select('test_questions.factor',
        //             DB::raw('AVG(test_answers.value) as average_value'))
        //     ->groupBy('test_questions.factor')
        //     ->get();

        // dd($factorAverages);

        $answersPerFactor = [];

        foreach ($userTestCollections as $user) {
            foreach ($user->testCollections as $testCollection) {
                foreach ($testCollection->tests as $testForm) {
                    $questionAnswers = [];

                    // Inicializa os fatores com arrays vazios
                    foreach ($testForm->questions as $question) {
                        $questionAnswers[$question->factor] = [];
                    }

                    // Agrupa as respostas por fator
                    foreach ($testForm->answers as $answer) {
                        $questionFactor = $answer->testQuestion->factor;
                        $questionAnswers[$questionFactor][] = $answer;
                    }

                    // Armazena as respostas agrupadas
                    $answersPerFactor[$testForm->test_name][] = $questionAnswers;
                }
            }
        }

        // Calcula as médias por item e coleta os valores para médias gerais
        $averagesPerFactor = $answersPerFactor;
        $factorTotals = [];

        foreach ($averagesPerFactor as $testName => $test) {
            foreach ($test as $index => $factors) {
                foreach ($factors as $factorName => $factor) {
                    $sum = 0;
                    $count = count($factor);

                    if ($count > 0) {
                        foreach ($factor as $answer) {
                            $sum += (int) $answer->value;
                        }
                        $average = $sum / $count;
                    } else {
                        $average = 0;
                    }

                    // Armazena a média no array temporário para cálculo geral
                    $factorTotals[$testName][$factorName][] = $average;
                }
            }
        }

        $result = [];

        foreach($factorTotals as $testName => $test){
            foreach($test as $key => $factor){
                $result[$testName][$key] = array_sum($factor) / count($factor);
            }
        }

        // foreach($result as $testName => $test){
        //     $result[$testName]['total'] = array_sum($test) / count($test);
        // }

        // dump($userTestCollections);
        return $result;
    }

    /**
     * Retorna um array com 2 itens.
     * @return array [Total de usuários, usuários que realizaram os testes]
     */
    private function getTestsParticipation(): array{
        $usersLatestCollections = $this->usersLatestCollections;

        $usersWithCollections = [];

        foreach($usersLatestCollections as $user){
            if(count($user->testCollections) > 0){
                $usersWithCollections[] = $user;
            }
            
        }

        $countCollections = count($usersWithCollections);

        
        $users = User::query()->where('company_id', '=', session('company')->id)->get()->toArray();
        
        $countUsers = count($users);
        
        $testsParticipation = [$countCollections, ($countUsers - $countCollections)];

        return $testsParticipation;
   }
}
