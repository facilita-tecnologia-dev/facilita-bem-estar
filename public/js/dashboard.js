Chart.register(ChartDataLabels);

const chartDefaultColors = Object.freeze({
  PRIMARY: "#64B5F680",
  SECONDARY: '#BBDEFB80',
});

const severityMap = Object.freeze({
    1: 'MINIMO',
    2: 'BAIXO',
    3: 'MEDIO',
    4: 'ALTO',
    5: 'CRITICO',
});

const severityColors = Object.freeze({
    MINIMO: "#4CAF5080",
    BAIXO: "#a9f5ac80",
    MEDIO: "#eddd5880",
    ALTO: "#FFB74D80",
    CRITICO: "#f5554780",
});

const chartLabelTypes = Object.freeze({
    FRACTION: 'fraction',
    PERCENT: 'percent',
    DETAILED_PERCENT: 'detailed_percent'
})

// Dashboard collection tabs
const tabs = document.querySelectorAll('[data-role="collection-tab"]');
const toggleCollectionTabTriggers = document.querySelectorAll('[data-role="toggle-collection-tab"]');

toggleCollectionTabTriggers.forEach(button => {
    button.addEventListener('click', function(){
        toggleCollectionTab(this);
    });
});

// Metric Bars
const metricBars = document.querySelectorAll('[data-role="metric-bar"]');
metricBars.forEach((metric) => {
    const value = metric.dataset.value;
    const bar = metric.querySelector('.bar');
    bar.style.width = value + "%";
    bar.style.backgroundColor = chartDefaultColors.PRIMARY;
});

// Risk Bars
const riskBars = document.querySelectorAll('[data-role="risk-bar"]');
riskBars.forEach((risk)=>{
    const value = risk.dataset.value;
    const bar = risk.querySelector('.bar');
    const barWidth = (value / 3) * 100; 
    
    bar.style.width = barWidth.toFixed() + "%";
    bar.style.backgroundColor = getColor(value);
})

renderDashboardCharts();

function renderDashboardCharts(){
    renderTestParticipacionChart();
    renderPsychosocialCharts();
    renderOrganizationalCharts();
    renderGeneralOrganizationBars();
}

function renderTestParticipacionChart(){
    const data = testParticipation;
    const chartId = 'test_participation_chart';
    const labels = ['Fez os testes', 'Não fez os testes']
    const wrapper = document.getElementById('Participação')
    const colors = [chartDefaultColors.PRIMARY, chartDefaultColors.SECONDARY]
    
    createDoughnutChart(wrapper, chartId, labels, data, colors, chartLabelTypes.FRACTION);
}

function renderPsychosocialCharts(){
    const keys = Object.keys(dashboardResults['psychosocial-risks']);
    Object.values(dashboardResults['psychosocial-risks']).forEach((testType, index) => {
        const data = Object.values(testType.severities).map(test => {
            return test.count
        });
        const chartId = `chart_${index}`;
        const wrapper = document.getElementById(keys[index])
        const colors = Object.values(testType.severities).map(test => {
            return test.severity_color in severityMap
            ? severityColors[severityMap[test.severity_color]]
            : chartDefaultColors.PRIMARY;
        });
        const labels = Object.keys(testType.severities)
        
        createDoughnutChart(wrapper, chartId, labels, data, colors, chartLabelTypes.DETAILED_PERCENT);
    });   
}

function renderOrganizationalCharts(){
    const key = Object.keys(dashboardResults['organizational-climate']);
    Object.values(dashboardResults['organizational-climate']).forEach((testType, index) => {
        let data = [];
        let labels = [];

        Object.values(testType).forEach((category) => {
            data.push(category['total_average'])
        })
        
        Object.keys(testType).forEach((category) => {
            labels.push(category)
        })


        const chartId = `chart_i_${index}`;
        const wrapper = document.getElementById(key[index])
        colors = [chartDefaultColors.PRIMARY, chartDefaultColors.SECONDARY]
        

        createBarChart(wrapper, chartId, labels, data, colors, "Índice de Satisfação (%)", 'horizontal');
    });
}

function renderGeneralOrganizationBars(){
    const chartId = 'test_participation_chart';
    const wrapper = document.getElementById('general-bars')
    const colors = [chartDefaultColors.PRIMARY, chartDefaultColors.SECONDARY]
    let data = [];
    let labels = [];

    Object.values(dashboardResults['organizational-climate']).forEach((testType) => {
        data.push(testType['Geral']['total_average'])
    });

    Object.keys(dashboardResults['organizational-climate']).forEach((testType) => {
        labels.push(testType);
    })

    let generalSum = data.reduce((acumulador, valorAtual) => acumulador + valorAtual, 0);
    let generalAverage = generalSum / data.length;

    data.unshift( Number(generalAverage.toFixed()))
    labels.unshift('Geral')

    createBarChart(wrapper, chartId, labels, data, colors, "Índice de Satisfação (%)");
}

function createBarChart(wrapper, chartId, labels, data, colors = null, title = "Título", orientation = 'vertical'){
    const chart = document.createElement('canvas');
    chart.classList = 'w-full';
    chart.id = chartId;

    const container = wrapper;
    container.appendChild(chart);

    new Chart(chart, {
        type: 'bar',
        data: {
        labels: labels,
        datasets: [{
            label: title,
            data: data,
            backgroundColor: colors,
            borderWidth: 1,
            barThickness: 40,
            minBarLength: 2,
        }]
        },
        options: {
            responsive: true,
            indexAxis: orientation == 'horizontal' ? 'y' : 'x',
            plugins: {
                datalabels: {
                    formatter: function(value) {
                        return value + '%';
                    },
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            let value = 0;
                            if(orientation == 'vertical'){
                                value = context.parsed.y
                            } else{
                                value = context.parsed.x;
                            }
                            
                            return ` ${value}%`;
                        }
                    }
                },
            },
            scales: {
                x: {
                    ticks: {
                    callback: function(value, index, ticks) {
                        const matches = this.getLabelForValue(value).match(/\(([^)]+)\)/g);
                        const sigla = matches ? matches.map(item => item.slice(1, -1)) : [];
                        if(matches){
                            return sigla[0];
                        } else{
                            return this.getLabelForValue(value);
                        }
                    }
                    },
                    offset: true
                },
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 100
                }
            }
        }
    });
}

function createDoughnutChart(wrapper, chartId, labels = [], data, colors, labelType){
    const chart = document.createElement('canvas');
    chart.classList = 'w-40! h-40!';
    chart.id = chartId;

    const container = wrapper;
    container.appendChild(chart);

    const datalabels = {
        display: true,
        color: '#333',
        font: {
            size: 14
        },
        formatter: (value, context) => {
            if(labelType === chartLabelTypes.PERCENT){
                const percentage = value.toFixed();
                return ` ${percentage}%`;
            }
            
            const total = context.dataset.data.reduce((a, b) => a + b, 0);

            if(labelType === chartLabelTypes.FRACTION){
                return `${value}/${total}`;
            }

            if(labelType === chartLabelTypes.DETAILED_PERCENT){
                const percentage = ((value / total) * 100).toFixed(0);
                return `${percentage}%`;
            }
        }
    }

    new Chart(chart, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors
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
                            
                            if(labelType === chartLabelTypes.PERCENT){
                                const percentage = value.toFixed();
                                return ` ${percentage}%`;
                            }
                            
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);

                            if(labelType === chartLabelTypes.FRACTION){
                                return `${value}/${total}`;
                            }

                            if(labelType === chartLabelTypes.DETAILED_PERCENT){
                                const percentage = ((value / total) * 100).toFixed(0);
                                return `${percentage}%`;
                            }
                        }
                    }
                },
                datalabels: datalabels
            }
        }
    });
}

function toggleCollectionTab(button){
    const bgElement = button.parentElement.querySelector('[data-role="toggle-bg"]');

    if(button.id == 'organizational-climate'){
        bgElement.classList.replace('left-1', 'left-[calc(50%+4px)]')
        document.querySelector('#psychosocial-risks-tab').style.display = 'none';
        document.querySelector('#organizational-climate-tab').style.display = 'contents';
    } else{
        bgElement.classList.replace('left-[calc(50%+4px)]', 'left-1')
        document.querySelector('#organizational-climate-tab').style.display = 'none';
        document.querySelector('#psychosocial-risks-tab').style.display = 'contents';
    }
}

function getColor(value) {
    if(value == 1){
        return `#4CAF5075`
    } else if(value == 2){
        return `#eddd5875`
    } else{
        return `#f5554775`
    }
}