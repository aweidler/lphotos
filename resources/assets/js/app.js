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

		
