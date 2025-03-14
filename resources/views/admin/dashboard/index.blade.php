<x-layouts.app>
    <x-box class="w-full">
        <div class="mr-auto relative md:absolute md:left-10 md:top-4 mb-4">
            <x-actions.anchor href="{{ route('admin.welcome') }}" color="success" variant="solid">
                Voltar
            </x-actions.anchor>
        </div>
        
        <x-heading>
            Dashboard
        </x-heading>

        <div class="text-center mt-4 mb-4">
            <p class="text-sm sm:text-md md:text-lg font-medium">Resultados de todos os testes de bem-estar realizados pelos funcionários</p>
        </div>

        <div id="charts" class="flex-1 flex flex-wrap overflow-auto gap-[1%] gap-y-3 w-full justify-center">
            <div class="shadow-md rounded-md border border-teal-700 w-[100%] min-[430px]:w-[49%] sm:w-[32%] md:w-[24%] lg:w-[19%] xl:w-[15%]">
                <a href="" class="w-full h-full p-4 flex flex-col justify-between gap-3 items-center">
                    <p class="text-center">Participação nos testes</p>
                    <div class="w-36 h-36 xl:w-40 xl:h-40" id="Participação">
                        
                    </div>
                </a>
            </div>

            @foreach ($generalResults as $testName => $testData)
                <div class="shadow-md rounded-md border border-teal-700 w-[100%] min-[430px]:w-[49%] sm:w-[32%] md:w-[24%] lg:w-[19%] xl:w-[15%]">
                    <a href="{{ route('test-results.dashboard', $testName)}}" class="w-full h-full p-4 flex flex-col justify-between gap-3 items-center">
                        <p class="text-center">{{ $testName }}</p>
                        <div class="w-36 h-36 xl:w-40 xl:h-40" id="{{ $testName }}">
                            
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </x-box>
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

    Object.values(generalResults).forEach((testType, index) => {
        const testChartId = `chart_${index}`;
        const testChartWrapper = document.getElementById(labels[index])
        
        const testChartSeverities = Object.keys(testType)
        
        const tests = Object.values(testType)

        const testChartBackgroundColors = tests.map(test => {
            severityKey = test[0].severity_color
            
            return severityKey in testSeverityColors
            ? testSeverityColors[severityKey]
            : '#cccccc';
        });

        const testChartData = tests.map(test => {
            return tests ? test.length : 0
        });
        
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