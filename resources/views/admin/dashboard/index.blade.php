<x-layouts.app>
    <div class="w-screen h-screen flex overflow-hidden pt-16 md:pt-0">
        <x-sidebar />
        
        <div class="flex-1 overflow-auto p-4 md:p-8 flex flex-col items-start justify-start gap-6">
            <div class="bg-white/25 w-fit px-6 py-2 rounded-md shadow-md">
                <h2 class="text-2xl md:text-4xl text-gray-800 font-semibold text-left">Dashboard</h2>
            </div>
    
            <div class="bg-white/25 w-full px-6 py-2 rounded-md shadow-md">
                <p class="text-sm md:text-base text-gray-800 font-normal text-left flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-lg"></i>
                    Compilado geral dos resultados de todos os colaboradores.
                </p>
            </div>

            <div id="charts" class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <div class="shadow-md rounded-md w-full bg-white/25 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                    <div class="w-full h-full p-5 flex flex-col justify-start gap-4 items-center">
                        <p class="text-center">Participação nos testes</p>
                        <div class="w-40 h-40 xl:w-44 xl:h-44" id="Participação">
                            
                        </div>
                    </div>
                </div>
                @foreach ($generalResults as $testName => $testData)
                    <div class="shadow-md rounded-md w-full flex flex-col items-center bg-white/25 relative left-0 top-0 hover:left-1 hover:-top-1 transition-all">
                        <a href="{{ route('dashboard.test-result-per-department', $testName)}}" class="w-full px-5 py-6 flex flex-col justify-start gap-4 items-center">
                            <p class="text-center">{{ $testName }}</p>
                            <div class="w-40 h-40 xl:w-44 xl:h-44" id="{{ $testName }}">
                                
                            </div>

                            <div class="w-full flex flex-col gap-2">
                                @foreach ($subfactorResults[$testName] as $subfactorName => $subfactorValue)
                                    <div class="w-full flex flex-col gap-1">
                                        <p class="text-sm">{{ $subfactorName }}</p>
                                        <div data-role="subfactor-bar" data-value="{{ $subfactorValue }}" class="w-full h-5 bg-gray-300/50 rounded-md overflow-hidden">
                                            <div style="width: 0" class="opacity-50 bar h-full"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </a>

                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</x-layouts.app>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


<script>
    const subfactorBars = document.querySelectorAll('[data-role="subfactor-bar"]');

    subfactorBars.forEach((subfactor)=>{
        const value = subfactor.dataset.value;
        const percentage = convertToPercentage(value);
        const bar = subfactor.querySelector('.bar');
        const barWitdth = (value - 1) * 25;
        
        bar.style.width = barWitdth.toFixed() + "%";
        bar.style.backgroundColor = getColor(percentage);
    })

    function convertToPercentage(value) {
        const percentage = (value - 1) * 25;
        return Math.min(Math.max(percentage, 0), 100);
    }

    function getColor(percentage) {
        const hue = (1 - percentage / 100) * 120
        return `hsl(${hue}, 100%, 50%)`;
    }

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
        console.log(testType);
        const testChartId = `chart_${index}`;
        const testChartWrapper = document.getElementById(labels[index])

        const testChartSeverities = Object.keys(testType.general)
        
        const tests = Object.values(testType.general)

        const testChartBackgroundColors = tests.map(test => {
            severityKey = test.severity_color
            
            return severityKey in testSeverityColors
            ? testSeverityColors[severityKey]
            : '#cccccc';
        });

        const testChartData = tests.map(test => {
            return test.count
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