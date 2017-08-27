$(function(){
	var $photoWrapper = $('#photosWrapper');
	if(!$photoWrapper.length){
		return false;
	}

	var $photoCols = $('.photo-col');

	var imgIndex = 0;
	var pageNumber = 1;
	var canloadnext = true;
	var $shortest = null;

	var images = [];
	var items = [];
	var _colImages = {};
	$photoCols.each(function(i){
		_colImages[i] = [];
	});

	$photoWrapper.hitsBottom(function(threshold){
		fetchImages();
	}, 400);
	var obj = fetchImages();
	if(obj){
		obj.done(function(){
			fetchImages();
		});
	}


	function fetchImages(){
		if(canloadnext){
			canloadnext = false;
			return $.ajax({
				type: 'GET',
				url: './photos?by='+$photoWrapper.data('selectedsort')
				+'&seed='+$photoWrapper.data('seed')
				+'&page='+pageNumber,
				success: function(data){
					if(placeImages(data)){
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
		return false;
	}

	function placeImages(data){
		if(data && data.length){
			for(var i=0; i<data.length; i++){
				for(var j=0; j<$photoCols.length; j++){
					var $col = $($photoCols[j]);
					if(!$shortest || parseInt($col.data('height')) < parseInt($shortest.data('height'))){
						$shortest = $col;
					}
				}

				var $img = $(data[i]);
				var colindex = $shortest.index();
				$img.data('index', imgIndex++);
				$img.data('col', colindex);
				_colImages[colindex].push($img);
				images.push($img);
				items.push({
					src: $img.data('path'),
					w: $img.data('lwidth'),
					h: $img.data('lheight'),
				});

				var height = parseInt($img.data('height'));
				$shortest.data('height', parseInt($shortest.data('height')) + height);
				if(isTiny){
					$($photoCols[0]).append($img);
				}
				else{
					$shortest.append($img);
				}
			}

			return true;
		}
		return false;
	}

	var breakpoint = 786 - 33;
	var isTiny = isBreakPoint(768);
	function moveImages(){
		if(isTiny){
			console.log('going to tiny');
			$($photoCols[0]).append(images);
		}
		else{
			console.log('going to large');
			$photoCols.each(function(i){
				$(this).append(_colImages[i]);
			});
		}
	}
	$(window).on('resize', function(e){
		console.log($(window).width())
		if($(window).width() < breakpoint && !isTiny){
			isTiny = true;
			moveImages();
		}
		else if($(window).width() >= breakpoint && isTiny){
			isTiny = false;
			moveImages();
		}
	});
	moveImages();

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

	$photoWrapper.on('click', '.thumb', function(e){
		e.stopPropagation();
		var $img = $(this).parents('.imgwrapper:first');
		// define options (if needed)
		var options = {
			index: $img.data('index'),
			bgOpacity: 1,
			barsSize: {top:0, bottom:0},
		};

		// Initializes and opens PhotoSwipe
		var pswp = new PhotoSwipe(document.getElementById('pswp'), PhotoSwipeUI_Default, items, options);
		pswp.init();
	});

	$photoWrapper.on('click', '.toggle-info', function(e){
		e.stopPropagation();
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();

	});
	$photoWrapper.on('click', '.infowrapper', function(e){
		e.stopPropagation();
		var $wrapper = $(this).parents('.imgwrapper:first');
		$wrapper.infoup();
	});
});