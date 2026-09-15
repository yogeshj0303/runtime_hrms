document.addEventListener("DOMContentLoaded", function () {
    const chartCanvas = document.getElementById("hiringTrendChart");

    if (!chartCanvas) {
        return;
    }

    new Chart(chartCanvas, {
        type: "line",

        data: {
            labels: [
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                "Jan", "Feb", "Mar", "Apr", "May", "Jun"
            ],

            datasets: [
                {
                    label: "Hires",
                    data: [2, 4, 3, 6, 5, 8, 7, 10, 9, 12, 11, 15],
                    borderColor: "#3478f6",
                    backgroundColor: "rgba(52,120,246,0.08)",
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: "#3478f6",
                    pointBorderColor: "#3478f6"
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: "index"
            },

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    enabled: true,
                    backgroundColor: "#111827",
                    titleFont: {
                        size: 11
                    },
                    bodyFont: {
                        size: 11
                    },
                    padding: 8,
                    displayColors: false
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: "#8b94a3",
                        font: {
                            size: 10
                        }
                    },
                    border: {
                        display: false
                    }
                },

                y: {
                    beginAtZero: true,
                    grid: {
                        color: "#eef1f5"
                    },
                    ticks: {
                        stepSize: 5,
                        color: "#8b94a3",
                        font: {
                            size: 10
                        }
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });
});