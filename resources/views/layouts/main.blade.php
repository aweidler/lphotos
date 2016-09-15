<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title', Config::get('mysite.default_title'))</title>

	<!-- Fonts -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css' id="fontawesome_cdn">
	<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

	<!-- Styles -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap_cdn">
	<link href="{{ URL::asset(bustAsset('css/app.css')) }}" rel="stylesheet">
</head>
<body id="app-layout">

	@include('layouts.header')




	@yield('contents', 'Uh Oh. What we\'ve got no content')







	<!-- JQuery JS CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<!-- jQuery JS local fallback -->
	<script>window.jQuery || document.write("<script src='{{ URL::asset(bustAsset('js/jquery.min.js')) }}'><\/script>")</script>

	<!-- Bootstrap JS CDN -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!-- Bootstrap JS local fallback -->
	<script>
	if(typeof($.fn.modal) === 'undefined') {
		document.write("<script src='{{ URL::asset(bustAsset('js/bootstrap.min.js')) }}'><\/script>");
	}
	</script>

	<!-- Bootstrap CSS local fallback -->
	<script>
	$(document).ready(function() {
		var bodyColor = $('body').css('color');
		if(bodyColor != 'rgb(51, 51, 51)') {
			$("head #bootstrap_cdn").after("<link href='{{ URL::asset(bustAsset('css/bootstrap.css')) }}' rel='stylesheet'>");
		}
	});
	</script>

	<!-- Font Awesome CSS local fallback -->
	<div id="FontAwesome_fallback" style="display:none"></div>    
	<script>(function($) {
		var $span = $('<span class="fa" style="display:none"></span>');
		$('#FontAwesome_fallback').append($span);
		var f = $span.css('fontFamily');
		if (f !== 'FontAwesome') {
		  $('head #fontawesome_cdn').after("<link href='{{ URL::asset(bustAsset('css/font-awesome.css')) }}' rel='stylesheet' type='text/css'>");
		}
		})(jQuery);
	</script>

	<!-- My JS -->
	<script src="{{ URL::asset(bustAsset('js/app.js')) }}"></script>
</body>
</html>
