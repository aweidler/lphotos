@extends('layouts.main')

@section('contents')

<div id="fsWrapper" class="container-fluid">
	<div class="content">
		<div class="fspolar">
			{{-- Insert images to process here --}}
			<img src="{{URL::asset('/image/1.jpg')}}">
			<img src="{{URL::asset('/image/2.jpg')}}">
			<img src="{{URL::asset('/image/3.jpg')}}">
			<img src="{{URL::asset('/image/4.jpg')}}">
			<img src="{{URL::asset('/image/5.jpg')}}">
		</div>
	</div>
</div>

<div class="container fsMiddle">
	<div class="row">
		<div class="col-sm-8">
			<h1>{{ trans('aria.home.welcome') }} <span class="fscanim">&#8220;Photos&#8221;</span></h1>
			<h4><span>{{ trans('aria.home.welcomeby') }} Austin Weidler</span></h4>
			<article>
				{{ trans('aria.home.welcometext') }}
			</article>
		</div>
		<div class="col-sm-4 myimage">
			<img src="{{URL::asset('/image/me.jpg')}}">
		</div>
	</div>
</div>

@endsection