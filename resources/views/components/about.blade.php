@extends('layouts.main')


@section('contents')

<section id="aboutWrapper" class="container fsMiddle">
	<div class="about-icons">
		<i class="fa fa-male" aria-hidden="true"></i>
		<i>+</i>
		<i class="fa fa-camera" aria-hidden="true"></i>
		<i>=</i>
		<i class="fa fa-picture-o fscanim" aria-hidden="true"></i>
	</div>

	<article class="abouter row">
		<div class="col col-sm-6 text">
			<h2>{{ trans('aria.about.photography') }}</h2>
			<blockquote>
				{!! trans('aria.about.ptext') !!}
			</blockquote>
		</div>
		<div class="col col-sm-6 hidden-xs">
			<img src="{{URL::asset('/image/austin_weidler.jpg')}}">
		</div>
	</article>

	<article class="abouter row">
		<div class="col col-sm-6 hidden-xs">
			<div>
				<i class="fa fa-file-code-o" aria-hidden="true"></i>
				<i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
				<i class="fa fa-file-image-o" aria-hidden="true"></i>
			</div>
		</div>
		<div class="col col-sm-6 text">
			<h2>{{ trans('aria.about.website') }}</h2>
			<blockquote>
				{!! trans('aria.about.wtext') !!}
			</blockquote>
		</div>
	</article>
</section>

@endsection