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

<div>
	This is some cool content down here!
</div>

@endsection