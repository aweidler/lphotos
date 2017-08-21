$(function(){
	$('#photosWrapper').hitsBottom(function(threshold){

	});

	$.fn.infoup = function(){
		var $wrapper = $(this);
		var $info = $wrapper.find('.infowrapper:first');
		var $img = $wrapper.find('img:first');
		if($wrapper.hasClass('infoup')){
			$info.fadeOut(100);
			$img.removeClass('blurimg');
			$wrapper.removeClass('infoup');
		}
		else{
			$info.fadeIn(100);
			$img.addClass('blurimg');
			$wrapper.addClass('infoup');
		}
	}

	$('#photosWrapper').on('click', '.toggle-info', function(e){
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();

	});
	$('#photosWrapper').on('click', '.infowrapper', function(e){
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();
	});

});