@extends('layouts.main')

@section('contents')

<div id="fsWrapper" class="container-fluid">
	<div class="content">
		{{-- Insert images to process here --}}
		<div class="fspolar">
			<img src="{{URL::asset('/image/1.jpg')}}">
			<img src="{{URL::asset('/image/2.jpg')}}">
		</div>
{{-- 		<div class="fspolar">
			<img src="{{URL::asset('/image/2.jpg')}}">
		</div>
		<div class="fspolar">
			<img src="{{URL::asset('/image/3.jpg')}}">
		</div> --}}
	</div>
</div>

<div>
	This is some cool content down here!
</div>

@endsection