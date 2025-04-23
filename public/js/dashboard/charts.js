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
    DETAILED_PERCENT: 'detailed_percent',
    LABEL: 'label'
})


function createBarChart(wrapper, chartId, labels, data, colors = null, orientation = 'vertical', zeroEqualsUndefined = false){
    const chart = document.createElement('canvas');
    chart.classList = 'w-full';
    chart.id = chartId;


    if(!wrapper){
        return;
    }
    
    const container = wrapper;
    container.appendChild(chart);

    let delayed;

    new Chart(chart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: 1,
                borderRadius: 4,
                minBarLength: 2,
            }]
        },
        options: {
            legend: {
                display: false
            },
            responsive: true,
            indexAxis: orientation == 'horizontal' ? 'y' : 'x',
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false,
                },
                legend: {
                    display: false,
                },
                datalabels: {
                    anchor: 'center',
                    formatter: function(value) {
                        if(zeroEqualsUndefined && value == 0){
                            return 'Não informado';
                        }
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
                            
                            if(zeroEqualsUndefined && value == 0){
                                return 'Não informado';
                            }

                            return ` ${value.toFixed()}%`;
                        }
                    }
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                    min: 0,
                    max: 100,
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
}

function createDoughnutChart(wrapper, chartId, labels = [], data, colors, labelType){
    const chart = document.createElement('canvas');
    chart.classList = 'w-40! h-40!';
    chart.id = chartId;

    if(!wrapper){
        return;
    }

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

            if(labelType === chartLabelTypes.LABEL){
                return context.chart.data.labels[context.dataIndex];
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