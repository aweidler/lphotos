<?php
use Photos\Http\Controllers\UploadController; 
$myurl = action('PhotosController@index');

?>
@extends('layouts.main')

@section('contents')

@include('components.viewer')

<section id="photosSorter" class="container">
	<div class="photos-header col-sm-7">
		<h1>{{ $listLabel or 'All Photos' }}</h1>
	</div>
	<div class="dropdown-header col-sm-5">
		<div class="dropdown-wrapper">
			<label for="droper" style="color: #999; margin-right: 12px;">Sort By</label>
			<div id="droper" class="wrapper-dropdown"><span>{{ $sorts[$selectedSort] }}</span>
				<ul class="dropdown">
					@foreach($sorts as $sort=>$sortdisplay)
						<li><a href="{{ $myurl.'?by='.$sort }}">{{ $sortdisplay }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</section>
<section id = "photosWrapper" data-seed="{{ $seed }}" data-selectedsort="{{ $selectedSort }}" class="container fs-album-wrapper">
	@for($q = 0; $q < $cols; $q++)
		<div class="photo-col col-sm-{{ intval(12 / $cols) }}" data-height="0">

		</div>
	@endfor
</section>

@endsection