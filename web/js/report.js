var data_source_chart;
var y = '';
var months = [
    'January', 'February', 'March', 'April', 'May',
    'June', 'July', 'August', 'September',
    'October', 'November', 'December'
];
getDataChart();
google.charts.load('current', {'packages':['bar']});

// Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //

function getDataChart(){
	$.ajax({
	    method: "GET",
	    url: "/appbasic/web/report/get_data",
	    dataType: 'json'
	})
	.done(function(result) {
	    if (result != null) {
	    	y = result.cts[0].y;
	    	var arr_data = [];
	    	for (var i = 0; i <= 12; i++) {
	    		arr_data[i] = [];
	    		var status = false;
	    		jQuery.each(result.cts, function(index, item){
	    			if (item.m == i) {
	    				arr_data[i][0] = monthNumToName(i);
	    				arr_data[i][1] = parseInt(item.cnt);
	    				status = true;
	    			}
			    });
			    if (!status) {
			    	arr_data[i][0] = monthNumToName(i);
			    	arr_data[i][1] = 0;
			    }
			    status = false;
	    		jQuery.each(result.cases, function(index, item){
	    			if (item.m == i) {
	    				arr_data[i][2] = item.cnt;
	    				status = true;
	    			}
			    });
			    if (!status) {
			    	arr_data[i][2] = 0;
			    }
	    	}
	    	arr_data[0] = ['Month', 'Confirmed', 'Pending'];
			loadGoogle(arr_data);
	    }
	})
	.fail(function() {
	    alert( "Error" );
	});
}
function loadGoogle($data){
	data_source_chart = $data;
	google.charts.setOnLoadCallback(drawChart);
	$(window).resize(function(){
		drawChart();
	});
}
function drawChart() {
	var data = google.visualization.arrayToDataTable(data_source_chart);

	var options = {
		chart: {
			title: 'Report in '+ y,
			// subtitle: 'Sales, Expenses, and Profit: 2014-2017',
		},
		bars: 'vertical',
		height: 400,
		colors: ['#1b9e77', '#d95f02'],
		isStacked: true
	};

	var chart = new google.charts.Bar(document.getElementById('chart_div'));

	chart.draw(data, google.charts.Bar.convertOptions(options));
}

function monthNumToName(monthnum) {
    return months[monthnum - 1] || '';
}
function monthNameToNum(monthname) {
    var month = months.indexOf(monthname);
    return month ? month + 1 : 0;
}
///////////////////////////////////////////////////////////
/* old lib
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart1);
function drawChart1() {
  var data = google.visualization.arrayToDataTable([
    ['Year', 'Sales', 'Expenses'],
    ['2004',  1000,      400],
    ['2005',  1170,      460],
    ['2006',  660,       1120],
    ['2007',  1030,      540]
  ]);

  var options = {
    title: 'Company Performance',
    hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
 };

var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
  chart.draw(data, options);
}

$(window).resize(function(){
  drawChart1();
});

// Reminder: you need to put https://www.google.com/jsapi in the head of your document or as an external resource on codepen //

///////////////////////////////////////////////////////////////////////////
*/


















