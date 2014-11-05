yAxisNumberFormat = d3.format(",");
xAxisDateFormat = "%e %b";

function getMainChartData(data) {
	rv = [
		['country', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
		['Guinea', 648, 812, 862, 861, 936, 942, 1008, 1022, 1074, 1157, 1199, 1298, 1350, 1472, 1519, 1540, 1553, 1906, 1667], 
		['Liberia', 1378, 1871, 2046, 2081, 2407, 2710, 3022, 3280, 3458, 3696, 3834, 3924, 4076, 4249, 4262, 4665, 4665, 6535, 6535],
		['SierraLeone', 1026, 1261, 1361, 1424, 1620, 1673, 1813, 1940, 2021, 2304, 2437, 2789, 2950, 3252, 3410, 3706, 3896, 5235, 5338],
		['Nigeria', 19, 22, 21, 21, 21, 21, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20],
		['Senegal', null, 1, 3, 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
		['Spain', null, null, null, null, null, null, null, null, null, null, null, null, 1, 1, 1, 1, 1, 1, 1],
		['USA', null, null, null, null, null, null, null, null, null, null, 1, 1, 1, 2, 3, 3, 4, 4, 4],
		['Mali', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 1, 1, 1]
	];
	totalsArray = getTotals(rv);
	//newCasesArray = getNewCases(totalsArray);
	rv[rv.length] = totalsArray;
	//rv[rv.length] = newCasesArray;
	return rv;
};

function getNewCases(data){
	newCasesArray = new Array(); // Add a new row to the data for the new cases
	newCasesArray[newCasesArray.length] = "New Cases";
	newCasesArray[1] = null //the first column will always have null new cases
	prevCases = data[1];
	for (i=2; i<data.length; i++){
		newCasesArray[newCasesArray.length] = data[i] - prevCases;
		prevCases = data[i];
	};
	return newCasesArray;
};

function getTotals(data){
	totalsArray = new Array(); 
	totalsArray[totalsArray.length] = "Total";
	numberOfColumns = data[0].length;
	for (i = 1; i < numberOfColumns; i++) {
		totalsArray[i] = 0;
	};
	for (row = 1; row < data.length; row++) {
		for (col = 1; col < numberOfColumns; col++) {
			if ($.isNumeric(data[row][col])) {
				totalsArray[col] += data[row][col];
			};
		};
	};
	return totalsArray;
};

function getCountryChartData(data, country) {
	switch(country) {
		case "Guinea":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', 482, 604, 664, 678, 743, 750, 818, 832, 876, 950, 977, 1044, 1097, 1184, 1217, 1289, 1312, 1391, 1409], 
				['probable', 141, 152, 151, 151, 162, 162, 162, 162, 162, 170, 177, 180, 180, 190, 191, 193, 194, 199, 204], 
				['suspected', 25, 56, 47, 32, 31, 30, 28, 28, 36, 37, 45, 74, 73, 98, 111, 58, 47, 316, 54]
			];
			break;
		case "Liberia":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', 322, 614, 634, 654, 790, 812, 863, 890, 914, 927, 931, 941, 943, 950, 965, 965, 2515, 2515],
				['probable', 674, 888, 969, 974, 1078, 1233, 1342, 1469, 1539, 1656, 1713, 1795, 1874, 1923, 2106, 2106, 1540, 1540],
				['suspected', 382, 369, 443, 453, 539, 675, 817, 921, 1005, 1113, 1190, 1188, 1259, 1376, 1594, 1594, 2480, 2480]
			];
			break;
		case "SierraLeone":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', 935, 1146, 1234, 1287, 1464, 1513, 1640, 1745, 1816, 2076, 2179, 2455, 2593, 2849, 2977, 3223, 3389, 3700, 3778],
				['probable', 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 37, 322, 322],
				['suspected', 54, 78, 90, 100, 119, 123, 136, 158, 168, 191, 221, 297, 320, 366, 396, 446, 470, 1213, 1238]
			];
			break;
		case "Nigeria":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', 15, 18, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19, 19], 
				['probable', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['suspected', 3, 3, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
			];
			break;
		case "Senegal":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', null, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 
				['probable', null, null, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], 
				['suspected', null, null, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0] 			
			];
			break;
		case "Spain":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', null, null, null, null, null, null, null, null, null, null, null, null, 1, 1, 1, 1, 1, 1, 1], 
				['probable', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0, 0], 
				['suspected', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0, 0] 			
			];
			break;	
		case "USA":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', null, null, null, null, null, null, null, null, null, null, 1, 1, 1, 2, 3, 3, 4, 4, 4], 
				['probable', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0, 0], 
				['suspected', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0, 0] 			
			];
			break;		
		case "Mali":
			return [
				['casedef', '2014-08-29', '2014-09-05', '2014-09-08', '2014-09-12', '2014-09-16', '2014-09-18', '2014-09-22', '2014-09-24', '2014-09-26', '2014-10-01', '2014-10-03', '2014-10-08', '2014-10-10', '2014-10-15', '2014-10-17', '2014-10-22', '2014-10-25', '2014-10-29', '2014-10-31'], 
				['confirmed', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 1, 1, 1], 
				['probable',  null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0], 
				['suspected', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, 0] 			
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
				Mali: "area-spline",
				Nigeria: "area-spline",
				SierraLeone: "area-spline",
				Senegal: "area-spline",
				USA: "area-spline",
				Spain: "area-spline",
				Total: "spline"
			},
			groups: [["Guinea", "Liberia", "Mali", "Nigeria", "SierraLeone", "Senegal", "Spain", "USA"]]
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: xAxisDateFormat,
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
				tick: {
					format: yAxisNumberFormat
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
		color: {
			pattern: ['#dd1c77', '#756bb1', '#e41a1c']
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
					format: xAxisDateFormat,
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
				show: true,
				max: 7000,
				tick: {
					format: yAxisNumberFormat,
					culling: {
						max: 4
					}
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
var spainChart = generateCountryChart("#spainChartArea", null, "Spain");
var maliChart = generateCountryChart("#maliChartArea", null, "Mali");


//Update the as of date for all elements with the as-at-date-place-holder class
var asOfDatePlaceHolderElements = document.getElementsByClassName("as-at-date-place-holder");
for (i = 0; i < asOfDatePlaceHolderElements.length; i++) {
	asOfDatePlaceHolderElements[i].innerHTML = "as of 31 October 2014";
};
