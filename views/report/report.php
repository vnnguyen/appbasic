<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <div id="chart_div" style=" height: 500px;"></div>
<script>
	
	      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = new google.visualization.DataTable();
      data.addColumn('string', 'Hotel');
      data.addColumn('number', '2016');
      data.addColumn({type: 'string', role: 'annotation'});
      data.addColumn('number', '2015');
      data.addColumn({type: 'string', role: 'annotation'});

      data.addRows([
        ['hotelfgsdfgsdfg', 5, '5', 2.25,  '2'],
        ['hoteldfsgsdf', 5, '5', 2.25,  '2'],
        ['hotelsdfgsdfg', 5, '5', 2.25,  '2'],
        ['hoteldsfgsdf', 5, '5', 2.25,  '2'],
        ['hotelsdfgsd', 5, '5', 2.25,  '2'],
        ['hoteldfsgdf', 5, '5', 2.25,  '2'],
        ['hoteldfsgfgsdfgfsdg', 5, '5', 2.25,  '2'],
        ['hoteldsfgsdf', 5, '5', 2.25,  '2'],
        ['hotel', 5, '5', 2.25,  '2'],
        ['hotel', 5, '5', 2.25,  '2'],
        ['hotel', 5, '5', 2.25,  '2'],
        ['hotel', 5, '5', 2.25,  '2'],
        ['hotel', 5, '5', 2.25,  '2'],
      ]);
// annotations.datum.stem.length
    var options = {
      title : 'Report Hotels',
      annotations: {
          alwaysOutside: true,
          textStyle: {
            fontSize: 12
          },
          stem: {
          		// length: 25,
          		color: 'none'
          },
          boxStyle: {
            gradient: {
              color1: '#fff',
              color2: '#fff',

              x1: '0%', y1: '0%',
              x2: '100%', y2: '100%'
            }
          }
      },
      legend: {position: 'top'},
      hAxis: {
      	title: 'Hotel',
      	maxAlternation: 1
      },
      vAxis: {
		ticks: [0]
	  },
      seriesType: 'bars',
      // series: {5: {type: 'line'}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>