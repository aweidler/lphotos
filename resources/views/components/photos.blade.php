<?php
use Photos\Http\Controllers\UploadController; 
$myurl = action('PhotosController@index');

?>
@extends('layouts.main')

@section('contents')

@include('components.viewer')

<section id="photosSorter" class="container">
	<div class="photos-header col-sm-7">
		<h1>
			<a title="View All Photos" href="{{ $myurl.($selectedSort ? '?by='.$selectedSort : '') }}">
				{{ $listLabel or 'All Photos' }}
			</a>
		</h1>
	</div>
	<div class="dropdown-header col-sm-5">
		<div class="dropdown-wrapper">
			<label for="droper" style="color: #999; margin-right: 12px;">Sort By</label>
			<div id="droper" class="wrapper-dropdown"><span>{{ $sorts[$selectedSort] }}</span>
				<ul class="dropdown">
					@foreach($sorts as $sort=>$sortdisplay)
						<li><a class="sort-select" data-sort="{{ $sort }}">{{ $sortdisplay }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</section>
<section id = "photosWrapper" data-seed="{{ $seed }}" data-selectedsort="{{ $selectedSort }}" class="container fs-album-wrapper">
	<div class="noresults">{{ trans('aria.photos.noresults') }}</div>
	<div class="img-cols">
	@for($q = 0; $q < $cols; $q++)
		<div class="photo-col col-sm-{{ intval(12 / $cols) }}" data-height="0">

		</div>
	@endfor
	</div>
</section>

@endsection