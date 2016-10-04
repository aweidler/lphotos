$(function(){
	
	var $navlinks = $('#aw_navbar .navbar-links');
	var $navlinksa = $('a', $navlinks);

	$navlinksa.hover(function(ein){
		$navlinksa.not(this).addClass('blur');
	}, function(eout){
		$navlinksa.removeClass('blur');
	});

});