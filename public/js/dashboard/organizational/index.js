renderGeneralOrganizationBars();
renderOrganizationalCharts();
renderOrganizationalTestsParticipation();

function renderOrganizationalTestsParticipation(){
    let data = [];
    let tooltips = [];
    let labels = [];

    Object.values(organizationalTestsParticipation).forEach((department) => {
        data.push(department['per_cent'])
    })

    Object.values(organizationalTestsParticipation).forEach((department) => {
        tooltips.push(department['count'])
    })
    
    Object.keys(organizationalTestsParticipation).forEach((department) => {
        labels.push(department);
    });

    const chartId = 'organizational_participation_chart';

    const wrapper = document.getElementById('organizational-participation')
    const colors = [chartDefaultColors.PRIMARY]
    
    createBarChart(wrapper, chartId, labels, data, tooltips, colors);
}

function renderOrganizationalCharts(){
    const key = Object.keys(organizationalClimateResults);
    Object.values(organizationalClimateResults).forEach((testType, index) => {
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
        
        createBarChart(wrapper, chartId, labels, data, null, colors);
    });
}

function renderGeneralOrganizationBars(){
    const chartId = 'test_participation_chart';
    const wrapper = document.getElementById('general-bars')
    const colors = generateHSLAColors(Object.keys(organizationalClimateResults).length)
    let data = [];
    let labels = [];

    Object.values(organizationalClimateResults).forEach((testType) => {
        if(testType['Geral']){
            data.push(testType['Geral']['total_average'])
        } else{
            data.push(Object.values(testType)[0]['total_average'])
        }
    });

    Object.keys(organizationalClimateResults).forEach((testType) => {
        labels.push(testType);
    })

    let generalSum = data.reduce((acumulador, valorAtual) => acumulador + valorAtual, 0);
    let generalAverage = generalSum / data.length;

    data.unshift( Number(generalAverage.toFixed()))
    labels.unshift('Geral')

    createBarChart(wrapper, chartId, labels, data, null, colors);
}

function generateHSLAColors(count, saturation = 60, lightness = 85, alpha = 0.8) {
  return Array.from({ length: count }, (_, i) => {
    const hue = Math.round((360 * i) / count);
    return `hsla(${hue},${saturation}%,${lightness}%,${alpha})`;
  });
}

const triggerFilterModal = document.querySelector(
    '[data-role="filter-modal-trigger"]'
);

const filterModal = document.querySelector('[data-role="filter-modal"]');

body.addEventListener("click", function (event) {
    if (event.target === filterModal) {
        filterModal.classList.replace("flex", "hidden");
    }
});

triggerFilterModal.addEventListener("click", function () {
    filterModal.classList.replace("hidden", "flex");
});