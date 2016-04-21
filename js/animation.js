$(document).ready(function() {
var baseDelay = 0.1;

$('.animatedBounceIn').each(function(i) {
$(this).addClass('bounceIn');
$(this).css({animationDelay: (baseDelay*i).toString() + 's'});
});

$('.animatedBounceInUp').each(function(i) {
$(this).addClass('bounceInUp');
$(this).css({animationDelay: (baseDelay*i).toString() + 's'});
});

$('.animatedBounceInRight').each(function(i) {
$(this).addClass('bounceInRight');
$(this).css({animationDelay: (baseDelay*i).toString() + 's'});
});

$('.flipContainer').each(function(){
        $(this).not('a').click(function() {
        	$('.flipper', this).toggleClass('flipped');
        });
        $('.noClick', this).click(function(e) {
        	e.stopPropagation();
        });
    });
});