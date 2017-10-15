$(function(){
	var END_SCROLL = 1500;
	var END_FADE = 500;
	var END_FADE_MOBILE = 150;
	var LAZY_THRESHOLD = 400;
	var canloadnext = true;

	var $albumWrapper = $('#albumWrapper');
	if(!$albumWrapper.length){
		return false; // only do the following if we are on the albums page
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
			$e.data('focusfactor', Math.min(lefti + $e.width(), meLeftWidth) - Math.max(lefti, meLeft));
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

	// TODO: replace with generic hitbottom filter
	var pageNumber = 2;
	$(window).scroll(function() {
		if(didHitBottom()) {
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
		var $this = $(this); 
		$(this).on('scroll', function(e){
			var timer = $this.data('timer');
			clearTimeout(timer);

			$alls = $this.find('.fileitem');
			if($this.data('focused')){
				$alls.stop().fadeTo(0, 1.0);
				$this.data('focused', false);
			}

			if(isBreakPoint(480)){
				$this.data('timer', setTimeout(function(){
					var $items = $this.find('.fileitem:containedview');
					if($items && $items.length){
						var $best = null;
						$items.each(function(i){
							if(!$best || ($(this).data('focusfactor') && $(this).data('focusfactor') > $best.data('focusfactor'))){
								$best = $(this);
							}
						});
						if($best){
							$best.focusImage($this, 200);
						}
					}
				}, END_FADE_MOBILE));
			}
			else{
				$this.data('timer', setTimeout(function(){
					var $items = $this.find('.fileitem:notcontainedenough');
					if($items.length != $alls.length){
						$this.data('focused', true);
						$items.stop().fadeTo(END_FADE, 0.12);
					}
				}, END_SCROLL));
			}
		});

		$(this).unveil(LAZY_THRESHOLD);
	}
	$('.pag-scroller').bindScroller();
	$(window).trigger('scroll');

});
var footerheight;
function getDocHeight() {
	if(!footerheight){
		footerheight = $('footer').height();
	}
	var D = document;
	return Math.max(
		D.body.scrollHeight, D.documentElement.scrollHeight,
		D.body.offsetHeight, D.documentElement.offsetHeight,
		D.body.clientHeight, D.documentElement.clientHeight
	) - footerheight;
}

function didHitBottom(threshold){
	threshold = threshold || 0;
	return (threshold + $(window).scrollTop() + $(window).height() >= getDocHeight());
}

var isBreakPoint = function (bp) {
	var bps = [320, 480, 768, 1024],
		w = $(window).width(),
		min, max;
	for(var i = 0, l = bps.length; i < l; i++){
		if(bps[i] === bp) {
		min = bps[i-1] || 0
		max = bps[i]
		break
		}
	}
	return w > min && w <= max
}

function setUrlParam(names, clears, hash){
	var path = window.location.href;

	path = path.split('#', 2);
	var hasher = "";
	if(path[1]){
		hasher = "#" + path[1];
	}
	path = path[0];

	var vars = path.split('?', 2);

	for(var name in names){
		val = encodeURIComponent(names[name]);

		if(vars[1] && vars[1].length > 1){
			if(path.search(name+'=') == -1){
				var morethanone = '&';
				if(vars[1].length < 2){
					morethanone = '';
				}

				path += morethanone+name+'='+val;
				vars = path.split('?', 2);
			}
			else{
				var alls = vars[1].split('&');
				for(var i=0; i<alls.length; i++){
					var mine = alls[i].trim();
					if(mine.indexOf(name+'=') == 0){
						alls[i] = name+'='+val;
						break;
					}
				}
				vars[1] = alls.join('&');
				path = vars[0]+'?'+vars[1];
			}
		}
		else if(path.indexOf('?') != -1){
			path += "&"+name+'='+val;
		}
		else{
			path += "?"+name+'='+val;
		}
	}

	if(clears){
		var alls = vars[1].split('&');
		var finals = [];
		for(var i=0; i<alls.length; i++){
			keep = true;
			for(var j=0; j<clears.length; j++){
				if(alls[i].indexOf(clears[j]) == 0){
					keep = false;
					break;
				}
			}
			if(keep){
				finals.push(alls[i]);
			}
		}
		path = vars[0]+'?'+finals.join('&');
	}

	return path + (hash === false ? '' : hasher);
}



function DropDown(el) {
	this.dd = el;
	this.initEvents();
}
DropDown.prototype = {
	initEvents : function() {
		var obj = this;

		obj.dd.on('click', function(event){
			$(this).toggleClass('active');
			event.stopPropagation();
		});	
	}
}

$(function() {

	var dd = new DropDown( $('.wrapper-dropdown') );

	$(document).click(function() {
		// all dropdowns
		$('.wrapper-dropdown').removeClass('active');
	});

});

		

$(function(){
	var scale = 0.75; // matches what is in css
	var animtime = 2; // time for our animation to complete
	var rotatestd = 30; // max degress for rotation
	var blurstd = 0; // controls amount of blur on each image
	var intervalSpeed = 3; // 1 second
	var canvasAmount = 3 // total amount of images on the canvas at a time

	var pointer = 0;
	var out = [];
	var units = '%';

	// cache our items
	var $content = $('#fsWrapper .content');
	var $polars = $content.find('> .fspolar img');

	$.fn.findOutPoint = function(){
		// find a point in this container that we can put our image transform to start
		var mywidth = $(this).outerWidth();
		var myheight = $(this).outerHeight();
		var px = Math.floor(Math.random() * 100) + 1;
		if(Date.now() % 2 == 0){
			px *= -1;
		}
		var py = -100 - (mywidth / myheight * rotatestd) - rotatestd;
		return {x: px + units, y: py + units};
	}

	$.fn.findInPoint = function(){
		var mywidth = $(this).outerWidth(); 
		var containpercent = (mywidth / $content.outerWidth());
		var px = Math.floor(Math.random() * (1 / containpercent) * 50 * scale) + 1;
		var py = Math.floor(Math.random() * Math.abs(100 - 1/scale * 100)) + 1;
		if(Date.now() % 2 == 0){
			px *= -1;
		}
		return {x: px + units, y: py + units};
	}


	$.fn.nextTransform = function(){
		var point;
		var rotate = " rotate("+(Math.random() * rotatestd * (Date.now() % 2 ? 1 : -1))+"deg)";
		if(!$(this).data('out')){
			// we need to move out
			point = $(this).findOutPoint();
			$(this).data('out', true);
		}
		else{
			// we need to move in
			point = $(this).findInPoint();
			rotate = "";
			$(this).data('out', false);
		}

		return "translate("+point.x+", "+point.y+")" + rotate;
	}

	$.fn.fly = function(instant) {
		return this.each(function() {
			var css = $(this).nextTransform(); 
			var anim = (instant) ? 'none' : 'transform ' + animtime + 's';
			$(this).css({
				'transition': anim,
				'-webkit-transform': css,
				'-moz-transform': css,
				'-ms-transform': css,
				'-o-transform': css,
				'transform': css,
				'display': 'block'});
		});
	};

	// reset all images
	$polars.fly(true);

	if($polars.length){

		// var $loadedPolars = $([]);
		// $polars.each(function(i){
		// 	$(this).one('load', function(){
		// 		$loadedPolars.add($(this));
		// 	});
		// });

		var ihandle;
		var curz = 0;
		(function sanimate(){
			var $cur = false;
			var total = 0;
			do{
				if(pointer > $polars.length - 1){
					pointer = 0;
				}

				var cur = $polars.get(pointer);
				if(cur.complete){
					$cur = $(cur);
				}
				
				if(total++ >= $polars.length){
					break;
				}
				
				pointer++;

			} while(!$cur);

			if($cur){
				// update our z-index
				$cur.css({'z-index': ++curz, 'filter':'none'});
				$cur.fly();

				if(blurstd){
					for(var i=0; i<out.length; i++){ 
						out[i].css('filter', 'blur('+(blurstd * (out.length - i))+'px)');
					}
				}
				if(out.length >= canvasAmount){
					var $last = out.shift();
					if($last){
						$last.fly();
					}
				}
				out.push($cur);
			}

			ihandle = setTimeout(sanimate, intervalSpeed * 1000);
		})();
	}
});
/**
 * Altercations for Photos Website by Austin Weidler
 *
 * jQuery Unveil
 * A very lightweight jQuery plugin to lazy load images
 * http://luis-almeida.github.com/unveil
 *
 * Licensed under the MIT license.
 * Copyright 2013 Luís Almeida
 * https://github.com/luis-almeida
 */

;(function($) {

  $.fn.unveil = function(threshold, callback) {

	var $w = $(this),
		th = threshold || 0,
		retina = window.devicePixelRatio > 1,
		attrib = retina? "data-src-retina" : "data-src",
		images = $(this).find('.fileitem'),
		loaded;

	images.one("unveil", function() {
		var me = this;
		var img = this.children[0];
		var source = img.getAttribute(attrib);
		source = source || img.getAttribute("data-src");
		if (source) {
			// console.log('loaded', img);
			img.setAttribute("src", source);
			img.onload = function(){
				me.removeChild(me.children[1]);
			};
			if (typeof callback === "function") callback.call(img);
		}
	});

	function unveil(){
		var inview = images.filter(function() {
			var $e = $(this);

			var wl = $w.scrollLeft(),
				wr = wl + $w.width(),
				el = $e.position().left + wl,
				er = el + $e.width();
			var willdo = er >= wl - th && el <= wr + th;
			return willdo;
		});

		loaded = inview.trigger("unveil");
		images = images.not(loaded);
	}

	$w.on("scroll.unveil resize.unveil lookup.unveil", unveil);
	unveil();

	return this;
  };

  $.fn.hitsBottom = function(callback, threshold){
  	var $this = $(this);
  	threshold = threshold || 0;
  	$(window).on('scroll resize', function(e){
  		if(didHitBottom(threshold)){
  			if(typeof callback === 'function'){
  				callback.call(window, threshold);
  			}
  			$this.trigger('bottomhit');
  		}
  	});
  };

})(window.jQuery || window.Zepto);

$(function(){
	
	var $navlinks = $('#aw_navbar .navbar-links');
	var $navlinksa = $('a', $navlinks);
	var $active = $navlinks.find('li.active');

	$navlinksa.hover(function(ein){
		$navlinksa.not(this).addClass('blur');
		if($active.length){
			$active.removeClass('active');
		}
	}, function(eout){
		$navlinksa.removeClass('blur');
		if($active.length){
			$active.addClass('active');
		}
	});

});
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
				url: setUrlParam({
					'by': $photoWrapper.data('selectedsort'),
					'seed': $photoWrapper.data('seed'),
					'page': pageNumber,
				}),
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
				var pswpitem = {
					src: $img.data('path'),
					w: $img.data('lwidth'),
					h: $img.data('lheight'),
				};

				items.push(pswpitem);

				var height = parseInt($img.data('height'));
				$shortest.data('height', parseInt($shortest.data('height')) + height);
				if(isTiny){
					$($photoCols[0]).append($img);
				}
				else{
					$shortest.append($img);
				}

				var $realimg = $img.find('img:first');
				if($realimg[0]){
					$realimg[0].onload = function(){
						var $loader = $(this).next();
						if($loader.length){
							$loader.remove();
						}
					};
				}

			}

			return true;
		}
		else if(!images.length){
			$photoWrapper.find('.noresults:first').show();
		}
		return false;
	}

	var $pswpElement = $('#pswp');
	var $pswpAlbumElement = $pswpElement.find('.viewer-album:first');
	var $pswpAlbumButton = $pswpElement.find('.pswp__button--album:first');
	var $pswpInfoElement = $pswpElement.find('.pswp__info:first');
	var pswp = null;
	function openViewer(index){
		// define options (if needed)
		var options = {
			index: index,
			loop: false,
			bgOpacity: 1,
			timeToIdle: 3000,
			history: true,
			barsSize: {top:0, bottom:0},
		};

		// Initializes and opens PhotoSwipe
		pswp = new PhotoSwipe(document.getElementById('pswp'), PhotoSwipeUI_Default, items, options);

		$nextBtn = $('.pswp__button--arrow--right', pswp.template);
		$prevBtn = $('.pswp__button--arrow--left', pswp.template);
		pswp.listen('beforeChange', function() {
			if(this.getCurrentIndex() == this.items.length - 1 && canloadnext){
				console.log('fetching page ' + pageNumber);
				$nextBtn.hide();
				fetchImages().done(function(){
					pswp.invalidateCurrItems();
					pswp.updateSize(true);
					if(canloadnext){
						$nextBtn.show();
					}
				});
			}
			else if(!canloadnext){
				$nextBtn.toggle(this.getCurrentIndex() < this.items.length - 1);
			}
			$prevBtn.toggle(this.getCurrentIndex() > 0);
		});

		pswp.listen('afterChange', function() {
			var index = pswp.getCurrentIndex();
			if(images[index]){
				var $album = images[index].find('.albumname:first');
				$pswpAlbumElement.text($album.text().trim());
				$pswpAlbumButton.off().on('click', function(e){
					e.stopPropagation();
					document.location = setUrlParam({'album': $album.data('album')}, false, false);
					return false;
				});

				var $info = images[index].find('.infowrapper:first').clone();
				$info.addClass('info-viewer');
				$info.css({display: 'block'});
				$pswpInfoElement.html($info);
			}
		});

		pswp.listen('destroy', function() {
			pswp = null;
		});

		pswp.init();

		if(images[index]){
			viewerInfo(images[index].hasClass('infoup') ? 'show' : 'hide', 0);
		}
	}

	var breakpoint = 786 - 33;
	var isTiny = isBreakPoint(768);
	function moveImages(){
		if(isTiny){
			$($photoCols[0]).append(images);
		}
		else{
			$photoCols.each(function(i){
				$(this).append(_colImages[i]);
			});
		}
	}
	$(window).on('resize', function(e){
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

	$pswpElement.on('click', '.pswp__button--download', function(e){
		if(pswp){
			var index = pswp.getCurrentIndex();
			if(images[index]){
				var ref = images[index].find('.toggle-download:first').attr('href');
				if(ref){
					document.location = ref;
					return;
				}
			}
		}
		alert('Could not download image');
	});

	$pswpElement.on('click', '.pswp__button--info', function(e){
		viewerInfo('toggle');
	});

	$pswpInfoElement.on('click', function(e){
		viewerInfo('hide');
	});

	function viewerInfo(type, time){
		if(typeof time === 'undefined'){
			time = 100;
		}
		var callback = function(){
			$pswpElement.toggleClass('freeze', $pswpInfoElement.is(':visible'));
			$pswpElement.find('.pswp__button--info:first').toggleClass('active', $pswpInfoElement.is(':visible'));
		};

		if(pswp){
			if(type == 'show'){
				$pswpInfoElement.fadeIn(time, 'swing', callback);
			}
			else if(type == 'hide'){
				$pswpInfoElement.fadeOut(time, 'swing', callback);
			}
			else{
				$pswpInfoElement.fadeToggle(time, 'swing', callback);
			}
		}
	}

	$photoWrapper.on('click', '.albumname', function(e){
		e.stopPropagation();
		var album = parseInt($(this).data('album'));
		document.location = setUrlParam({'album': album});
	});

	$photoWrapper.on('click', '.thumb, .toggle-zoom', function(e){
		e.stopPropagation();
		var $img = $(this).parents('.imgwrapper:first');
		openViewer($img.data('index'));
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

	$('#droper').on('click', '.sort-select', function(e){
		e.stopPropagation();
		var by = parseInt($(this).data('sort'));
		document.location = setUrlParam({'by': by});
	});
});
$(function(){

	var $uploadWrapper = $('#upload_wrapper');
	if(!$uploadWrapper.length){
		return false;
	}

	const threshold = 0.93;
	const app = new Clarifai.App({
		apiKey: 'ea746f008b50445aa47ffb018f3d5681',
	});


	$('#albumchoice').on('change', function(e){
		document.location = $('body').data('path') + '/aupload/' + $(this).val();
	});

	var last = null;
	$('#upload_wrapper').on('focus', '.tags-text', function(e){
		last = $(this).val();
	});

	$('#upload_wrapper').on('blur', '.tags-text', function(e){
		var val = $(this).val();
		if(val != last){
			$(this).save();
		}
	});

	$('#upload_wrapper').on('click', '.save-tags', function(e){
		$(this).save();
	});

	$('#upload_wrapper').on('click', '.auto-tags', function(e){
		$(this).autoTag();
	});

	function saveSort(){
		var $cells = $('#imagecells > div.imagecell');
		if($cells.length){
			var token = $($cells[0]).data('token');

			var items = {};
			$cells.each(function(i){
				items[parseInt($(this).data('file'))] = i + 1;
			});

			$.post($('body').data('path') + '/aupload/fsaveorder', {
				order: items,
				_token: token,
			}, function(result){
				console.log(result);
			});
		}
	}

	$uploadWrapper.find('#imagecells:first').sortable({
		stop: function(e, ui){
			saveSort();
		}
	});

	$.fn.autoTag = function(){
		var $self = $(this);
		var $imagecell = $self.parents('.imagecell:first');
		var image = $imagecell.data('name');
		var $tagField = $imagecell.find('.tags-text:first');
		var tags = $tagField.val();
		tags = tags ? tags.split(',') : [];
		var path = $('body').data('path')+"/img/large/"+image;
		//var path = "http://austinweidler.com/images/Handcraft1%20(render%20open).jpg";
		
		app.models.predict(Clarifai.GENERAL_MODEL, path).then(
			function(response) {
				for(var i=0; i<response.outputs.length; i++){
					var concepts = response.outputs[i].data.concepts;

					for(var j=0; j<concepts.length; j++){
						var tag = concepts[j].name;
						var value = concepts[j].value;

						if(value > threshold && tag.indexOf(',') == -1 && $.inArray(tag.trim(), tags) == -1){
							tags.push(tag);
						}
					}
				}

				var tagstring = tags.join(',');
				$tagField.val(tagstring);
				$self.save();
			},
			function(err) {
				console.log(err);
			}
		);
	}

	$.fn.save = function(){
		var $imagecell = $(this).parents('.imagecell:first');
		var tags = $imagecell.find('.tags-text:first').val();
		var file = $imagecell.data('file');
		var token = $imagecell.data('token');

		$.post($('body').data('path') + '/aupload/fsave/'+parseInt(file), {
			file: file,
			tags: tags,
			_token: token,
		}, function(result){
			console.log(result);
		});
	}
});