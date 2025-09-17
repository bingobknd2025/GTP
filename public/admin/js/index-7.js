/* basic column chart */
var options = {
    series: [{
        name: 'Male',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 55, 70]
    }, {
        name: 'Female',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 60, 65]
    }],
    chart: {
        type: 'bar',
        height: 237
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '80%',
            borderRadius: 8,
            borderRadiusApplication: 'end',
        },
    },
    grid: {
        borderColor: '#949eb7',
    },
    dataLabels: {
        enabled: false
    },
    colors: ["var(--primary-color)", '#5eba00'],
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    legend: {
        show: false
    },
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        labels: {
            show: true,
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    },
    yaxis: {
        title: {
            text: '$ (thousands)',
            style: {
                color: "#8c9097",
            }
        },
        labels: {
            show: true,
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
};
var chart = new ApexCharts(document.querySelector("#countrywise-donors"), options);
chart.render();

  /*chart-donut*/
var options = {
    chart: {
        height: 275,
        type: "radialBar",
    },
	
    colors: ["var(--primary-color)", "#5eba00"],

    plotOptions: {
        
        radialBar: {
            hollow: {
                size: "55%"
              },
            track: {
                background: "rgba(0, 0, 0, 0.05)",
                opacity: 0.5,
            },
            dataLabels: {
                name: {
                    fontSize: "14px",
                },
                value: {
                    fontSize: "16px",
                    color: "#0f4069",
                },
                total: {
                    show: !0,
                    label: "Total Visits",
                    color: "#0f4069",
                    formatter: function(e) {
                        return 249;
                    },
                },
            },
        },
    },

    stroke: {
        lineCap: "round",
    },
    series: [55, 78],
    labels: ["Cardiology", "Dermatology"],
    legend: {
        show: false,
        floating: true,
        fontSize: "13px",
        position: "left",
        offsetX: 0,
        offsetY: 1,
        labels: {
            useSeriesColors: true,
        },
        markers: {
            size: 1,
        },
    },
  };

var chart = new ApexCharts(document.querySelector("#visit-by-departments"), options);

chart.render();

/*chart-donut*/

function index7() { 	
    var options = {
        chart: {
            height: 320,
            type: 'bar',
      toolbar: {
        show: false
      },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '45%',
        borderRadius: 2
            },
        },
        grid: {
            borderColor: '#949eb7'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        legend: {
            tooltipHoverFormatter: function(val, opts) {
            return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
            },
            fontSize: '12px',
            labels: {
                colors: '#74767c',
            },
            markers: {
                width: 7,
                height: 7,
                strokeWidth: 0,
                radius: 12,
                offsetX: 0,
                offsetY: 0
            },
        },
        series: [{
            name: 'In Patient',
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 44, 55]
        }, {
            name: 'Out Patient',
            data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 85, 101]
        }],
        colors: ["var(--primary-color)", '#5eba00'],
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            axisBorder: {
                show: false,
                olor: 'rgba(119, 119, 142, 0.05)',
            },
            axisTicks: {
                show: false,
                olor: 'rgba(119, 119, 142, 0.05)',
            },
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
    }
    document.querySelector("#patient-visit").innerHTML= " ";
    var chart = new ApexCharts(document.querySelector("#patient-visit"), options);
    chart.render();
}
