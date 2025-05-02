renderPsychosocialTestsParticipation();
renderPsychosocialCharts();
renderPsychosocialRiskBars()

function renderPsychosocialRiskBars(){
    const riskBars = document.querySelectorAll('[data-role="risk-bar"]');
    riskBars.forEach((risk)=>{
        const value = risk.dataset.value;
        const bar = risk.querySelector('.bar');
        const barWidth = (value / 3) * 100; 
        
        let barBGColor = '';
        
        if(value == 1){
            barBGColor =  `#4CAF5075`
        } else if(value == 2){
            barBGColor =  `#eddd5875`
        } else{
            barBGColor =  `#f5554775`
        }

        bar.style.backgroundColor = barBGColor;
        bar.style.width = barWidth.toFixed() + "%";
    })
}

function renderPsychosocialTestsParticipation(){
    let data = [];
    let tooltips = [];
    let labels = [];

    Object.values(psychosocialTestsParticipation).forEach((department) => {
        data.push(department['per_cent'])
    })

    Object.values(psychosocialTestsParticipation).forEach((department) => {
        tooltips.push(department['count'])
    })

    Object.keys(psychosocialTestsParticipation).forEach((department) => {
        labels.push(department);
    });

    const chartId = 'psychosocial_participation_chart';

    const wrapper = document.getElementById('psychosocial-participation')
    const colors = [chartDefaultColors.PRIMARY]
    
    createBarChart(wrapper, chartId, labels, data, tooltips, colors);
}

function renderPsychosocialCharts(){
    const keys = Object.keys(psychosocialRiskResults);
    Object.values(psychosocialRiskResults).forEach((testType, index) => {
        const data = Object.values(testType.severities).map(test => {
            return test.count
        });
    
        const chartId = `psychosocial_chart_${index}`;
        const wrapper = document.getElementById(keys[index])

        const colors = Object.values(testType.severities).map(test => {
            return severityColors[test.severity_color]
        });

        const labels = Object.keys(testType.severities)

        createDoughnutChart(wrapper, chartId, labels, data, colors, chartLabelTypes.DETAILED_PERCENT);
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