Chart.register(ChartDataLabels);

const chartDefaultColors = Object.freeze({
  PRIMARY: "#64B5F680",
  SECONDARY: '#BBDEFB80',
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
function renderMetricBars(){
    const metricBars = document.querySelectorAll('[data-role="metric-bar"]');
    metricBars.forEach((metric) => {
        const value = metric.dataset.value;
        const bar = metric.querySelector('.bar');
        bar.style.width = value + "%";
        bar.style.backgroundColor = chartDefaultColors.PRIMARY;
    });
}

function removeMetricBars(){
    const metricBars = document.querySelectorAll('[data-role="metric-bar"]');
    metricBars.forEach((metric) => {
        const bar = metric.querySelector('.bar');
        bar.style.width = "0%";
    });
}

// Risk Bars
function renderPsychosocialRiskBars(){
    const riskBars = document.querySelectorAll('[data-role="risk-bar"]');
    riskBars.forEach((risk)=>{
        const value = risk.dataset.value;
        const bar = risk.querySelector('.bar');
        const barWidth = (value / 3) * 100; 
        
        bar.style.backgroundColor = getColor(value);
        bar.style.width = barWidth.toFixed() + "%";
    })
}

function removePsychosocialRiskBars(){
    const riskBars = document.querySelectorAll('[data-role="risk-bar"]');
    riskBars.forEach((risk)=>{
        const bar = risk.querySelector('.bar');
        bar.style.width = "0%";
    })
}

const activeCharts = [];

renderDashboardCharts();

function renderDashboardCharts(){
    renderTestParticipacionChart();
    renderPsychosocialCharts();
    renderPsychosocialRiskBars()
    renderMetricBars();
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
            return severityColors[test.severity_color]
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
        wrapper.style.height = Object.keys(testType).length * 60 + "px";
        colors = generateHSLAColors(Object.keys(testType).length)
        
        createBarChart(wrapper, chartId, labels, data, colors, "Índice de Satisfação (%)", 'horizontal');
    });
}

function renderGeneralOrganizationBars(){
    const chartId = 'test_participation_chart';
    const wrapper = document.getElementById('general-bars')
    const colors = generateHSLAColors(Object.keys(dashboardResults['organizational-climate']).length)
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

    let delayed;

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
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    formatter: function(value) {
                        return value.toFixed() + '%';
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
            },
            animation: {
                duration: 1000,
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 100 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
        }
    });

    activeCharts.push(chart);
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

    activeCharts.push(chart);
}

function toggleCollectionTab(button){
    const bgElement = button.parentElement.querySelector('[data-role="toggle-bg"]');

    if(button.id == 'psychosocial-risks'){
        bgElement.style.left = '4px';
        renderPsychosocialTab();
    } else if(button.id == 'organizational-climate'){
        bgElement.style.left = 'calc(33% + 4px)';
        renderOrganizationalTab();
    } else {
        bgElement.style.left = 'calc(66% + 4px)';
        renderCompanyDemographicsTab();
    }
}

function renderCompanyDemographicsTab(){
    clearTabs();
    document.querySelector('#company-demographic-metrics-tab').style.display = 'contents';
}

function renderOrganizationalTab(){
    clearTabs();
    document.querySelector('#organizational-climate-tab').style.display = 'contents';
    removeActiveCharts();
    renderOrganizationalCharts();
    renderGeneralOrganizationBars();
}

function renderPsychosocialTab(){
    clearTabs();
    document.querySelector('#psychosocial-risks-tab').style.display = 'contents';
    removeActiveCharts();
    renderTestParticipacionChart();
    renderPsychosocialCharts();
    renderPsychosocialRiskBars();
    renderMetricBars();
}

function clearTabs(){
    const tabs = document.querySelectorAll('[data-role="collection-tab"]');
    
    tabs.forEach((tab)=> {
        tab.style.display = 'none'
    })
}

function removeActiveCharts(){
    activeCharts.forEach((chart) => {
        chart.remove();
    })

    removePsychosocialRiskBars();
    removeMetricBars();
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

function generateHSLAColors(count, saturation = 60, lightness = 85, alpha = 0.8) {
  return Array.from({ length: count }, (_, i) => {
    const hue = Math.round((360 * i) / count);
    return `hsla(${hue},${saturation}%,${lightness}%,${alpha})`;
  });
}