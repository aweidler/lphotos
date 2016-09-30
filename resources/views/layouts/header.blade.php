@section('header')

<header>
<nav class="navbar navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
		
			<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" 
			data-target="#aw_navbar" aria-expanded="false" aria-controls="aw_navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			{{-- Branding Image --}}
			<a class="navbar-brand" aria-label="{{ trans('aria.gohome') }}" href="{{ url('/') }}">
				<span role="link" class="icon-Weidler_Icon"></span>
			</a>
			{{-- The crazy logo --}}
			<div class="alogo-wrapper">
				<div class="alens-hood"></div>
				@for($i=1; $i<=$asides; $i++)
					<div class="aflag aflag-{{$i}}"></div>
				@endfor
			</div>
		</div>
		<div class="collapse navbar-collapse" id="aw_navbar">
			<ul class="nav navbar-nav navbar-links">
				<li {{-- class="active" --}}><a href="#">Photos<span class="sr-only">(current)</span></a></li>
				<li><a href="#">Albums</a></li>
				<li><a href="#">About</a></li>
			</ul>
		</div>
	</div>
</nav>
</header>

@show