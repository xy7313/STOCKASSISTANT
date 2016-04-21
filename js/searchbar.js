$(document).ready(function() {

	var suggestions;

	$.ajax({
		url : 'ajax/searchSuggestions.php',
		dataType : "json",
		success : function(data) {
			$("#keyword").autocomplete({
				source : data,
				appendTo: "#searchbar",
				create: function(e, ui) {
            $('.ui-autocomplete').css({
            	'position': 'absolute',
            	'z-index': 999
            	});
        }
			});
		}
	});
	
	$('#keyword').one("click", function() {
		$(this).val('');
	});
	
	$('.ui-autocomplete li:odd').css('background-color', '#ececec');

	//create bars
	var width = $('#bars_container').width();
	var count = width / (17 + 7);

	for (var i = 0; i < count; i++) {
		$('#bars_container').append('<div class="bar"></div>')
	}

	var containerHeight = $('#bars_container').height();

	//start up animation
	$('.bar').each(function(i) {
		var margin = Math.random() * (containerHeight * 0.5) + (containerHeight * 0.5)
		$(this).attr({
			'margin' : margin,
		});
		$(this).css({
			"opacity" : Math.random() * 0.5 + 0.5,
			"height" : containerHeight * 1.2,
			"margin-top" : containerHeight * 1.2,
		});
		$(this).delay(i*30).animate({'margin-top' : margin}, 1000);

	});

	//dynamic animations start after 3 seconds
	setTimeout(function() {
		$(document).on("mousemove", function(event) {
			$('.bar').each(function() {

				var dx = Math.abs($(this).offset().left - event.pageX);
				var move;

				if (dx <= 10) {
					$(this).css({
						"border-top-width" : "2px"
					});
				} else {
					$(this).css({
						"border-top-width" : "0px"
					});
				}
				move = $(this).attr('margin') - ((containerHeight + 20) * .3) / (dx / ((containerHeight + 20) * .2) + 1);
				$(this).css("margin-top", move);
			});
		});
	}, 2000);
});

