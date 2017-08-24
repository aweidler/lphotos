$(function(){
	var $photoWrapper = $('#photosWrapper');
	if(!$photoWrapper.length){
		return false;
	}
	debugger;
	var $photoCols = $('.photo-col');

	var pageNumber = 4;
	var canloadnext = true;
	$photoWrapper.hitsBottom(function(threshold){
		if(canloadnext){
			canloadnext = false;
			$.ajax({
				type: 'GET',
				url: './photos?by='+$photoWrapper.data('selectedsort')
				+'&seed='+$photoWrapper.data('seed')
				+'&page='+pageNumber,
				success: function(data){
					if(data && data.length){
						for(var i=0; i<data.length; i++){
							var $shortest = null;
							for(var j=0; j<$photoCols.length; j++){
								var $col = $($photoCols[j]);
								if(!$shortest || $col.height() < $shortest.height()){
									$shortest = $col;
								}
							}

							$shortest.append(data[i]);
						}
						
						pageNumber++;
						canloadnext = true;
						$(window).trigger('scroll');
					}
					else{
						canloadnext = false;
					}
				}

			});
		}
	}, 200);
	$(window).trigger('scroll');

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

	$photoWrapper.on('click', '.toggle-info', function(e){
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();

	});
	$photoWrapper.on('click', '.infowrapper', function(e){
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();
	});


	// viewer
	/*
	var pswpElement = document.querySelectorAll('.pswp')[0];

	// build items array
	var items = [
		{
			src: 'http://localhost/photos/public/img/large/H91A2553.JPG',
			w: 1280,
			h: 1920
		},
		{
			src: 'http://localhost/photos/public/img/large/H91A5359.JPG',
			w: 1920,
			h: 1280
		}
	];

	// define options (if needed)
	var options = {
		// optionName: 'option value'
		// for example:
		index: 0, // start at first slide
		bgOpacity: 1,
		barsSize: {top:0, bottom:0},
	};

	// Initializes and opens PhotoSwipe
	var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
	gallery.init();
	*/
});