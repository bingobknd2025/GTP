var options = {
    series: [44, 55, 17],
    chart: {
        height: 290,
        type: 'donut',
    },
  
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
      },
    plotOptions: {
        pie: {
          donut: {
            size: '75%',
            background: 'transparent',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '20px',
                color:'#495057',
                offsetY: -4
              },
              value: {
                show: true,
                fontSize: '18px',
                color: undefined,
                offsetY: 8,
                formatter: function (val) {
                  return val + "%"
                }
              },
              total: {
                show: true,
                showAlways: true,
                label: 'Total',
                fontSize: '22px',
                fontWeight: 600,
                color: '#495057',
                }
      
            }
          }
        }
    },
  responsive: [{
    breakpoint: 320,
    options: {
      chart: {
        width: 250
      },
      legend: {
        show: false,
        position: 'top'
      }
    }
  },
  {
    breakpoint: 1425,
    options: {
      chart: {
        height: 220
      },
      legend: {
        show: false,
        position: 'top'
      }
    }
  }],
  colors: ["var(--primary-color)", '#5eba00', '#f35e90'],
  
};

  var chart = new ApexCharts(document.querySelector("#viewing-devices"), options);
  chart.render();
/* Chartjs (#total-customers) closed */

function revenueChart2() {
    setTimeout(() => {
        var options = {
            series: [{
                    name: "TEAM A",
                    type: "line",
                    data: [30, 25, 36, 30, 45, 35, 64, 35, 55, 36, 39, 28, 15, 38],
                },
                {
                    name: "TEAM B",
                    type: "bar",
                    data: [44, 55, 41, 67, 22, 43, 25, 41, 56, 27, 43, 30, 25, 45],
                },
            ],
            chart: {
                height: 250,
                stacked: false,
                toolbar: {
                    show: false,
                }
            },

            stroke: {
                width: [2, 1],
                curve: "smooth",
                // dashArray: [8, 0],
            },
            markers: {
                size: [2, 0],
            },
            
            legend: {
                show: true,
				position: 'top',
      			horizontalAlign: 'right',
				fontSize: '10px',
				fontWeight: 600, 
                colors: ["rgb(243, 94, 144)"],
				// labels: {
				// 	colors: '#74767c',
				// },
				markers: {
					width: 7,
					height: 7,
					strokeWidth: 0,
					radius: 12,
					offsetX: 0,
					offsetY: 0
				},
            },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    borderRadius: 4,
                    borderRadiusApplication: 'end',
                },
            },

            colors: ["rgb(34, 192, 60)", "var(--primary-color)"],

            fill: {
                opacity: [1, 1],
                gradient: {
                    inverseColors: false,
                    shade: "light",
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100],
                },
            },

            labels: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "July",
                "Aug",
                "Sep",
                "Oct",
                "Sep",
                "Oct",
                "Nov",
                "dec",
            ],

            grid: {
                show: true,
                borderColor: "#949eb7",
            },
            xaxis: {
                labels: {
                    show: true,
                    style: {
                        color: ["#76839ac9"]
                    }
                },
                axisBorder: {
                    show: false,
                },

                lines: {
                    show: false,
                    color: "#fff",
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                show: true,
                labels: {
                    show: true,
                    style: {
                        color: ["#76839ab0"]
                    }
                },
                axisTicks: {
                    show: false,
                },
                lines: {
                    show: false,
                },
                min: 0,
            },

            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0) + " points";
                        }
                        return y;
                    },
                },
            },
        };

        document.querySelector("#revenue_chart").innerHTML = "";
        var revenue_chart = new ApexCharts(document.querySelector("#revenue_chart"), options);
        revenue_chart.render();
    }, 100);
}