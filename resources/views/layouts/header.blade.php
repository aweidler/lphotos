@section('header')

<header>
<nav class="navbar navbar-fixed-top">
	<div class="container">
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
				<li {{-- class="active" --}}><a href="#">{{ trans('aria.link.photos') }}
					<span class="sr-only">({{ trans('aria.current') }})</span></a>
				</li>
				<li><a href="#">{{ trans('aria.link.albums') }}</a></li>
				<li><a href="#">{{ trans('aria.link.about') }}</a></li>
				<li><a href="#">{{ trans('aria.link.contact') }}</a></li>
			</ul>
		</div>
	</div>
</nav>
</header>

@show