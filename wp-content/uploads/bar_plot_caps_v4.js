var chart = c3.generate({
	bindto: '#barplot',
    padding: {
        bottom: 150
    },
    data: {
    	x: 'country',
        url: 'http://docs.hdx.rwlabs.org/wp-content/uploads/cap_data.csv',
        type: 'bar',
        colors: {
        	n_affected: '#F2645A'
        },
        names: {
        	n_affected: 'Number of People Affected'
        }
    },
    bar: {
        width: {
            ratio: 0.7 // this makes bar width 50% of length between ticks
        }
    },
    axis: {
    	rotated: false,
    	x: {
    		type: 'category',
            tick: {
               rotate: 90
            }
    	},
    	y: {
    		tick: {
    			format: d3.format(",")
    		}
    	}
    },
    legend: {
    	show: false
    }
});