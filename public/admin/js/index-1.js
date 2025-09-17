/* Revenue Analytics Chart */
var options = {
    series: [
        {
            type: 'line',
            name: 'Profit',
            data: [
                {
                    x: 'Jan',
                    y: 45
                },
                {
                    x: 'Feb',
                    y: 52
                },
                {
                    x: 'Mar',
                    y: 38
                },
                {
                    x: 'Apr',
                    y: 24
                },
                {
                    x: 'May',
                    y: 33
                },
                {
                    x: 'Jun',
                    y: 26
                },
                {
                    x: 'Jul',
                    y: 21
                },
                {
                    x: 'Aug',
                    y: 20
                },
                {
                    x: 'Sep',
                    y: 6
                },
                {
                    x: 'Oct',
                    y: 8
                },
                {
                    x: 'Nov',
                    y: 15
                },
                {
                    x: 'Dec',
                    y: 10
                }
            ]
        },
        {
            type: 'line',
            name: 'Revenue',
            chart: {
                dropShadow: {
                    enabled: true,
                    enabledOnSeries: undefined,
                    top: 5,
                    left: 0,
                    blur: 3,
                    color: '#000',
                    opacity: 0.1
                }
            },
            data: [
                {
                    x: 'Jan',
                    y: 35
                },
                {
                    x: 'Feb',
                    y: 41
                },
                {
                    x: 'Mar',
                    y: 62
                },
                {
                    x: 'Apr',
                    y: 42
                },
                {
                    x: 'May',
                    y: 13
                },
                {
                    x: 'Jun',
                    y: 18
                },
                {
                    x: 'Jul',
                    y: 29
                },
                {
                    x: 'Aug',
                    y: 37
                },
                {
                    x: 'Sep',
                    y: 36
                },
                {
                    x: 'Oct',
                    y: 51
                },
                {
                    x: 'Nov',
                    y: 32
                },
                {
                    x: 'Dec',
                    y: 35
                }
            ]
        },
        {
            type: 'line',
            name: 'Sales',
            chart: {
                dropShadow: {
                    enabled: true,
                    enabledOnSeries: undefined,
                    top: 5,
                    left: 0,
                    blur: 3,
                    color: '#000',
                    opacity: 0.1
                }
            },
            data: [
                {
                    x: 'Jan',
                    y: 87
                },
                {
                    x: 'Feb',
                    y: 57
                },
                {
                    x: 'Mar',
                    y: 74
                },
                {
                    x: 'Apr',
                    y: 99
                },
                {
                    x: 'May',
                    y: 75
                },
                {
                    x: 'Jun',
                    y: 38
                },
                {
                    x: 'Jul',
                    y: 62
                },
                {
                    x: 'Aug',
                    y: 47
                },
                {
                    x: 'Sep',
                    y: 82
                },
                {
                    x: 'Oct',
                    y: 56
                },
                {
                    x: 'Nov',
                    y: 45
                },
                {
                    x: 'Dec',
                    y: 47
                }
            ]
        }
    ],
    chart: {
        height: 310,
        animations: {
            speed: 500
        },
        toolbar: {
            show: false,
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 8,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        },
    },
    colors: ["rgb(132, 90, 223)", '#5eba00', '#ffc107'],
    dataLabels: {
        enabled: false
    },
    grid: {
        borderColor: '#949eb7',
        strokeDashArray: 3
    },
    stroke: {
        curve: 'smooth',
        width: [2, 2, 2],
        dashArray: [0, 6, 5],
    },
    legend: {
        tooltipHoverFormatter: function (val, opts) {
            return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
        }
    },
    xaxis: {
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                return "$" + value;
            }
        },
    },
    tooltip: {
        y: [{
            formatter: function(e) {
                return void 0 !== e ? "$" + e.toFixed(0) : e
            }
        }, {
            formatter: function(e) {
                return void 0 !== e ? "$" + e.toFixed(0) : e
            }
        }, {
            formatter: function(e) {
                return void 0 !== e ? e.toFixed(0) : e
            }
        }]
    },
    // legend: {
    //     show: true,
    //     customLegendItems: ['Profit', 'Revenue', 'Sales'],
    //     inverseOrder: true
    // },
    
    markers: {
        hover: {
            sizeOffset: 5
        }
    }
};
document.getElementById('revenue-analytics').innerHTML = '';
var chart = new ApexCharts(document.querySelector("#revenue-analytics"), options);
chart.render();

function revenueAnalytics() {
    chart.updateOptions({
        colors: ["rgba(" + myVarVal + ", 1)",'#5eba00', '#ffc107'],
    });
}
/* Revenue Analytics Chart */

    // Users by country map
    var markers = [{
        name: 'Russia',
        coords: [61, 105],
        style: {
            fill: '#28d193'
        }
    },
    {
        name: 'Geenland',
        coords: [72, -42],
        style: {
            fill: '#ff8c33'
        }
    },
    {
        name: 'Canada',
        coords: [56, -106],
        style: {
            fill: '#ff534d'
        }
    },
    {
        name: 'Palestine',
        coords: [31.5, 34.8],
        style: {
            fill: '#ffbe14'
        }
    },
    {
        name: 'Brazil',
        coords: [-14.2350, -51.9253],
        style: {
            fill: '#4b9bfa'
        }
    },
    ];
    var map = new jsVectorMap({
        map: 'world_merc',
        selector: '#country-map',
        markersSelectable: true,

        onMarkerSelected(index, isSelected, selectedMarkers) {
            console.log(index, isSelected, selectedMarkers);
        },

        // -------- Labels --------
        labels: {
            markers: {
                render: function (marker) {
                    return marker.name
                },
            },
        },

        // -------- Marker and label style --------
        markers: markers,
        markerStyle: {
            hover: {
                stroke: "#DDD",
                strokeWidth: 3,
                fill: '#FFF'
            },
            selected: {
                fill: '#ff525d'
            }
        },
        markerLabelStyle: {
            initial: {
                fontFamily: 'Poppins',
                fontSize: 13,
                fontWeight: 500,
                fill: '#35373e',
            },
        },
    })