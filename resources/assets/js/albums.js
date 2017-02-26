$(function(){
	var END_SCROLL = 2500;
	var END_FADE = 500;
	var LAZY_THRESHOLD = 400;
	var canloadnext = true;

	var $albumWrapper = $('#albumWrapper');
	var footerheight = $('footer').height();

	function getDocHeight() {
		var D = document;
		return Math.max(
			D.body.scrollHeight, D.documentElement.scrollHeight,
			D.body.offsetHeight, D.documentElement.offsetHeight,
			D.body.clientHeight, D.documentElement.clientHeight
		) - footerheight;
	}

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
		},
		notcontainedenough: function(el){
			var $e = $(el),
			$w = $(arguments[1]),
			meLeft = $w.scrollLeft(),
			lefti = $e.position().left + meLeft,
			meLeftWidth = meLeft + $w.width();
			return !((lefti >= meLeft && lefti <= meLeftWidth) && (lefti + $e.width() >= meLeft && lefti + $e.width() <= meLeftWidth));
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

	var pageNumber = 2;
	$(window).scroll(function() {
		console.log(footerheight);
		if($(window).scrollTop() + $(window).height() >= getDocHeight()) {
			if(canloadnext){
				canloadnext = false;
				$.ajax({
					type: 'GET',
					url: './albums?page='+pageNumber,
					success: function(data){
						if(data){
							var $data = $(data);
							var $newattached = $albumWrapper.append($data);
							$data.find('.pag-scroller').bindScroller()
							
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
		}
	});

	$('.fs-album-wrapper').on('click', '.pag-col', function(e){
		var $album = $(this).parent();
		var $scroller = $album.find('.pag-scroller:first');
		var left = ($(this).hasClass('lefter'));

		$scroller.focusNext(left);
	});

	$.fn.bindScroller = function(){
		$(this).on('scroll', function(e){
			var $this = $(this); 
			$alls = $this.find('.fileitem');
			if($this.data('focused')){
				$alls.stop().fadeTo(0, 1.0);
				$this.data('focused', false);
			}
			var timer = $this.data('timer');
			clearTimeout(timer);
			$this.data('timer', setTimeout(function(){
				var $items = $this.find('.fileitem:notcontainedenough');
				if($items.length != $alls.length){
					$this.data('focused', true);
					$items.stop().fadeTo(END_FADE, 0.12);
				}
			}, END_SCROLL));
		});

		$(this).unveil(LAZY_THRESHOLD);
	}
	$('.pag-scroller').bindScroller();
	$(window).trigger('scroll');

});