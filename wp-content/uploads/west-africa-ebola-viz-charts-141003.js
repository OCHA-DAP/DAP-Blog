function getMainChartData(data) {
	return [
		['country', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
		['Guinea', 648, 812, 862, 861, 936, 942, 1008, 1022, 1074, 1157, 1199], 
		['Liberia', 1378, 1871, 2046, 2081, 2407, 2710, 3022, 3280, 3458, 3696, 3834],
		['SierraLeone', 1026, 1261, 1361, 1424, 1620, 1673, 1813, 1940, 2021, 2304, 2437],
		['Nigeria', 19, 22, 21, 21, 21, 21, 20, 20, 20, 20, 20],
		['Senegal', null, 1, 3, 3, 1, 1, 1, 1, 1, 1, 1],
		['USA', null, null, null, null, null, null, null, null, null, null, 1]
	];
};

function getCountryChartData(data, country) {
	switch(country) {
		case "Guinea":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', 482, 604, 664, 678, 743, 750, 818, 832, 876, 950, 977], 
				['probable', 141, 152, 151, 151, 162, 162, 162, 162, 162, 170, 177], 
				['suspected', 25, 56, 47, 32, 31, 30, 28, 28, 36, 37, 45]
			];
			break;
		case "Liberia":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', 322, 614, 634, 654, 790, 812, 863, 890, 914, 927, 931],
				['probable', 674, 888, 969, 974, 1078, 1233, 1342, 1469, 1539, 1656, 1713],
				['suspected', 382, 369, 443, 453, 539, 675, 817, 921, 1005, 1113, 1190]
			];
			break;
		case "SierraLeone":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', 935, 1146, 1234, 1287, 1464, 1513, 1640, 1745, 1816, 2076, 2179],
				['probable', 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37],
				['suspected', 54, 78, 90, 100, 119, 123, 136, 158, 168, 191, 221]
			];
			break;
		case "Nigeria":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', 15, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19], 
				['probable', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['suspected', 3, 3, 1, 1, 1, 1, 0, 0, 0, 0, 0]
			];
			break;
		case "Senegal":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['probable', null, null, 0, 0, 0, 0, 0, 0, 0, 0, 0], 
				['suspected', null, null, 2, 2, 0, 0, 0, 0, 0, 0, 0] 			
			];
			break;
		case "USA":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03'], 
				['confirmed', null, null, null, null, null, null, null, null, null, null, 1], 
				['probable', null, null, null, null, null, null, null, null, null, null, null], 
				['suspected', null, null, null, null, null, null, null, null, null, null, null] 			
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
				Senegal: "area-spline",
				USA: "area-spline"
			},
			groups: [["Guinea", "Liberia", "Nigeria", "SierraLeone", "Senegal", "USA"]]
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: '%b %d',
					culling: {
						max: 100
					}
				},
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
					format: '%b %d',
					culling: {
						max: 100
					}
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
var usaChart = generateCountryChart("#usaChartArea", null, "USA");

//Calculate the total number of cases. The main chart data is a 2 dimensional
//array. Each array contains the time series data for a country. The total cases
//is calculated by adding the last elements of each array.
var mainChartData = getMainChartData();
var totalNumberOfCases = 0;
var previousTotalNumberOfCases = 0;
var currentReportingDate = mainChartData[0][mainChartData[0].length-1];
var previousReportingDate = mainChartData[0][mainChartData[0].length-2];

for (i = 1; i < mainChartData.length; i++) {
	totalNumberOfCases += mainChartData[i][mainChartData[i].length - 1];
	previousTotalNumberOfCases += mainChartData[i][mainChartData[i].length - 2];
};

document.getElementById("totalNumberOfCases").innerHTML = totalNumberOfCases;
document.getElementById("increaseInTotalNumberOfCases").innerHTML = (totalNumberOfCases - previousTotalNumberOfCases).toString();

//Update the as of date for all elements with the as-at-date-place-holder class
var asOfDatePlaceHolderElements = document.getElementsByClassName("as-at-date-place-holder");
for (i = 0; i < asOfDatePlaceHolderElements.length; i++) {
	asOfDatePlaceHolderElements[i].innerHTML = "as of 3 October 2014";
};
