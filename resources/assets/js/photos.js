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
						$img.css({'margin-bottom':'0px'});
						console.log($img);
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