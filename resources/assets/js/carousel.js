var $test; // only for testing take out later
var $test2;

$(function(){
	var scale = 0.5;
	var animtime = 2;

	// cache our items
	var $content = $('#fsWrapper .content');
	var $polars = $content.find('> .fspolar img');
	$test = $($polars[0]);
	$test2 = $($polars[1]);

	$.fn.findOutPoint = function(){
		// find a point in this container that we can put our image transform to start
		var mywidth = $(this).outerWidth();
		var contentWidth = $content.width();
		var px = Math.floor(Math.random() * 100) + 1;
		if(Date.now() % 2 == 0){
			px *= -1;
		}
		var py = -205;
		return {x: px + '%', y: py + '%'};
	}

	$.fn.findInPoint = function(){
		var mywidth = $(this).outerWidth();
		var myheight = $(this).outerHeight();
		var contentWidth = $content.width();
		var contentHeight = $content.height();
		var px = Math.floor(Math.random() * 100) + 1;
		var py = Math.floor(Math.random() * 100) + 1;
		if(Date.now() % 2 == 0){
			px *= -1;
		}
		return {x: px + '%', y: py + '%'};
	}


	$.fn.nextTransform = function(){
		var point;
		var rotate = " rotate("+(Math.random() * 60 * (Date.now() % 2 ? 1 : -1))+"deg)";
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

	$.fn.fly = function() {
		return this.each(function() {
			var css = $(this).nextTransform();
			$(this).css({
				'transition': 'transform ' + animtime + 's',
				'-webkit-transform': css,
				'-moz-transform': css,
				'-ms-transform': css,
				'-o-transform': css,
				'transform': css});
		});
	};

});