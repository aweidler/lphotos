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

})(window.jQuery || window.Zepto);
