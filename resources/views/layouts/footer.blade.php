@section('footer')

<footer>
	<div class="fluid-container footer">
		<div class="container">
			<div class="col-sm-6 left">
				<p><a href='http://austinweidler.com'>{{ trans('aria.footer.name') }}</a></p>
				<p>{{trans('aria.footer.address')}}</p>
				<p>{{trans('aria.footer.email')}}</p>
				<p>{{trans('aria.footer.phone')}}</p>
			</div>
			<div class="col-sm-6 right">
				<ul>
					<li><a href="">{{trans('aria.footer.terms')}}</a></li>
					<li>|</li>
					<li><a href="">{{trans('aria.footer.private')}}</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>

@show