renderCompanyMetrics();
renderDemographics();

function renderCompanyMetrics(){
    let data = [];
    let labels = [];

    Object.values(companyMetrics).forEach((department) => {
        data.push(department)
    })

    Object.keys(companyMetrics).forEach((department) => {
        labels.push(department);
    });
    
    const chartId = 'company_metrics_chart';

    const wrapper = document.getElementById('company-metrics')
    const colors = [chartDefaultColors.PRIMARY]
    
    createBarChart(wrapper, chartId, labels, data, null, colors, 'vertical', true);
}

function renderDemographics(){
    const keys = Object.keys(demographics);

    
    Object.values(demographics).forEach((demographic, index) => {
        const data = Object.values(demographic).map(($item) => ($item['per_cent']));
        const tooltips = Object.values(demographic).map(($item) => ($item['count']));

        const chartId = `demographic_chart_${index}`;
        const wrapper = document.getElementById(keys[index])
        wrapper.style.height = Object.keys(demographic).length * 60 + "px";
        const labels = Object.keys(demographic)
        const colors = generateHSLAColors(data.length);

        createBarChart(wrapper, chartId, labels, data, tooltips, colors, 'horizontal');
    });   
}

function generateHSLAColors(count, saturation = 60, lightness = 85, alpha = 0.8) {
  return Array.from({ length: count }, (_, i) => {
    const hue = Math.round((360 * i) / count);
    return `hsla(${hue},${saturation}%,${lightness}%,${alpha})`;
  });
}