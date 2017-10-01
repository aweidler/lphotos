@extends('layouts.main')

@section('title') {{ 'Contact - '.Config::get('mysite.default_title') }} @endsection

@section('contents')

<section id="contactWrapper" class="container fsMiddle">
	<div class="contact">
		<h2>{{ trans('aria.footer.name') }}</h2>
		<blockquote>
			<ul>
				<li>
					<dt><a target="_blank" href="http://www.google.com/maps/place/665+E+Millport+Rd,+Lititz,+PA+17543"><i class="fa fa-map-marker" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.address') }}</dd>
				</li>
				<li>
					<dt><a target="_blank" href="mailto:austin.weidler@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.email') }}</dd>
				</li>
				<li>
					<dt><a target="_blank" href="tel:1-717-824-9433"><i class="fa fa-phone" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.phone') }}</dd>
				</li>
				<li>
					<dt><a target="_blank" href="http://www.facebook.com/austin.weidler"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.facebook') }}</dd>
				</li>
				<li>
					<dt><a target="_blank" href="http://www.linkedin.com/in/aweidler/"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.linkedin') }}</dd>
				</li>
				<li>
					<dt><a target="_blank" href="http://austinweidler.com"><i class="fa fa-link" aria-hidden="true"></i></a></dt>
					<dd>{{ trans('aria.footer.webpage') }}</dd>
				</li>
			</ul>
		</blockquote>
	</div>
</section>

@endsection