<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                Dashboard
            </h1>

            {{-- <div class="text-center">
                <p>Nome do colaborador: Júlio da Silva</p>
                <p>Função do colaborador: Operador de Prensa</p>
            </div>


            <div class="flex flex-wrap gap-5 w-full max-w-10/12 justify-center">            
                @foreach ($testResults as $testResult)
                    <x-result-card :testResult="$testResult" />
                @endforeach
            
            </div> --}}
            <div class="text-center">
                <p>Média da empresa em geral</p>
            </div>

            <div id="charts" class="grid grid-cols-6 gap-5 w-full justify-center">

            </div>

    
        </div>
    </main>
</x-layouts.app>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartsWrapper = document.querySelector('#charts');

    const testsParticipation = @json($testsParticipation)    


    const testParticipationChartId = 'chart_test_participation';

    const testParticipationChartWrapper = document.createElement('div')
    testParticipationChartWrapper.className = 'shadow-md rounded-md border border-teal-700'
    testParticipationChartWrapper.innerHTML = `<a href="" class="w-full h-full p-4 flex justify-center"><canvas id="${testParticipationChartId}" class=""></canvas></a>`;

    chartsWrapper.appendChild(testParticipationChartWrapper);

    testParticipationChartCanvas = document.getElementById(testParticipationChartId);

    new Chart(testParticipationChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Fez os testes', 'Não fez os testes'],
            datasets: [{
                label: 'Participação nos testes',
                data: testsParticipation,
                backgroundColor: ["#64B5F6", "#BBDEFB"]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Participação nos testes',
                    font: {
                        size: 16,
                        weight: 'normal'
                    },
                    padding: {
                        bottom: 10
                    }
                },
                legend: {
                    display: false,
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            console.log(context.dataset.data)
                            const percentage = ((value / total) * 100).toFixed(1);

                            return ` ${percentage}%`;
                        }
                    }
                }
            }
        }
    });



    const generalResults = @json($generalResults)    
    const labels = Object.keys(generalResults)
    
    console.log(generalResults)
    console.log(labels)

    
    const testSeverityColors = {
        "Ansiedade": {
            "Mínima": "#4CAF50",
            "Leve": "#a9f5ac",
            "Moderada": "#eddd58",
            "Grave": "#f55547",
        },
        "Depressão": {
            "Mínima": "#4CAF50",
            "Leve": "#a9f5ac",
            "Moderada": "#eddd58",
            "Moderadamente grave": "#FFB74D",
            "Grave": "#f55547",
        },
        "Pressão no Trabalho": {
            "Baixa Pressão": "#4CAF50",
            "Pressão Moderada": "#eddd58",
            "Alta Pressão": "#f55547",
        },
        "Pressão por Resultados": {
            "Baixa Pressão": "#4CAF50",
            "Pressão Moderada": "#eddd58",
            "Alta Pressão": "#FFB74D",
            "Pressão Crítica": "#f55547",
        },
        "Insegurança": {
            "Baixa Insegurança": "#4CAF50",
            "Insegurança Moderada": "#eddd58",
            "Alta Insegurança": "#f55547",
        },
        "Conflitos": {
            "Baixo Nível de Conflitos": "#4CAF50",
            "Conflitos Moderados": "#eddd58",
            "Alto Nível de Conflitos": "#f55547",
        },
        "Relações Sociais": {
            "Baixo Risco": "#4CAF50",
            "Risco Moderado": "#eddd58",
            "Alto Risco": "#FFB74D",
            "Risco Crítico": "#f55547",
        },
        "Exigências Emocionais": {
            "Baixa exigência emocional": "#4CAF50",
            "Média exigência emocional": "#eddd58",
            "Alta exigência emocional": "#f55547",
        },
        "Autonomia": {
            "Alta Autonomia": "#4CAF50",
            "Autonomia Moderada": "#eddd58",
            "Baixa Autonomia": "#f55547",
        },
        "Burnout": {
            "Zona de Bem-estar - Baixo Burnout": "#4CAF50",
            "Zona de Alerta - Burnout Moderado": "#eddd58",
            "Zona de Risco - Alto Burnout": "#f55547",
        },
        "Estresse": {
            "Baixo Estresse": "#4CAF50",
            "Estresse Moderado": "#eddd58",
            "Alto Estresse": "#f55547",
        },
    }

    Object.values(generalResults).forEach((testType, index) => {
        const chartId = `chart_${index}`;
        
        const testChartWrapper = document.createElement('div');
        testChartWrapper.className = 'shadow-md rounded-md border border-teal-700';
        testChartWrapper.innerHTML = `<a href="" class="w-full h-full p-4 flex justify-center"><canvas id="${chartId}" class=""></canvas><a/>`;
        
        chartsWrapper.appendChild(testChartWrapper);

        const canvas = document.getElementById(chartId);

        // --------------------------------------------------

        const severityTypes = Object.keys(testType)

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: severityTypes,
                datasets: [{
                    label: 'Severidade',
                    data: severityTypes.map(severity => (
                            testType[severity] ? testType[severity].length : 0
                    )),
                    backgroundColor: severityTypes.map(severity => {
                        return labels.some(testName => 
                            testSeverityColors[testName]?.[severity]
                        ) 
                            ? testSeverityColors[labels[index]][severity] 
                            : '#cccccc';
                    }),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: labels[index],
                        font: {
                            size: 16,
                            weight: 'normal'
                        },
                        padding: {
                            bottom: 10
                        }
                    },
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);

                                return ` ${percentage}%`;
                            }
                        }
                    }
                }
            }
        });
    });

</script>