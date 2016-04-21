//load google visualization package
			google.load("visualization", "1", {
				packages : ["controls", "annotationchart"]
			});
			//once loaded call draw chart function
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var jsonData;
				//get search data
				$.ajax({
					url : "ajax/graph.php",
					dataType : "json",
					async : false,
					success : function(data) {
						jsonData = data;
					}
				});

				// Create data table out of JSON data loaded from server.
				var data = new google.visualization.DataTable();

				//add columns to the data table
				data.addColumn('string', 'Keyword');
				data.addColumn('number', 'Searches');
				data.addColumn({
					type : 'string',
					role : 'annotation'
				});
				//for each line of the json file
				$.each(jsonData.searchData, function(i, item) {
					//add rows to the data table
					data.addRow([item.Keyword, parseInt(item.Count), item.Keyword]);
				});

				// Create data table out of JSON data loaded from server.
				var data2 = new google.visualization.DataTable();

				//add columns to the data table
				data2.addColumn('date', 'Date');
				data2.addColumn('number', 'Session Duration', {f:'3.22 Minutes'});

				//for each line of the json file
				$.each(jsonData.predictionTimes, function(i, item) {
					//format the date into a javascript date
					var date = item.Date;
					var dateParts = date.split("-");
					var jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
					//format the price into a float
					var minutes = parseFloat(item.Time)/60;
					//add the row to the datatable
					data2.addRow([jsDate, minutes]);
				});
				
				// Create a dashboard.
				var dashboard = new google.visualization.Dashboard(document.getElementById('dashboard'));
				// Create a range slider, passing some options
				var donutRangeSlider = new google.visualization.ControlWrapper({
					'controlType' : 'NumberRangeFilter',
					'containerId' : 'filter',
					'options' : {
						'filterColumnLabel' : 'Searches',
						ui : {
							label : 'Filter Count:',
							cssClass : 'filter'
						}
					}
				});
				//create chart passing some options
				var chart = new google.visualization.ChartWrapper({
					'chartType' : 'BarChart',
					'containerId' : 'graph_search',
					'options' : {
						orientation : 'horizontal',
						chartArea : {
							width : '100%',
							height : '100%',
							top : 0,
							left : 20,
						},
						bar : {
							groupWidth : '40%'
						},
						vAxis : {
							textPosition : 'out',
							baselineColor : '#cccccc',
						},
						hAxis : {
							textPosition : 'none',
							baselineColor : '#cccccc',
						},
						fontSize : '9px',
						fontName : 'roboto',
						legend : {
							position : 'none'
						},
						colors : ['#1a3c75'],
					}
				});
			
				// Instantiate and draw our chart, passing in some options.
					var chart2 = new google.visualization.AnnotationChart(document.getElementById('graph_prediction'));
					//draw chart passing in a few options
					chart2.draw(data2, {
						'displayAnnotations' : false,
						'displayRangeSelector' : true,
						'thickness' : 1.5,
						'scaleFormat': '0.00',
						'displayLegendDots' : true,
						'numberFormats':'0.00 minutes',
						'colors' : ['#1a3c75','#E26D26'],
					});
				//draw the chart
				dashboard.bind(donutRangeSlider, chart);
				dashboard.draw(data);
			}