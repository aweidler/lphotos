$(function(){


	// old carousel stuff
	var $carousels = $('#fsCarouselMiddle, #fsCarouselLeft, #fsCarouselRight');

	// Carousel controls
	$carousels.carousel({
		pause: false,
		interval: false
	});

	$('#fsCarouselMiddle .carousel-indicators > li').click(function(e){
		$carousels.carousel('next');
	});

});