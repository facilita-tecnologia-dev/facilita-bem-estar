<x-layouts.app>
    <main class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white rounded-md w-full max-w-screen-2xl min-h-2/4 max-h-screen shadow-md m-8 p-10 flex flex-col items-center justify-center gap-6">
            <h1 class="text-4xl font-semibold leading-tight tracking-tight text-teal-700  text-center">
                Dashboard
            </h1>

            <div class="text-center">
                <p class="text-lg font-medium">Resultados de todos os testes de bem-estar realizados pelos funcionários</p>
            </div>

            <div id="charts" class="grid grid-cols-6 gap-5 w-full justify-center">
                <div class="shadow-md rounded-md border border-teal-700">
                    <a id="Participação" href="" class="w-full h-full p-4 flex flex-col gap-3 items-center">
                        <p>Participação nos testes</p>
                    
                    </a>
                </div>
    
                @foreach ($generalResults as $testName => $testData)
                    <div class="shadow-md rounded-md border border-teal-700">
                        <a id="{{ $testName }}" href="{{ route('test-results.dashboard', $testName)}}" class="w-full h-full p-4 flex flex-col gap-3 items-center">
                            <p>{{ $testName }}</p>

                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </main>
</x-layouts.app>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


<script>

    // Tests Participation

    const testsParticipationChartData = @json($testsParticipation)   

    const testParticipationChartId = 'chart_test_participation';

    const chartWrapper = document.getElementById('Participação')

    testParticipationChartLabels = ['Fez os testes', 'Não fez os testes']
    testParticipationChartBackgroundColors = ["#64B5F6", "#BBDEFB"]

    createNewChart(chartWrapper, testParticipationChartId, testParticipationChartLabels, testsParticipationChartData, testParticipationChartBackgroundColors, 'number')


    // Tests

    const generalResults = @json($generalResults)    
    const labels = Object.keys(generalResults)

   

    const testSeverityColors = {
        1: "#4CAF50",
        2: "#a9f5ac",
        3: "#eddd58",
        4: "#FFB74D",
        5: "#f55547",
    }

    // const testSeverityColors = {
    //     "Ansiedade": {
    //         1: "#4CAF50",
    //         2: "#a9f5ac",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Depressão": {
    //         1: "#4CAF50",
    //         2: "#a9f5ac",
    //         3: "#eddd58",
    //         4: "#FFB74D",
    //         5: "#f55547",
    //     },
    //     "Pressão no Trabalho": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Pressão por Resultados": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         4: "#FFB74D",
    //         5: "#f55547",
    //     },
    //     "Insegurança": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Conflitos": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Relações Sociais": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         4: "#FFB74D",
    //         5: "#f55547",
    //     },
    //     "Exigências Emocionais": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Autonomia": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Burnout": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    //     "Estresse": {
    //         1: "#4CAF50",
    //         3: "#eddd58",
    //         5: "#f55547",
    //     },
    // }

    Object.values(generalResults).forEach((testType, index) => {
      
        const testChartId = `chart_${index}`;
        const testChartWrapper = document.getElementById(labels[index])

        const testChartSeverities = Object.keys(testType)

        const testChartSeverityColors = Object.values(testType)

        const testChartBackgroundColors = testChartSeverityColors.map(severity => {
            severityKey = severity[0].severityColor
            
            return severityKey in testSeverityColors
            ? testSeverityColors[severityKey]
            : '#cccccc';
        });

        const testChartData = testChartSeverities.map(severity => (
                            testType[severity] ? testType[severity].length : 0
        ));

        
        createNewChart(testChartWrapper, testChartId, testChartSeverities, testChartData, testChartBackgroundColors)
    });


    function createNewChart(chartWrapper, canvasId, labels, data, backgroundColor, dataType){

        Chart.register(ChartDataLabels);

        const chartCanvas = document.createElement('canvas');
        chartCanvas.classList = 'w-40! h-40!';
        chartCanvas.id = canvasId;

        const chartContainer = chartWrapper;
        chartContainer.appendChild(chartCanvas);


        new Chart(chartCanvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    // label: 'Severidade',
                    data: data,
                    backgroundColor: backgroundColor
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: false,
                        // text: 'oi',
                        // font: {
                        //     size: 16,
                        //     weight: 'normal'
                        // },
                        // padding: {
                        //     bottom: 10
                        // }
                    },
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                
                                if(dataType === 'number'){
                                    return ` ${value}/${total}`;
                                } else{
                                    const percentage = ((value / total) * 100).toFixed(1);   
                                    return ` ${percentage}%`;
                                }
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        anchor: 'center',
                        align: 'center',
                        color: '#333',
                        font: {
                        weight: 'normal',
                        size: 14
                        },
                        formatter: (value, context) => {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);

                            if(dataType === 'number'){
                                return `${value}/${total}`;
                            } else{
                                const percentage = ((value / total) * 100).toFixed(0);
                                return `${percentage}%`; // Exibe a porcentagem
                            }
                        }
                    }
                }
            }
        });
    }
</script>