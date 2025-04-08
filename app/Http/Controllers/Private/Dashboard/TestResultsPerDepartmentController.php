<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TestResultsPerDepartmentController
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function __invoke($testName){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }
        
        $testData = $this->compileTestsData($testName);
       
        return view('admin.dashboard.test-results-per-department', [
            'testName' => $testName,
            'testStats' => $testData,
        ]);
    }

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     * @return array
     */
    private function compileTestsData($testName): array{
        $usersLatestTest = User::
            whereRelation('companies', 'companies.id', session('company')->id)
            ->has('testCollections')
            ->with('testCollections', function($query) use($testName) {
                $query->latest()->limit(1)
                    ->with('tests', function($q) use($testName) {
                        $q->whereHas('testType', function($typeQuery) use($testName) {
                            $typeQuery->where('display_name', $testName);
                        })
                        ->with(['answers', 'questions', 'testType']);
                    });
            })
            ->get();

        $testStats = [];
        
        foreach($usersLatestTest as $user){    
            $severityTitle = $user->testCollections[0]->tests[0]['severity_title'];
            $severityColor = $user->testCollections[0]->tests[0]['severity_color'];
            
            if(!isset($testStats[$user->department])){
                $testStats[$user->department] = [];
            }
            
            if(!isset($testStats[$user->department]['total'])){
                $testStats[$user->department]['total'] = 0;
            }
            
            if(!isset($testStats[$user->department]['severities'][$severityTitle]['count'])){
                $testStats[$user->department]['severities'][$severityTitle]['count'] = 0;
            }
            
            if(!isset($testStats[$user->department]['severities'][$severityTitle]['severity_color'])){
                $testStats[$user->department]['severities'][$severityTitle]['severity_color'] = '';
            }
            
            $testStats[$user->department]['total'] += 1;
            $testStats[$user->department]['severities'][$severityTitle]['count'] += 1;
            $testStats[$user->department]['severities'][$severityTitle]['severity_color'] = (int) $severityColor;
        };
            
        $testsSorted = array_map(function($item){
            $item['severities'] = $this->bubbleSortSeverities($item['severities']);
            
            return $item;
        }, $testStats);

        return $testsSorted;
    }

    /**
     * Função básica de Bubble Sort para ordenar com base na severidade
     * @return array
     */
    private function bubbleSortSeverities($array): array {
        $keys = array_keys($array);
        $n = count($keys);
        
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$keys[$j]]['severity_color'] < $array[$keys[$j + 1]]['severity_color']) {
                    $temp = $keys[$j];
                    $keys[$j] = $keys[$j + 1];
                    $keys[$j + 1] = $temp;
                }
            }
        }
        
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }
        
        return $result;
    }
}
