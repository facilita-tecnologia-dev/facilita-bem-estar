{{-- @dump($generalResults) --}}

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

            <div id="charts" class="flex flex-wrap gap-5 w-full justify-center">            
            </div>

    
        </div>
    </main>
</x-layouts.app>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const generalResults = @json($generalResults)

    console.log(generalResults)

    const labels = Object.keys(generalResults)

    console.log(labels)

    
    const testSeverityColors = {
        "Ansiedade": {
            "Mínima": "#28a745",
            "Leve": "#a9f5ac",
            "Moderada": "#ffcc00",
            "Grave": "#f44336",
        },
        "Depressão": {
            "Mínima": "#28a745",
            "Leve": "#a9f5ac",
            "Moderada": "#ffcc00",
            "Moderadamente grave": "#ff9800",
            "Grave": "#f44336",
        },
        "Pressão no Trabalho": {
            "Baixa Pressão": "#28a745",
            "Pressão Moderada": "#ffcc00",
            "Alta Pressão": "#f44336",
        },
        "Pressão por Resultados": {
            "Baixa Pressão": "#28a745",
            "Pressão Moderada": "#ffcc00",
            "Alta Pressão": "#ff9800",
            "Pressão Crítica": "#f44336",
        },
        "Insegurança": {
            "Baixa Insegurança": "#28a745",
            "Insegurança Moderada": "#ffcc00",
            "Alta Insegurança": "#f44336",
        },
        "Conflitos": {
            "Baixo Nível de Conflitos": "#28a745",
            "Conflitos Moderados": "#ffcc00",
            "Alto Nível de Conflitos": "#f44336",
        },
        "Relações Sociais": {
            "Baixo Risco": "#28a745",
            "Risco Moderado": "#ffcc00",
            "Alto Risco": "#ff9800",
            "Risco Crítico": "#f44336",
        },
        "Exigências Emocionais": {
            "Baixa exigência emocional": "#28a745",
            "Média exigência emocional": "#ffcc00",
            "Alta exigência emocional": "#f44336",
        },
        "Autonomia": {
            "Alta Autonomia": "#28a745",
            "Autonomia Moderada": "#ffcc00",
            "Baixa Autonomia": "#f44336",
        },
        "Burnout": {
            "Zona de Bem-estar - Baixo Burnout": "#28a745",
            "Zona de Alerta - Burnout Moderado": "#ffcc00",
            "Zona de Risco - Alto Burnout": "#f44336",
        },
        "Estresse": {
            "Baixo Estresse": "#28a745",
            "Estresse Moderado": "#ffcc00",
            "Alto Estresse": "#f44336",
        },
    }

    Object.values(generalResults).forEach((testType, index) => {
        const chartId = `chart_${index}`;
        
        const chartWrapper = document.createElement('div');
        chartWrapper.className = 'shadow-md rounded-md border border-teal-700 mb-4 p-4';
        chartWrapper.innerHTML = `<canvas id="${chartId}" class="w-40! h-40!"></canvas>`;
        
        const wrapper = document.querySelector('#charts');
        wrapper.appendChild(chartWrapper);

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

    console.log(Object.values(generalResults))
</script>