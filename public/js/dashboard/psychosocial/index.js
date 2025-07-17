document.addEventListener('DOMContentLoaded', function(){
    renderPsychosocialTestsParticipation();
    renderPsychosocialRiskBars()
});

function renderPsychosocialRiskBars(){
    const riskBars = document.querySelectorAll('[data-role="risk-bar"]');
    riskBars.forEach((risk)=>{
        const value = risk.dataset.value;
        const bar = risk.querySelector('.bar');
        const barWidth = (value / 4) * 100; 
        
        let barBGColor = riskColors[value] ?? riskColors.default;
        
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

const riskColors = {
    1: '#A8E6CFCC',
    2: '#DDE26F75',
    3: '#F6B26B75',
    4: '#F26C6C75',
    default: '#333',
};