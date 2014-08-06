<div class="container" id="chart"></div>
<script>
$(function () { 
    $('#chart').highcharts('StockChart', {
        chart: {
            type: 'line',
            zoomType: 'xy',
            renderTo: chart
        },
        title: {
            text: 'Nest Temperature Statistics'
        },
        credits: {
        	enabled: false
        },
        xAxis: {
            type: 'datetime'
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
        yAxis: {
            title: {
                text: 'Value'
            }
        },
        plotOptions: {
            line: {
                marker: {
                    enabled: false
                }
            }
        },

        series: [{
            name: 'Temperature',
            data: [<?php echo join($data_temp, ','); ?>]
        }, {
            name: 'Humidity',
            data: [<?php echo join($data_humidity, ','); ?>]
        }, {
        	name: 'Setpoint',
        	data: [<?php echo join($data_setpoint, ','); ?>]
        }],
        legend: {
			enabled: true,
			borderWidth: 1,
			backgroundColor: '#FFFFFF',
			shadow: true
		}
    });
});
</script>
