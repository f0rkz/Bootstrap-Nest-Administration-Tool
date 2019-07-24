$(function () {
    /**
     * Get chart data
     */
    function getData(chart, min, max) {
        chart.showLoading();
        $.getJSON('?cmd=graph_data&start=' + Math.floor(min) + '&end=' + Math.ceil(max) + '&callback=?',
            function (data) {
                for (i = 0; i < 8; i++) {
                    chart.series[i].setData(data[i]);
                }
                chart.hideLoading();
            }
        );
    }

    /**
     * Load the initial zoomed data
     */
    function loadInitialData() {
        var chart = this,
            currentExtremes = chart.xAxis[0].getExtremes();

        getData(chart, currentExtremes.min, currentExtremes.max);
    }

    /**
     * Load new data depending on the selected min and max
     */
    function afterSetExtremes(e) {
        getData(e.target.chart, e.min, e.max);
    }

    Highcharts.setOptions({
        time: {
            timezone: '<?= $timezone; ?>',
            timezoneOffset: 7 * 60
        }
    });
    $('#chart_nest_stats_<?= $device_serial_number ?>').highcharts('StockChart', {
        chart_nest_stats: {
            type: 'line',
            zoomType: 'xy'
        },
        title: {
            text: 'Nest Temp for <?= $device_name ?>'
        },
        credits: {
        	enabled: false
        },
        navigator: {
            adaptToUpdatedData: false
        },
        scrollbar: {
            liveRedraw: false
        },
        chart: {
            panning: true,
            events: {
                load: loadInitialData
            }
        },
        xAxis: {
            type: 'datetime',
            labels: {
				format: '{value:%Y-%b-%e %H:%M}'
			},
            ordinal: false,
            gridLineWidth: 1,
            minTickInterval: 1 * 3600 * 1000,
            minRange: 3600 * 1000, // one hour
            events: {
                afterSetExtremes: afterSetExtremes
            },
        },
        rangeSelector: {
            selected: 3,
            buttons: [{
                type: 'hour',
                count: 1,
                text: '1h'
            }, {
                type: 'hour',
                count: 3,
                text: '3h'
            }, {
                type: 'hour',
                count: 6,
                text: '6h'
            }, {
                type: 'hour',
                count: 12,
                text: '12h'
            }, {
                type: 'day',
                count: 1,
                text: '1d'
            }, {
                type: 'day',
                count: 7,
                text: '1w'
            }, {
                type: 'month',
                count: 1,
                text: '1m'
            }, {
                type: 'ytd',
                text: 'YTD'
            }, {
                type: 'year',
                count: 1,
                text: '1y'
            }, {
                type: 'all',
                text: 'All'
            }],
            selected: 4
	      },
        yAxis: [{
            title: {
                text: 'Temperature °<?= $scale; ?>' 
            },
            labels: {
                formatter: function() {
                return this.value + '°';
            }},
            height: '80%',
            lineWidth: 2
        }, {
       	    title: {
                text: 'Relative Humidity %'
            },
            labels: {
                formatter: function() {
                return this.value + '%';
            }},
	    	    opposite: false,
            height: '80%',
            lineWidth: 2
	      }, {
            title: {
                text: 'Battery'
            },
            labels: {
                formatter: function() {
                return this.value + ' V';
            }},
            floor: 0,
            height: '15%',
            top: '85%',
            offset: 0,
            lineWidth: 2
	      }],
        plotOptions: {
            spline: {
                events: {
                    click: function (event) {
                        this.update({ 
                            dataLabels: { enabled: !this.options.dataLabels.enabled},
                            marker: { symbol: 'diamond', radius: 3, enabled: !this.options.marker.enabled}
                        });
                    },
                },
            },
            line: {
                marker: {
                    enabled: false
                }
            },
            series: {
                connectNulls: false
            }
        },
        series: [{
            name: 'Inside Temperature',
	    	    type: 'spline',
	    	    color: 'orange',
            tooltip: { valueSuffix: '°' },
            dataGrouping: { enabled: false },
            data: [<?php echo join($data_temp, ','); ?>]
        }, {
            name: 'Outside Temperature (<span style="color:blue">below freezing</span>)',
	    	    type: 'spline',
	    	    color: 'red',
            threshold: <?= $freezing_point; ?>,
	    	    negativeColor: 'blue',
            tooltip: { valueSuffix: '°' },
            dataGrouping: { enabled: false },
            events: {
                hide: function () {
                    this.update({
                        name: 'Outside Temperature (below freezing)'
                    });
                },
                show: function () {
                    this.update({
                        name: 'Outside Temperature (<span style="color:blue">below freezing</span>)',
                    });
                }
            },
            data: [<?php echo join($data_outside_temp, ','); ?>]
        }, {
            name: 'Thermostat Setpoint',
	    	    type: 'line',
            step: 'left',
	    	    color: '#50B432',
            tooltip: { valueSuffix: '°' },
            dataGrouping: { enabled: false },
            dataLabels: {align: 'left', enabled: true},
            data: [<?php echo join($data_setpoint, ','); ?>]
        }, {
            name: 'Inside Humidity',
            type: 'spline',
            yAxis: 1,
            tooltip: { valueSuffix: '%' },
            dataGrouping: { enabled: false },
            data: [<?php echo join($data_humidity, ','); ?>]
        }, {
        	 name: 'Outside Humidity',
            type: 'spline',
            color: '#6AF9C4',
            yAxis: 1,
            tooltip: { valueSuffix: '%' },
            dataGrouping: { enabled: false },
        	  data: [<?php echo join($data_outside_humidity, ','); ?>]
        }, {
            name: 'Cooling',
            step: 'left',
            lineWidth: 0,
	    	    type: 'area',
			      threshold: <?= $base_room_temp; ?>,
	    	    fillOpacity: 0.5,
            tooltip: { valueSuffix: '°' },
            dataGrouping: { enabled: false },
	    	    color: '#058DC7',
            data: [<?php echo join($data_cooling, ','); ?>]
        }, {
            name: 'Heating',
            step: 'left',
            lineWidth: 0,
            type: 'area',
            threshold: <?= $base_room_temp; ?>,
            fillOpacity: 0.5,
            tooltip: { valueSuffix: '°' },
            dataGrouping: { enabled: false },
	    	    color: '#FF9655',
            data: [<?php echo join($data_heating, ','); ?>]
        }, {
            name: 'Voltage',
            type: 'spline',
            color: '#ff0000',
            yAxis: 2,
            tooltip: { valueSuffix: ' V' },
            dataGrouping: { enabled: false },
            data: [<?php echo join($data_battery_level, ','); ?>]
        }, {
            // Graph initial extremes to allow for mouse panning
            color: 'rgba(0,0,0,0)',
            enableMouseTracking: false,
            showInLegend: false,
            dataGrouping: { enabled: false },
            data: [
                { x: <?= $min_timestamp ?>, y: 0},
                { x: <?= $max_timestamp ?>, y: 0}
            ]
        }],
        legend: {
            enabled: true,
            borderWidth: 1,
            backgroundColor: '#FFFFFF',
            shadow: true
		    }
    });
});
