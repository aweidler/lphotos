@if(count($errors) > 0)
	<!-- Error list -->
	<div class="alert alert-danger">
		<strong>Sorry, an error occured</strong>

		<br><br>

		<ul>
			@foreach($errors->all() as $error)
				<li>{{ $error or 'General Error' }}</li>
			@endforeach
		</ul>
	</div>
@endif