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