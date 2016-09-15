@section('header')

<header>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">

			{{-- Branding Image --}}
			<a class="navbar-brand" href="{{ url('/') }}">
				<span class="icon-Weidler_Icon"></span>

				{{-- The crazy logo --}}
				<div class="alogo-wrapper">
					<div class="alens-hood"></div>
					@for($i=1; $i<=$asides; $i++)
						<div class="aflag aflag-{{$i}}"></div>
					@endfor
				</div>
			</a>
		</div>

	</div>
</nav>
</header>

@show