@section('header')

<?php
	$urls = [
		'albums' => action('AlbumController@index'),
		'photos' => action('PhotosController@index'),
		'about' => action('AboutController@index'),
		'contact' => action('ContactController@index'),
	];
?>

<header>
<nav class="navbar navbar-fixed-top">
	<div class="container nav-container">
		<div class="navbar-header">
		
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" 
			data-target="#aw_navbar" aria-expanded="false" aria-controls="aw_navbar">
				<span class="sr-only">Toggle navigation</span>
				<div class="toggle-bar">
					<span class="left"></span><span class="right"></span>
				</div>
				<div class="toggle-bar">
					<span class="left"></span><span class="right"></span>
				</div>
				<div class="toggle-bar">
					<span class="left"></span><span class="right"></span>
				</div>
			</button>

			{{-- Branding Image --}}
			<a class="navbar-brand" aria-label="{{ trans('aria.gohome') }}" href="{{ url('/') }}">
				<span role="link" class="icon-Weidler_Icon"></span>
			</a>
			{{-- The crazy logo --}}
			<div class="alogo-wrapper">
				<div class="alens-hood"></div>
				@for($i=1; $i<=$aperture_sides; $i++)
					<div class="aflag aflag-{{$i}}"></div>
				@endfor
			</div>
		</div>
		<div class="collapse navbar-collapse" id="aw_navbar">
			<ul class="navbar-nav nav navbar-links">
				<li class="{{ isset($active) && $active == 'photos' ? 'active' : '' }}" >
					<a href="{{ $urls['photos'] }}">{{ trans('aria.link.photos') }}
					@if(isset($active) && $active == 'photos')
						<span class="sr-only">({{ trans('aria.current') }})</span>
					@endif
					</a>
				</li>
				<li class="{{ isset($active) && $active == 'albums' ? 'active' : '' }}">
					<a href="{{ $urls['albums'] }}">{{ trans('aria.link.albums') }}
					@if(isset($active) && $active == 'albums')
						<span class="sr-only">({{ trans('aria.current') }})</span>
					@endif
					</a>
				</li>
				<li class="{{ isset($active) && $active == 'about' ? 'active' : '' }}">
					<a href="{{ $urls['about'] }}">{{ trans('aria.link.about') }}
					@if(isset($active) && $active == 'about')
						<span class="sr-only">({{ trans('aria.current') }})</span>
					@endif
					</a>
				</li>
				<li class="{{ isset($active) && $active == 'contact' ? 'active' : '' }}">
					<a href="{{ $urls['contact'] }}">{{ trans('aria.link.contact') }}
					@if(isset($active) && $active == 'contact')
						<span class="sr-only">({{ trans('aria.current') }})</span>
					@endif
					</a>
				</li>
			</ul>
		</div>
		<form id="searchWrapper" method="GET" action="{{ url('/photos') }}">
			<input name="q" type="text" placeholder="Search Photos" maxlength="80">
			<button type="submit" form="searchWrapper">
				<i class="fa fa-search" aria-hidden="true"></i>
			</button>
		</form>
	</div>
</nav>
</header>

@show