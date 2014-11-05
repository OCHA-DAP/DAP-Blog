function getMainChartData(data) {
	return [
		['country', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
		['Guinea', 648, 812, 862, 861, 936, 942, 1008, 1022, 1074, 1157], 
		['Liberia', 1378, 1871, 2046, 2081, 2407, 2710, 3022, 3280, 3458, 3696],
		['SierraLeone', 1026, 1261, 1361, 1424, 1620, 1673, 1813, 1940, 2021, 2304],
		['Nigeria', 19, 22, 21, 21, 21, 21, 20, 20, 20, 20],
		['Senegal', null, 1, 3, 3, 1, 1, 1, 1, 1, 1]
	];
};

function getCountryChartData(data, country) {
	switch(country) {
		case "Guinea":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
				['confirmed', 482, 604, 664, 678, 743, 750, 818, 832, 876, 950], 
				['probable', 141, 152, 151, 151, 162, 162, 162, 162, 162, 170], 
				['suspected', 25, 56, 47, 32, 31, 30, 28, 28, 36, 37]
			];
			break;
		case "Liberia":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
				['confirmed', 322, 614, 634, 654, 790, 812, 863, 890, 914, 927],
				['probable', 674, 888, 969, 974, 1078, 1233, 1342, 1469, 1539, 1656],
				['suspected', 382, 369, 443, 453, 539, 675, 817, 921, 1005, 1113]
			];
			break;
		case "SierraLeone":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
				['confirmed', 935, 1146, 1234, 1287, 1464, 1513, 1640, 1745, 1816, 2076],
				['probable', 37, 37, 37, 37, 37, 37, 37, 37, 37, 37],
				['suspected', 54, 78, 90, 100, 119, 123, 136, 158, 168, 191]
			];
			break;
		case "Nigeria":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
				['confirmed', 15, 18, 19, 19, 19, 19, 19, 19, 19, 19], 
				['probable', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['suspected', 3, 3, 1, 1, 1, 1, 0, 0, 0, 0]
			];
			break;
		case "Senegal":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-1'], 
				['confirmed', null, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['probable', null, null, 0, 0, 0, 0, 0, 0, 0, 0], 
				['suspected', null, null, 2, 2, 0, 0, 0, 0, 0, 0] 			
			];
			break;
	};
};

function generateMainChart(bindTo,data) {
	return c3.generate({
		bindto: bindTo,
		data: {
			x: "country",
			columns: getMainChartData(),
			types: {
				Guinea: "area-spline",
				Liberia: "area-spline",
				Nigeria: "area-spline",
				SierraLeone: "area-spline",
				Senegal: "area-spline"
			},
			groups: [["Guinea", "Liberia", "Nigeria", "SierraLeone", "Senegal"]]
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {format: '%b %d'},
				label: {
					text: "Report date",
					position: "outer-center"
				}
			},
			y: {
				label: {
					text: "Number of cases",
					position: "outer-center"
				}
			}
		},
		size: {
			height: 300
		},
		padding: {
			right: 20
		}		
	});
};

function generateCountryChart(bindTo, data, country) {
	return c3.generate({
		bindto: bindTo,
		data: {
			x: "casedef",
			columns: getCountryChartData(data, country),
			types: {
				confirmed: 'area-spline',
				probable: 'area-spline',
				suspected: 'area-spline'
			},
			groups: [['confirmed','probable','suspected']]
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: '%b %d'
				},
				label: {
					text: "Report date",
					position: "outer-center"
				}
			},
			y: {
				max: 3500,
				label: {
					text: "Number of cases",
					position: "outer-center"
				}
			}
		},
		size: {
			height: 200
		},
		padding: {
			right: 20
		}
	});
};

var mainChart = generateMainChart("#mainChartArea");
var liberiaChart = generateCountryChart("#liberiaChartArea", null, "Liberia");
var guineaChart = generateCountryChart("#guineaChartArea", null, "Guinea");
var sierraLeoneChart = generateCountryChart("#sierraLeoneChartArea", null, "SierraLeone");
var nigeriaChart = generateCountryChart("#nigeriaChartArea", null, "Nigeria");
var senegalChart = generateCountryChart("#senegalChartArea", null, "Senegal");