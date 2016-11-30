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
		var ihandle;
		var curz = 0;
		(function sanimate(){
			$cur = false;
			do{
				if(pointer > $polars.length - 1){
					pointer = 0;
				}

				var cur = $polars.get(pointer);
				if(cur.complete){
					$cur = $(cur);
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