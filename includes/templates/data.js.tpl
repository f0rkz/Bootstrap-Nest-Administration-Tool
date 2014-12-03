$(function () { 
    Highcharts.setOptions({
        global: {
            timezoneOffset: <?= $date_offset; ?> * 60
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
        xAxis: {
            type: 'datetime',
            ordinal: false,
            gridLineWidth: 1,
            minTickInterval: 1 * 3600 * 1000,
        },
	    rangeSelector: {
	    	selected: 1,
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
	    },
        yAxis:[{
            title: {
                text: 'Temperature °<?= $scale; ?>' 
            },
            labels: {
                formatter: function() {
                return this.value + '°';
            }}

        }, {
       	    title: {
                text: 'Relative Humidity %'
            },
            labels: {
                formatter: function() {
                return this.value + '%';
            }},
	    	opposite: false
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
            data: [<?php echo join($data_temp, ','); ?>]
        }, {
            name: 'Outside Temperature (<span style="color:blue">below freezing</span>)',
	    	type: 'spline',
	    	color: 'red',
            threshold: <?= $freezing_point; ?>,
	    	negativeColor: 'blue',
            tooltip: { valueSuffix: '°' },
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
            dataLabels: {align: 'left', enabled: true},
            data: [<?php echo join($data_setpoint, ','); ?>]
        }, {
            name: 'Inside Humidity',
	    	type: 'spline',
	    	yAxis: 1,
            tooltip: { valueSuffix: '%' },
            data: [<?php echo join($data_humidity, ','); ?>]
        }, {
        	name: 'Outside Humidity',
	    	type: 'spline',
	    	color: '#6AF9C4',
	    	yAxis: 1,
            tooltip: { valueSuffix: '%' },
        	data: [<?php echo join($data_outside_humidity, ','); ?>]
        }, {
            name: 'Cooling',
            step: 'left',
            lineWidth: 0,
	    	type: 'area',
			threshold: <?= $base_room_temp; ?>,
	    	fillOpacity: 0.5,
            tooltip: { valueSuffix: '°' },
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
	    	color: '#FF9655',
            data: [<?php echo join($data_heating, ','); ?>]
        }],
        legend: {
			enabled: true,
			borderWidth: 1,
			backgroundColor: '#FFFFFF',
			shadow: true
		}
    });
});
