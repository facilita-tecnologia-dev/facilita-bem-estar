<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\QuestionOption;
use App\Models\TestForm;
use App\Models\TestQuestion;
use App\Models\TestType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(50)->create();

        $users = User::all();

        // $testNames = ['Ansiedade', 'Depressão', 'Pressão no Trabalho', 'Pressão por Resultados', 'Insegurança', 'Conflitos', 'Relações Sociais', 'Exigências Emocionais', 'Autonomia', 'Burnout', 'Estresse'];

        $tests = [
            'ansiedade' => [
                'keyName' => 'ansiedade',
                'displayName' => 'Ansiedade',
                'nextStep' => 'depressao',
                'numberOfQuestions' => 7,
                'handlerType' => 'anxiety',
                'statement' => 'Nas últimas 2 semanas, com que frequência você foi incomodado pelos problemas abaixo?',
                'reference' => 'Baseado no GAD-7 (Generalized Anxiety Disorder-7) [Spitzer et al., 2006]',
                'order' => 1,
                'questions' => [
                    [
                        'statement' => 'Sentir-se nervoso, ansioso ou muito tenso',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Preocupar-se muito com diversas coisas',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Não ser capaz de impedir ou controlar as preocupações',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Dificuldade para relaxar',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Ficar tão agitado que se torna difícil permanecer sentado',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Ficar facilmente aborrecido ou irritado',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Sentir medo como se algo terrível fosse acontecer',
                        'options' => [
                            [
                                'content' => 'Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Alguns dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 7
                ],
            ],
            'depressao' => [
                'keyName' => 'depressao',
                'displayName' => 'Depressão',
                'nextStep' => 'pressao-no-trabalho',
                'numberOfQuestions' => 9,
                'handlerType' => 'depression',
                'order' => 2,
                'statement' => 'Nas últimas 2 semanas, com que frequência você foi incomodado pelos problemas abaixo?',
                'reference' => 'Baseado no PHQ-9 (Patient Health Questionnaire-9) [Kroenke et al., 2001]',
                'questions' => [
                    [
                        'statement' => 'Pouco interesse ou prazer em fazer as coisas',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Sentindo-se para baixo, deprimido ou sem esperança',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Dificuldade para dormir ou dormir demais',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Sentindo-se cansado ou com pouca energia',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Falta de apetite ou comendo demais',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Sentindo-se mal consigo mesmo ou achando que é um fracasso',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Dificuldade para se concentrar nas coisas',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Movimentando-se ou falando tão lentamente que outras pessoas perceberam, ou estar agitado',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 8
                    [
                        'statement' => 'Pensamentos de que seria melhor estar morto ou de se machucar de alguma forma',
                        'options' => [
                            [
                                'content' => 'Nenhuma vez',
                                'value' => 0,
                            ],
                            [
                                'content' => 'Vários dias',
                                'value' => 1,
                            ],
                            [
                                'content' => 'Mais da metade dos dias',
                                'value' => 2,
                            ],
                            [
                                'content' => 'Quase todos os dias',
                                'value' => 3,
                            ],
                        ],
                    ], // 9
                ],
            ],
            'pressao-no-trabalho' => [
                'keyName' => 'pressao-no-trabalho',
                'displayName' => 'Pressão no Trabalho',
                'nextStep' => 'pressao-por-resultados',
                'numberOfQuestions' => 8,
                'handlerType' => 'pressure-at-work',
                'order' => 3,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado na ISO 45003:2021 e Copenhagen Psychosocial Questionnaire (COPSOQ) [Kristensen et al., 2005]',
                'questions' => [
                    [
                        'statement' => 'Com que frequência você não tem tempo para completar todas as suas tarefas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Você precisa trabalhar muito rapidamente?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Seu trabalho exige emocionalmente de você?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Você se sente atrasado no trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Você tem tempo suficiente para suas tarefas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Você precisa fazer horas extras?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Você consegue fazer pausas quando necessário?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Você sente que as demandas são contraditórias?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'pressao-por-resultados' => [
                'keyName' => 'pressao-por-resultados',
                'displayName' => 'Pressão por Resultados',
                'nextStep' => 'inseguranca',
                'numberOfQuestions' => 8,
                'handlerType' => 'pressure-for-results',
                'order' => 4,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado na ISO 45003:2021 e Job Content Questionnaire (JCQ) [Karasek et al., 1998]',
                'questions' => [
                    [
                        'statement' => 'Sinto-me pressionado a atingir metas irrealistas',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'As expectativas de desempenho são claramente comunicadas',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Tenho recursos adequados para atingir os resultados esperados',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Sou cobrado por resultados que dependem de outros setores',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Recebo feedback construtivo sobre meu desempenho',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'As metas são definidas de forma participativa',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Sinto que posso negociar prazos quando necessário',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'O reconhecimento está atrelado apenas aos resultados numéricos',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'inseguranca' => [
                'keyName' => 'inseguranca',
                'displayName' => 'Insegurança',
                'nextStep' => 'conflitos',
                'numberOfQuestions' => 8,
                'handlerType' => 'insecurity',
                'order' => 5,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado no Job Insecurity Scale (JIS) [De Witte, 2000] e ISO 45003:2021',
                'questions' => [
                    [
                        'statement' => 'Sinto-me inseguro sobre o futuro do meu emprego',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Tenho medo de perder meu trabalho',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Tenho certeza de que posso manter meu trabalho',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Existem possibilidades de crescimento na empresa',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Minhas habilidades são valorizadas',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Sinto-me facilmente substituível',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'A empresa oferece estabilidade',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Tenho clareza sobre meu papel e responsabilidades',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'conflitos' => [
                'keyName' => 'conflitos',
                'displayName' => 'Conflitos',
                'nextStep' => 'relacoes-sociais',
                'numberOfQuestions' => 8,
                'handlerType' => 'conflicts',
                'order' => 6,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado no Interpersonal Conflict at Work Scale (ICAWS) [Spector & Jex, 1998]',
                'questions' => [
                    [
                        'statement' => 'Com que frequência você discorda com outros no trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Com que frequência outros são rudes com você?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Com que frequência há tensão nas relações de trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Há conflitos não resolvidos em sua equipe?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Você se sente respeitado por seus colegas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Há competição prejudicial entre colegas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Os conflitos são gerenciados de forma construtiva?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Você se sente à vontade para expressar opiniões divergentes?',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'relacoes-sociais' => [
                'keyName' => 'relacoes-sociais',
                'displayName' => 'Relações Sociais',
                'nextStep' => 'exigencias-emocionais',
                'numberOfQuestions' => 8,
                'handlerType' => 'social-relations',
                'order' => 7,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado no COPSOQ II [Pejtersen et al., 2010] e ISO 45003:2021',
                'questions' => [
                    [
                        'statement' => 'Você recebe ajuda e suporte dos seus colegas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Há boa comunicação no seu local de trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Você se sente parte de uma comunidade?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Você sente que seu trabalho é reconhecido e apreciado?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Há cooperação entre os colegas?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Você tem bom relacionamento com sua chefia?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Você participa das decisões que afetam seu trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Existe um ambiente de confiança mútua?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'exigencias-emocionais' => [
                'keyName' => 'exigencias-emocionais',
                'displayName' => 'Exigências Emocionais',
                'nextStep' => 'autonomia',
                'numberOfQuestions' => 8,
                'handlerType' => 'emotional-demands',
                'order' => 8,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado no COPSOQ II [Pejtersen et al., 2010]',
                'questions' => [
                    [
                        'statement' => 'Seu trabalho coloca você em situações emocionalmente perturbadoras?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Você precisa lidar com problemas pessoais de outros?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Seu trabalho exige que você esconda seus sentimentos?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Você se sente emocionalmente exausto?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Você precisa ser simpático e aberto o tempo todo?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Você lida com pessoas difíceis no trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Seu trabalho é emocionalmente desgastante?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Você consegue se recuperar emocionalmente após o trabalho?',
                        'options' => [
                            [
                                'content' => '1 - Nunca/quase nunca',
                                'value' => 5,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 4,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 2,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 1,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'autonomia' => [
                'keyName' => 'autonomia',
                'displayName' => 'Autonomia',
                'nextStep' => 'burnout',
                'numberOfQuestions' => 8,
                'handlerType' => 'autonomy',
                'order' => 9,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado no Job Autonomy Scale [Breaugh, 1985] e ISO 45003:2021',
                'questions' => [
                    [
                        'statement' => 'Posso decidir como fazer meu trabalho',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Tenho liberdade para planejar minhas atividades',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Posso tomar decisões sem consultar superiores',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Tenho flexibilidade de horário',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Posso escolher métodos para realizar tarefas',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Tenho controle sobre o ritmo do meu trabalho',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Posso priorizar minhas atividades',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Tenho liberdade para resolver problemas do meu jeito',
                        'options' => [
                            [
                                'content' => '1 - Nunca / Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Sempre',
                                'value' => 5,
                            ],
                        ],
                    ], // 8
                ],
            ],
            'burnout' => [
                'keyName' => 'burnout',
                'displayName' => 'Burnout',
                'nextStep' => 'estresse',
                'numberOfQuestions' => 9,
                'handlerType' => 'burnout',
                'order' => 10,
                'statement' => 'Responda numa escala de 0 (Nunca) a 6 (Todos os dias):',
                'reference' => 'Baseado no Maslach Burnout Inventory (MBI) [Maslach et al., 1996]',
                'questions' => [
                    [
                        'statement' => 'Sinto-me emocionalmente esgotado com meu trabalho',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Sinto-me acabado no final do dia',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Sinto-me fatigado quando acordo',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Trabalhar o dia todo é realmente um peso para mim',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Sinto-me frustrado com meu trabalho',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Sinto que estou trabalhando demais',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Trabalhar diretamente com pessoas me deixa muito estressado',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Sinto que estou no meu limite',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 8
                    [
                        'statement' => 'Sinto-me sem energia',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Raramente',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Às vezes',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Frequentemente',
                                'value' => 4,
                            ],
                            [
                                'content' => '5 - Muito frequentemente',
                                'value' => 5,
                            ],
                            [
                                'content' => '6 - Todos os dias',
                                'value' => 6,
                            ],
                        ],
                    ], // 9
                ],
            ],
            'estresse' => [
                'keyName' => 'estresse',
                'displayName' => 'Estresse',
                'nextStep' => '',
                'numberOfQuestions' => 10,
                'handlerType' => 'stress',
                'order' => 11,
                'statement' => 'Responda numa escala de 1 (Nunca/Quase nunca) a 5 (Sempre):',
                'reference' => 'Baseado na Perceived Stress Scale (PSS) [Cohen et al., 1983]',
                'questions' => [
                    [
                        'statement' => 'Ficou chateado por algo que aconteceu inesperadamente?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 1
                    [
                        'statement' => 'Sentiu que foi incapaz de controlar coisas importantes em sua vida?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 2
                    [
                        'statement' => 'Sentiu-se nervoso ou estressado?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 3
                    [
                        'statement' => 'Sentiu-se confiante em sua capacidade de lidar com problemas pessoais?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 4,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 3,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 1,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 0,
                            ],
                        ],
                    ], // 4
                    [
                        'statement' => 'Sentiu que as coisas estavam indo do seu jeito?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 4,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 3,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 1,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 0,
                            ],
                        ],
                    ], // 5
                    [
                        'statement' => 'Percebeu que não conseguia lidar com todas as coisas que tinha que fazer?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 6
                    [
                        'statement' => 'Foi capaz de controlar irritações em sua vida?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 4,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 3,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 1,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 0,
                            ],
                        ],
                    ], // 7
                    [
                        'statement' => 'Sentiu que estava por cima das coisas?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 4,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 3,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 1,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 0,
                            ],
                        ],
                    ], // 8
                    [
                        'statement' => 'Esteve bravo por causa de coisas que estavam fora de seu controle?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 9
                    [
                        'statement' => 'Sentiu que as dificuldades estavam se acumulando tanto que você não conseguiria superá-las?',
                        'options' => [
                            [
                                'content' => '0 - Nunca',
                                'value' => 0,
                            ],
                            [
                                'content' => '1 - Quase nunca',
                                'value' => 1,
                            ],
                            [
                                'content' => '2 - Às vezes',
                                'value' => 2,
                            ],
                            [
                                'content' => '3 - Frequentemente',
                                'value' => 3,
                            ],
                            [
                                'content' => '4 - Muito frequentemente',
                                'value' => 4,
                            ],
                        ],
                    ], // 10
                ],
            ],
        ];

        $testsPointsMap = [
            'ansiedade' => [
                'min' => 0,
                'max' => 21,
            ],
            'depressao' => [
                'min' => 0,
                'max' => 27,
            ],
            'pressao-no-trabalho' => [
                'min' => 8,
                'max' => 40,
            ],
            'pressao-por-resultados' => [
                'min' => 8,
                'max' => 40,
            ],
            'inseguranca' => [
                'min' => 8,
                'max' => 40,
            ],
            'conflitos' => [
                'min' => 8,
                'max' => 40,
            ],
            'relacoes-sociais' => [
                'min' => 8,
                'max' => 40,
            ],
            'exigencias-emocionais' => [
                'min' => 8,
                'max' => 40,
            ],
            'autonomia' => [
                'min' => 8,
                'max' => 40,
            ],
            'burnout' => [
                'min' => 0,
                'max' => 54,
            ],
            'estresse' => [
                'min' => 0,
                'max' => 40,
            ],
        ];

        $testsValues = array_values($tests);

        foreach ($tests as $testType) {
            $storedTestType = TestType::factory()->create([
                'key_name' => $testType['keyName'],
                'display_name' => $testType['displayName'],
                'number_of_questions' => $testType['numberOfQuestions'],
                'handler_type' => $testType['handlerType'],
                'order' => $testType['order'],
                'reference' => $testType['reference'],
                'statement' => $testType['statement'],
            ]);

            foreach ($testType['questions'] as $question) {
                $testQuestion = TestQuestion::factory()->create([
                    'test_type_id' => $storedTestType->id,
                    'statement' => $question['statement'],
                ]);

                foreach ($question['options'] as $option) {
                    QuestionOption::factory()->create([
                        'question_id' => $testQuestion->id,
                        'content' => $option['content'],
                        'value' => $option['value'],
                    ]);
                }
            }
        }

        foreach ($users as $user) {
            $testCollection = Collection::factory()->create([
                'user_id' => $user->id,
            ]);

            for ($i = 0; $i < 11; $i++) {
                $totalPoints = rand($testsPointsMap[$testsValues[$i]['keyName']]['min'], $testsPointsMap[$testsValues[$i]['keyName']]['max']);

                TestForm::factory()->create([
                    'test_collection_id' => $testCollection->id,
                    'test_name' => $testsValues[$i]['displayName'],
                    'total_points' => $totalPoints,
                    'severity_title' => 'oi',
                    'severity_color' => 'klçda',
                    'recommendation' => 'djalsd',
                    'test_type_id' => $i + 1,
                ]);
            }
        }

    }
}
