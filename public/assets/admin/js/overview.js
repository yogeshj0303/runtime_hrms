var attendanceChart = null;

var attendanceData = {
    2026: {
        present: [86, 85, 84, 82, 83, 72, 0, 0, 0, 0, 0, 0],
        absent:  [4, 6, 5, 7, 6, 22, 0, 0, 0, 0, 0, 0],
        leave:   [5, 8, 10, 8, 11, 6, 0, 0, 0, 0, 0, 0]
    },
    2025: {
        present: [78, 81, 79, 84, 86, 88, 87, 85, 83, 80, 82, 84],
        absent:  [12, 9, 10, 7, 6, 5, 7, 8, 9, 11, 8, 7],
        leave:   [10, 10, 11, 9, 8, 7, 6, 7, 8, 9, 10, 9]
    },
    2024: {
        present: [72, 75, 77, 79, 76, 80, 82, 81, 78, 79, 80, 83],
        absent:  [18, 15, 13, 11, 14, 10, 9, 10, 12, 11, 10, 8],
        leave:   [10, 10, 10, 10, 10, 10, 9, 9, 10, 10, 10, 9]
    }
};

function getSelectedYear(){
    var yearSelect = document.getElementById('yearSelect');

    if(yearSelect){
        return yearSelect.value;
    }

    return '2026';
}

function getChartSeries(){
    var year = getSelectedYear();
    var data = attendanceData[year] || attendanceData['2026'];

    return [
        {
            name: 'Presents (%)',
            data: data.present
        },
        {
            name: 'Absents (%)',
            data: data.absent
        },
        {
            name: 'Leaves (%)',
            data: data.leave
        }
    ];
}

function loadAttendanceChart(){
    var chartEl = document.querySelector("#attendance-chart");

    if(!chartEl){
        return;
    }

    var options = {
        series: getChartSeries(),

        chart: {
            type: 'bar',
            height: 245,
            toolbar: {
                show: false
            },
            fontFamily: 'Arial, Helvetica, sans-serif'
        },

        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '24%',
                borderRadius: 1
            }
        },

        dataLabels: {
            enabled: false
        },

        stroke: {
            show: true,
            width: 1,
            colors: ['transparent']
        },

        colors: ['#3b82f6', '#f06b85', '#f8d980'],

        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontWeight: 500
                }
            },
            axisBorder: {
                show: true,
                color: '#dbe4ee'
            },
            axisTicks: {
                show: false
            }
        },

        yaxis: {
            min: 0,
            max: 150,
            tickAmount: 6,
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontWeight: 500
                }
            }
        },

        grid: {
            borderColor: '#edf1f6',
            strokeDashArray: 0,
            xaxis: {
                lines: {
                    show: true
                }
            }
        },

        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'left',
            fontSize: '11px',
            markers: {
                width: 8,
                height: 8,
                radius: 8
            },
            itemMargin: {
                horizontal: 8,
                vertical: 0
            }
        },

        tooltip: {
            y: {
                formatter: function(value){
                    return value + '%';
                }
            }
        },

        fill: {
            opacity: 1
        }
    };

    attendanceChart = new ApexCharts(chartEl, options);
    attendanceChart.render();
}

function refreshAttendanceChart(){
    var refreshIcon = document.querySelector('#refreshAttendanceBtn i');

    if(refreshIcon){
        refreshIcon.classList.add('ri-spin');
    }

    setTimeout(function(){
        if(attendanceChart){
            attendanceChart.updateSeries(getChartSeries());
        }

        if(refreshIcon){
            refreshIcon.classList.remove('ri-spin');
        }
    }, 500);
}

document.addEventListener('DOMContentLoaded', function(){

    loadAttendanceChart();

    var refreshBtn = document.getElementById('refreshAttendanceBtn');

    if(refreshBtn){
        refreshBtn.addEventListener('click', function(){
            refreshAttendanceChart();
        });
    }

    var yearSelect = document.getElementById('yearSelect');

    if(yearSelect){
        yearSelect.addEventListener('change', function(){
            refreshAttendanceChart();
        });
    }

});