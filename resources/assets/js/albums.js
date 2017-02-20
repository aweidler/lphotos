$(function(){

	$.extend($.expr[':'], { 
		inview: function(el){
			var $e = $(el),
			$w = $(arguments[1]),
			meLeft = $w.scrollLeft(),
			lefti = $e.position().left -1 + meLeft,
			meLeftWidth = meLeft + $w.width();
			return (lefti > meLeft && lefti < meLeftWidth);
		},
		outview: function (el){
			var $e = $(el),
			$w = $(arguments[1]),
			meLeft = $w.scrollLeft(),
			lefti = $e.position().left + meLeft + $e.width(),
			meLeftWidth = meLeft + $w.width();
			return (lefti > meLeft && lefti < meLeftWidth);
		},
		containedview: function(el){
			var $e = $(el),
			$w = $(arguments[1]),
			meLeft = $w.scrollLeft(),
			lefti = $e.position().left + meLeft,
			meLeftWidth = meLeft + $w.width();
			return (lefti >= meLeft && lefti <= meLeftWidth) || (lefti + $e.width() >= meLeft && lefti + $e.width() <= meLeftWidth);
		}
	});


	$.fn.focusNext = function(left){
		var animtime = 200;
		if($(this)){
			var $infocus = $(this).find('.fileitem:' + (left ? 'outview:first' : 'inview:last') );

			if($infocus && $infocus.length){
				$infocus.focusImage($(this), animtime);
				$(this).data('lastfocus', $infocus);
			}
			else{
				// find conainment
				$infocus = $(this).find('.fileitem:containedview:first');
				if($infocus && $infocus.length){
					$infocus = (left ? $infocus.prev() : $infocus.next());
					if($infocus && $infocus.length){
						$infocus.focusImage($(this), animtime);
						return;
					}
				}

				$(this).animate({
					scrollLeft: (left ? 0 : $(this).prop('scrollWidth') - $(this).width())
				}, animtime);
			}
		}
	}

	$.fn.focusImage = function($scroller, time){
		if($(this)){
			var scrollerleft = parseInt($scroller.scrollLeft());
			var gofor = ($(this).position().left + scrollerleft) - $scroller.width()/2 + $(this).width()/2;
			$scroller.animate({
				scrollLeft: gofor
			}, time);
		}
	}

	$('.fs-album-wrapper').on('click', '.pag-col', function(e){
		var $album = $(this).parent();
		var $scroller = $album.find('.pag-scroller:first');
		var left = ($(this).hasClass('lefter'));

		$scroller.focusNext(left);
	});
});