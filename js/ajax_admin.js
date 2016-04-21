$(document).ready(function() {

	$.ajaxSetup({
		cache : true
	});

	$.ajax({
		url : 'ajax/getTimes.php',
		dataType : "json",
		success : function(data) {

			var updateMin = data.update[0];
			var updateHour = data.update[1];
			var updateDay = data.update[2];

			var predictMin = data.predict[0];
			var predictHour = data.predict[1];
			var predictDay = data.predict[2];

			if (updateDay != '*') {
				$('#updateTimerSelect').val('Days');
				$('#updateTime').val(updateDay);

				if (updateHour >= 12) {
					$('#updateAmPm').val('pm');
				}

				if (updateHour >= 13) {
					updateHour = updateHour - 12;
				}

				if (updateMin < 10) {
					updateMin = '0' + updateMin;
				}

				$('#updateDayTime').val(updateHour + ':' + updateMin);
				$('#updateDay').show();

			} else if (updateHour != '*') {
				$('#updateTimerSelect').val('Hours');
				$('#updateTime').val(updateHour);
			} else if (updateMin != '*') {
				$('#updateTimerSelect').val('Minutes');
				$('#updateTime').val(updateMin);
			} else {
				$('#updateTimerSelect').val('Minutes');
				$('#updateTime').val('');
			}

			if (predictDay != '*') {
				$('#predictTimerSelect').val('Days');
				$('#predictTime').val(predictDay);

				if (predictHour >= 12) {
					$('#predictAmPm').val('pm');
				} else {
					$('#predictAmPm').val('am');
				}

				if (predictHour >= 13) {
					predictHour = predictHour - 12;
				}

				if (predictMin < 10) {
					predictMin = '0' + predictMin;
				}

				$('#predictDayTime').val(predictHour + ':' + predictMin);
				$('#predictDay').show();

			} else if (updateHour != '*') {
				$('#predictTimerSelect').val('Hours');
				$('#predictTime').val(predictHour);
			} else if (updateMin != '*') {
				$('#predictTimerSelect').val('Minutes');
				$('#predictTime').val(predictMin);
			} else {
				$('#predictTimerSelect').val('Minutes');
				$('#predictTime').val('');
			}
		},

		error : function(xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		},
	});

	$('#button_addstock').click(function() {
		var postdata = $('#input_addstock').val();
		if (postdata) {
			$.ajax({
				type : 'POST',
				url : 'ajax/addstock.php',
				data : {
					input_addstock : postdata
				},
				dataType : "json",
				beforeSend : function() {
					// this is where we append a loading image
					$('#addstock').append('<div class="loading"><img src="images/adding.gif"></img></div>');
				},

				success : function(data) {
					var content = ' ';

					$('#message_addstock').empty();

					if (data.exists.length == 0 && data.errors == 0) {
						$('#message_addstock').css({
							'font-weight' : 400,
							'color' : 'green'
						});
						$('#message_addstock').html('<p>' + postdata + ' successfully added.</p>');
						$('#stockdata').html(data.table);
					} else {
						if (data.exists.length != 0) {
							content = '<p>Error! The following stocks already exist:';
							$.each(data.exists, function() {
								content += this + ' ';
							});
							content += '</p>';
						}
						if (data.errors.length != 0) {
							content += '<p>Error! The following stocks could not be found: ';
							$.each(data.errors, function() {
								content += this + ' ';
							});

							content += '</p>';
						}
						$('#message_addstock').css({
							'font-weight' : 400,
							'color' : 'red'
						});
						$('#message_addstock').html(content);
						$('#stockdata').html(data.table);
					}
				},

				error : function(xhr, ajaxOptions, thrownError) {
					$('#message_addstock').css({
						'font-weight' : 300,
						'color' : 'red'
					});
					$('#message').html('<p>Error! Please make sure there are spaces after every comma</p>');
				},
				complete : function() {
					$('#addstock .loading').remove();
				},
			});
		}
	});

	$('#button_timers').click(function() {
		var updateMin = null;
		var updateHour = null;
		var updateDay = null;

		var predictMin = null;
		var predictHour = null;
		var predictDay = null;
		
		var error = '';

		function checkMinutes(minutes) {
		if (minutes < 1 || minutes > 59) {
				$('#message_timer').css({
					'font-weight' : 400,
					'color' : '#E62A2A'
				});
				error += '<p>Error: Minute interval should be between 1 and 59</p>';
			}
		}
		
		function checkHours(hours) {
		if (hours < 1 || hours > 23) {
				$('#message_timer').css({
					'font-weight' : 400,
					'color' : '#E62A2A'
				});
				error += '<p>Error: Hour interval should be between 1 and 23</p>';
			}
		}
		
		function checkTime(minutes, hours) {
		if (minutes < 0 || minutes > 59 || hours < 1 || hours > 12) {
				$('#message_timer').css({
					'font-weight' : 400,
					'color' : '#E62A2A'
				});
				error += '<p>Error: Invalid time - the hour value should be between 1 and 12 and the minutes should be between 0 and 59</p>';
			}
		}
		
		if ($('#updateTimerSelect').val() == 'Minutes') {
			updateMin = $('#updateTime').val();
			checkMinutes(updateMin);
			
		} else if ($('#updateTimerSelect').val() == 'Hours') {
			updateHour = $('#updateTime').val();
			checkHours(updateHour);
			
		} else {
			updateDay = $('#updateTime').val();
			var time = $('#updateDayTime').val().split(':');
			updateHour = parseInt(time[0], 10);
			updateMin = parseInt(time[1], 10);
			checkTime(updateMin, updateHour);
			
			if (updateHour < 12 && $('#predictAmPm').val() == 'pm') updateHour = updateHour+12;
		}

		if ($('#predictTimerSelect').val() == 'Minutes') {
			predictMin = $('#predictTime').val();
			checkMinutes(predictMin);

		} else if ($('#predictTimerSelect').val() == 'Hours') {
			predictHour = $('#predictTime').val();
			checkHours(predictHour);

		} else {
			predictDay = $('#predictTime').val();
			var time = $('#predictDayTime').val().split(':');
			predictHour = parseInt(time[0], 10);
			predictMin = parseInt(time[1], 10);
			checkTime(predictMin, predictHour);
			
			if (predictHour < 12 && $('#predictAmPm').val() == 'pm') predictHour = predictHour+12;

		}
		
		if (error) {
			$('#message_timer').html(error);
			return;
		}

		$.ajax({
			type : 'POST',
			url : 'ajax/editTimes.php',
			data : {
				updateMin : updateMin,
				updateHour : updateHour,
				updateDay : updateDay,
				predictMin : predictMin,
				predictHour : predictHour,
				predictDay : predictDay
			},

			success : function() {
				$('#message_timer').css({
					'font-weight' : 300,
					'color' : 'green'
				});
				$('#message_timer').html('<p>Times successfully changed</p>');
			}
		});
	});

	$('#stocks').on('click', '.remove', function() {
		var postdata = $(this).attr('ticker');

		$('#confirmation').dialog({
			dialogClass : 'alert',
			title : 'Remove ' + postdata + '?',
			show : {
				effect : "shake",
				distance : 5,
				times : 2,
				duration : 300
			},
			draggable : false,
			resizable : false,
			modal : true,
			position : 'center',
			buttons : {
				"Remove" : function() {
					$.ajax({
						type : 'POST',
						url : 'ajax/removestock.php',
						data : {
							ticker : postdata
						},
						beforeSend : function() {
							// this is where we append a loading image
						},
						success : function(data) {
							$('#stockdata').html(data);
							alert("success");
						},
						error : function(xhr, ajaxOptions, thrownError) {
							alert(xhr.status + '|' + thrownError);
						}
					});
					$(this).dialog("close");
				},
				Cancel : function() {
					$(this).dialog("close");
				}
			},
		});
	});

	$('#updateTimerSelect').change(function() {
		if ($('#updateTimerSelect').val() == 'Days') {
			$('#updateDay').slideDown(200);
		} else
			$('#updateDay').slideUp(200);
	});
	$('#predictTimerSelect').change(function() {
		if ($('#predictTimerSelect').val() == 'Days') {
			$('#predictDay').slideDown(200);
		} else
			$('#predictDay').slideUp(200);
	});

});

