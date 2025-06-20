window.addEventListener('DOMContentLoaded', () => {

    setTimeout(function () {
        const button = document.getElementById('create-report-button');
        if (button) {
            button.disabled = false;
            button.style.opacity = '100%';
        }
    }, 2500);

    function createColorPalette(count, saturation = 60, lightness = 85, alpha = 0.8) {
        return Array.from({ length: count }, (_, i) => {
            const hue = Math.round((360 * i) / count);
            return `hsla(${hue},${saturation}%,${lightness}%,${alpha})`;
        });
    }

    function renderOrganizationalTestsParticipationChart() {
        const chartId = 'organizational_participation_chart';
        const wrapper = document.getElementById('organizational-participation');
        const colors = [chartDefaultColors.PRIMARY];

        const data = Object.values(organizationalTestsParticipation).map(dep => dep['per_cent']);
        const tooltips = Object.values(organizationalTestsParticipation).map(dep => dep['count']);
        const labels = Object.keys(organizationalTestsParticipation);

        createBarChart(wrapper, chartId, labels, data, tooltips, colors);
    }

    function renderOrganizationalCategoryCharts() {
        const departmentKeys = Object.keys(organizationalClimateResults['main']);

        Object.values(organizationalClimateResults['main']).forEach((testType, index) => {
            const chartId = `chart_i_${index}`;
            const wrapper = document.getElementById(departmentKeys[index]);
            const labels = Object.keys(testType);
            
            let data = [];
            let colors = [];
            
            if(organizationalClimateResults['general']){
                data.push(labels.map(label => organizationalClimateResults['general'][departmentKeys[index]]['Geral da empresa']['total_average']))
                colors.push([`hsla(0, 0%, 75%, 0.8)`]);
                data.push(labels.map(label => testType[label]['total_average']))
                colors.push(createColorPalette(labels.length));
            } else{   
                data = labels.map(label => testType[label]['total_average']);
                colors = createColorPalette(labels.length);
            }
                
            createBarChart(wrapper, chartId, labels, data, null, colors);
        });
    }

    function renderGeneralSummaryChart() {
        const chartId = 'test_indicators_chart';
        const wrapper = document.getElementById('Geral');
        const testTypes = Object.values(organizationalClimateResults['main']);
        const labels = Object.keys(organizationalClimateResults['main']);
        const colors = createColorPalette(labels.length);

        const data = testTypes.map(test =>
            test['Geral'] ? test['Geral']['total_average'] : Object.values(test)[0]['total_average']
        );

        const generalAverage = data.reduce((sum, val) => sum + val, 0) / data.length;

        data.unshift(Number(generalAverage.toFixed()));
        labels.unshift('Geral');

        createBarChart(wrapper, chartId, labels, data, null, colors);
    }

    renderGeneralSummaryChart();
    renderOrganizationalCategoryCharts();
    renderOrganizationalTestsParticipationChart();
});