$(document).ready(function() {

	//dynamic animations start after 1.5 seconds
	setTimeout(function() {

		$('.help').each(function() {
			var l = $(this).css("left");
			var w = $(this).css("width");

			$(this).hover(function() {
				$(this).css("opacity", 1);
				$(this).addClass("hover");
				$(this).stop(true, false).animate({
					"width" : "600px",
					"left" : "0px",
				}, 300);
			}, function() {
				$(this).stop(true, false).animate({
					"width" : w,
					"left" : l,
				}, 300, function() {
					$(this).removeClass("hover");
				});
			});
		});
	}, 1500);
});
