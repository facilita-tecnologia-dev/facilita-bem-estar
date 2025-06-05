

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


function createBarChart(wrapper, chartId, labels, data, tooltips = null, colors = null, orientation = 'vertical', zeroEqualsUndefined = false){
    const chart = document.createElement('canvas');
    chart.classList = 'w-full';
    chart.id = chartId;
    
    if(orientation == 'vertical'){
        chart.style.width = '100%';
        chart.style.height = '250px';
        chart.style.minWidth = 120 * data.length + 'px';
    } else{
        chart.style.height = 40 * data.length + 'px';
    }

    if(!wrapper){
        return;
    }
    
    const container = wrapper;
    container.appendChild(chart);

    let delayed;
    let captured;

    let datasets = [];

    const isMultiDataset = Array.isArray(data) && data.every(item => Array.isArray(item));
    const isMultiColor = Array.isArray(colors) && colors.every(c => Array.isArray(c));
    const isMultiLabel = Array.isArray(labels) && labels.every(l => Array.isArray(l));


    if (isMultiDataset) {
        data.forEach((array, index) => {
            datasets.push({
                data: array,
                backgroundColor: isMultiColor ? colors[index] : colors,
                borderWidth: 1,
                borderRadius: 4,
                minBarThickness: 70,
                label: isMultiLabel ? labels[index] : undefined
            });
        });
    } else {
        datasets.push({
            data: data,
            backgroundColor: colors,
            borderWidth: 1,
            borderRadius: 4,
            minBarThickness: 70,
            label: !isMultiLabel && typeof labels === 'string' ? labels : undefined
        });
    }

    new Chart(chart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            legend: {
                display: false
            },
            responsive: false,
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
                            const chart = context[0].chart;
                            const datasetCount = chart.data.datasets.length;
                            const datasetIndex = context[0].datasetIndex;
                        
                            if (datasetCount > 1 && datasetIndex === 0) {
                                return 'Geral da empresa';
                            }
                        
                            return context[0].label;
                        },
                        label: function(context) {
                            if(tooltips){
                                return ` ${tooltips[context.dataIndex]} pessoas`
                            }

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
                        font: {
                            size: 13
                        },
                        callback: function(value, index, ticks) {
                            const label = String(this.getLabelForValue(value));
                            const maxLineLength = 15; // caracteres por linha (ajuste conforme necessário)
                            const words = label.split(' ');
                            const lines = [];
                            let currentLine = '';

                            for (const word of words) {
                                if ((currentLine + word).length <= maxLineLength) {
                                currentLine += word + ' ';
                                } else {
                                lines.push(currentLine.trim());
                                currentLine = word + ' ';
                                }
                            }

                            if (currentLine) lines.push(currentLine.trim());

                            return lines;
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
                    if (!captured) {
                        const image = chart.toDataURL(); 
                        const reportChartInput = document.querySelector(`input[name="${wrapper.id}-to-base-64"]`)
                        if(reportChartInput){
                            reportChartInput.value = image;
                        }
                        captured = true;
                    }
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
        align: 'center',
        display: 'auto',
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