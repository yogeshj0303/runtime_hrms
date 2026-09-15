var attendancePieChart = null;
var latePieChart = null;
var earlyPieChart = null;

function makePieChart(selector, series, labels, colors){
    var el = document.querySelector(selector);

    if(!el){
        return null;
    }

    var options = {
        series: series,
        chart: {
            type: 'pie',
            height: 130,
            toolbar: {
                show: false
            },
            fontFamily: 'Arial, Helvetica, sans-serif'
        },
        labels: labels,
        colors: colors,
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '10px',
            fontWeight: 600,
            markers: {
                width: 10,
                height: 10,
                radius: 0
            },
            itemMargin: {
                horizontal: 5,
                vertical: 0
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ['#ffffff']
        },
        tooltip: {
            y: {
                formatter: function(value){
                    return value;
                }
            }
        }
    };

    var chart = new ApexCharts(el, options);
    chart.render();

    return chart;
}

function loadAttendanceCharts(){
    attendancePieChart = makePieChart(
        '#attendancePie',
        [0, 0, 10, 0, 0],
        ['Presents', 'Leaves', 'Absents', 'WeekOffs', 'Holidays'],
        ['#3b82f6', '#f8d980', '#ff5757', '#8b8b8b', '#22c55e']
    );

    latePieChart = makePieChart(
        '#latePie',
        [0, 10],
        ['On-Time', 'Late Comers'],
        ['#3b82f6', '#ffb366']
    );

    earlyPieChart = makePieChart(
        '#earlyPie',
        [10, 0],
        ['On-Time', 'Early Goers'],
        ['#3b82f6', '#ffb366']
    );
}

function refreshAttendancePage(){
    var btnIcon = document.querySelector('#attendanceLoadBtn i');

    if(btnIcon){
        btnIcon.classList.add('spin');
    }

    setTimeout(function(){

        if(attendancePieChart){
            attendancePieChart.updateSeries([0, 0, 10, 0, 0]);
        }

        if(latePieChart){
            latePieChart.updateSeries([0, 10]);
        }

        if(earlyPieChart){
            earlyPieChart.updateSeries([10, 0]);
        }

        if(btnIcon){
            btnIcon.classList.remove('spin');
        }

    }, 600);
}

document.addEventListener('DOMContentLoaded', function(){

    loadAttendanceCharts();

    var loadBtn = document.getElementById('attendanceLoadBtn');

    if(loadBtn){
        loadBtn.addEventListener('click', function(){
            refreshAttendancePage();
        });
    }

});