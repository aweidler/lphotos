$(function(){

var $wrapper = $('#fsCanvasWrapper');
var $svg = $('#fsSVG');
var images = $svg.find('image').toArray();

var i = 0;

function update() {

	var limage = (i-1 < 0 ? images[images.length - 1] : images[i-1]);
	var mimage = images[i];
	var rimage = (i+1 < images.length ? images[i+1] : images[0]);

	updateImage(limage, -$(mimage).width());

	function updateImage(image, xOffset, yOffset){
		xOffset = xOffset || 0;
		yOffset = yOffset || 0;

		console.log(xOffset);

		var $img = $(image);
		var x = $img.attr('x') || 0;
		var y = $img.attr('y') || 0;

		$(image).attr('x', '-1080px');
		$(image).attr('y', parseFloat(y + yOffset) + 'px');
	}
}

// resize the canvas to fill browser window dynamically
window.addEventListener('resize', cresize, false);
function cresize() {
	$svg.width($wrapper.width());
	$svg.height($wrapper.height());
	update(); 
}
cresize();


});