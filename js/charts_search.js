$(document).ready(function() {			
			//once loaded call draw chart function
			google.setOnLoadCallback(drawChart);

			function drawChart() {
				//for every object with class of chart_new
				$('.chart_new').each(function() {
					//get the id of the object
					var id = $(this).attr('id');
					var jsonData;
					//get the historical prices of the stock the current object holds
					$.ajax({
						type : "POST",
						url : "ajax/graph.php",
						data : {
							stockID : id
						},
						dataType : "json",
						async : false,
						success : function(data) {
							jsonData = data;
						}
					});

					// Create data table out of JSON data loaded from server.
					var data = new google.visualization.DataTable();
					//add columns to the datatable
					data.addColumn('date', 'Date');
					data.addColumn('number', 'Price');
					//for each line of the json file 
					$.each(jsonData.historical, function(i, item) {
						//format the date into a javascript date
						var date = item.Date;
						var dateParts = date.split("-");
						var jsDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
						//format the price into a float
						var price = parseFloat(item.Close);
						var prediction;
						//add the row to the datatable
						data.addRow([jsDate, price]);
					});
					
					var chart = new google.visualization.AnnotationChart(document.getElementById(id));
					//draw chart passing in a few options
					chart.draw(data, {
						'displayAnnotations' : false,
						'displayExactValues' : true,
						'displayRangeSelector' : true,
						'thickness' : 1.5,
						'displayLegendDots' : true,
						'colors' : ['#1a3c75','#E26D26'],
					});
				});
			};
			
			$('.cardinfo').on('click', '.trackLink', function() {
				
				var link = $(this);
				var id = link.attr('stockID');
				
				$.ajax({
						type : "POST",
						url : "ajax/track.php",
						data : {
							stockID : id
						},
						success : function(data) {
							link.removeClass('trackLink');
							link.addClass('trackedLink');
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + '|' + thrownError);
						}
					});
					
				return false;
			});
			
				$('.cardinfo').on('click', '.trackedLink', function() {
				
				var link = $(this);
				var id = link.attr('stockID');
				
				$.ajax({
						type : "POST",
						url : "ajax/unTrack.php",
						data : {
							stockID : id
						},
						success : function(data) {
							link.removeClass('trackedLink');
							link.addClass('trackLink');
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + '|' + thrownError);
						}
					});
					
				return false;
			});
});