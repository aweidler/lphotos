@extends('layouts.main')

@section('contents')

<div id="fsWrapper">
	<div id="fsCarousels" class="container">
		<div id="fsCarouselLeft" class="carousel slide container">
			{{-- Wrappers for slides --}}
			<div class="carousel-inner" role="listbox">
				<div class="item active">
					<img src="{{URL::asset('/image/1.jpg')}}" alt="Austin">
				</div>
				<div class="item">
					<img src="{{URL::asset('/image/2.jpg')}}" alt="Austin">
				</div>
				<div class="item">
					<img src="{{URL::asset('/image/3.jpg')}}" alt="Austin">
				</div>
			</div>

		</div>

		<div id="fsCarouselMiddle" class="carousel slide container">
			{{-- Indicators for carousel --}}
			<ol class="carousel-indicators">
				<li></li>
				<li class="active"></li>
				<li></li>
			</ol>

			{{-- Wrappers for slides --}}
			<div class="carousel-inner" role="listbox">
				<div class="item">
					<img src="{{URL::asset('/image/1.jpg')}}" alt="Austin">
				</div>
				<div class="item active">
					<img src="{{URL::asset('/image/2.jpg')}}" alt="Austin">
				</div>
				<div class="item">
					<img src="{{URL::asset('/image/3.jpg')}}" alt="Austin">
				</div>
			</div>

		</div>

		<div id="fsCarouselRight" class="carousel slide container">
			{{-- Wrappers for slides --}}
			<div class="carousel-inner" role="listbox">
				<div class="item">
					<img src="{{URL::asset('/image/1.jpg')}}" alt="Austin">
				</div>
				<div class="item">
					<img src="{{URL::asset('/image/2.jpg')}}" alt="Austin">
				</div>
				<div class="item active">
					<img src="{{URL::asset('/image/3.jpg')}}" alt="Austin">
				</div>
			</div>

		</div>

	</div>
</div>



{{-- This is a new html5 canvas method! --}}
<div id="fsCanvasWrapper" class="container-fluid">
	<svg id="fsSVG" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink= "http://www.w3.org/1999/xlink">
		<image xlink:href="{{URL::asset('/image/1.jpg')}}" x="0" y="0" width="100%" height="100%" />
		<image xlink:href="{{URL::asset('/image/2.jpg')}}" x="0" y="0" width="100%" height="100%" />
		<image xlink:href="{{URL::asset('/image/3.jpg')}}" x="0" y="0" width="100%" height="100%" />
	</svg>
</div>



@endsection