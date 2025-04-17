<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\Company;
use App\Services\TestService;
use Illuminate\Support\Facades\Gate;

class DashboardController
{
    protected $testService;

    protected $companyUserCollections;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
        $this->companyUserCollections = $this->pageQuery();
    }

    public function __invoke()
    {
        Gate::authorize('view-manager-screens');

        $dashboardResults = $this->getCompiledTestsData();
        $testsParticipation = $this->getTestsParticipation();
        $metrics = $this->companyUserCollections->metrics;

        return view('private.dashboard.index', compact(
            'dashboardResults',
            'testsParticipation',
            'metrics',
        ));
    }

    private function pageQuery()
    {
        $companyUserCollections = Company::where('id', session('company')->id)
            ->with('metrics')
            ->with('users', function ($user) {
                $user
                    ->has('collections')
                    ->with('latestCollections', function ($latestCollection) {
                        $latestCollection->with('tests');
                    });
            })
            ->first();

        return $companyUserCollections;
    }

    private function getCompiledTestsData()
    {
        $testCompiled = [];

        foreach ($this->companyUserCollections->users as $user) {
            foreach ($user->latestCollections as $collection) {
                foreach ($collection->tests as $userTest) {
                    $collectionName = $collection->collectionType->key_name;
                    $testDisplayName = $userTest->testType->display_name;

                    $evaluatedTest = $this->testService->evaluateTest($userTest, $this->companyUserCollections->metrics);

                    if ($collection->collectionType->key_name == 'psychosocial-risks') {
                        if (! isset($testCompiled[$collectionName][$testDisplayName]['severities'][$evaluatedTest['severity_title']])) {
                            $testCompiled[$collectionName][$testDisplayName]['severities'][$evaluatedTest['severity_title']] = [
                                'count' => 0,
                                'severity_color' => $evaluatedTest['severity_color'],
                            ];
                        }

                        if (isset($evaluatedTest['risks'])) {
                            foreach ($evaluatedTest['risks'] as $riskName => $risk) {
                                $testCompiled[$collectionName][$testDisplayName]['risks'][$riskName]['score'][] = $risk['riskPoints'];
                            }
                        }

                        $testCompiled[$collectionName][$testDisplayName]['severities'][$evaluatedTest['severity_title']]['count'] += 1;
                    }

                    if ($collection->collectionType->key_name == 'organizational-climate') {
                        foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
                            $testCompiled[$collectionName][$testDisplayName]['Geral']['answers'][$questionNumber][] = $answer;
                            $testCompiled[$collectionName][$testDisplayName][$user->department]['answers'][$questionNumber][] = $answer;
                        }
                    }
                }
            }
        }

        foreach ($testCompiled['psychosocial-risks'] as $testName => $test) {
            if (isset($test['risks'])) {
                foreach ($test['risks'] as $riskName => $testRisk) {
                    $average = array_sum($testRisk['score']) / count($testRisk['score']);
                    $testCompiled['psychosocial-risks'][$testName]['risks'][$riskName]['score'] = ceil($average);

                    if ($average > 2) {
                        $testCompiled['psychosocial-risks'][$testName]['risks'][$riskName]['risk'] = 'Risco Alto';
                    } elseif ($average > 1) {
                        $testCompiled['psychosocial-risks'][$testName]['risks'][$riskName]['risk'] = 'Risco Médio';
                    } else {
                        $testCompiled['psychosocial-risks'][$testName]['risks'][$riskName]['risk'] = 'Risco Baixo';
                    }
                }
            }
        }

        foreach ($testCompiled['organizational-climate'] as $testName => $test) {
            foreach ($test as $categoryName => $category) {
                foreach ($category['answers'] as $questionNumber => $answers) {
                    $count = count($answers);
                    $sum = array_sum($answers);
                    $testCompiled['organizational-climate'][$testName][$categoryName]['total_average'] = $sum / $count;
                }
            }
        }

        krsort($testCompiled);

        return $testCompiled;
    }

    /**
     * Retorna um array com a quantidade de usuários que realizaram/não realizaram os testes.
     *
     * @return array [Total de usuários, usuários que realizaram os testes]
     */
    private function getTestsParticipation(): array
    {
        $participatingUserCount = $this->companyUserCollections->users->count();
        $nonParticipatingUserCount = session('company')->users->count() - $participatingUserCount;

        $testsParticipation = [$participatingUserCount, $nonParticipatingUserCount];

        return $testsParticipation;
    }
}
