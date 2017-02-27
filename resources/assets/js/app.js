
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

function didHitBottom(){
	return ($(window).scrollTop() + $(window).height() >= getDocHeight());
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