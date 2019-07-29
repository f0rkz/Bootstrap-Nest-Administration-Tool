<div class="container" id="chart_unit_stats"></div>
<script>
$(function () { 
    Highcharts.setOptions({
		time: {
            timezone: '<?= $timezone; ?>',
            timezoneOffset: '<?= $time_offset; ?>' * 60
        }
    });
    $('#chart_unit_stats').highcharts('StockChart', {
        chart_unit_stats: {
            type: 'line',
            zoomType: 'xy',
            renderTo: chart_unit_stats
        },
        title: {
            text: 'Unit Runtime Statistics'
        },
        credits: {
        	enabled: false
        },
        xAxis: {
            type: 'datetime',
			labels: {
				format: '{value:%Y-%b-%e %H:%M}'
			},
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
                text: 'Status'
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
            name: 'Cooling',
            data: [<?php echo join($data_cooling, ','); ?>]
        }, {
            name: 'Heating',
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
</script>
