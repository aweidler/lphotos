<?php
use Photos\Http\Controllers\UploadController; 

$photochuncks = [];
foreach($photos->chunk($cols) as $row){
	$chunkcounter = 0;
	foreach($row as $index=>$photo){
		if(!isset($photochuncks[$chunkcounter])){
			$photochuncks[$chunkcounter] = [];
		}

		$photochuncks[$chunkcounter++][] = $photo;
	}
}

$myurl = action('PhotosController@index');
?>
@extends('layouts.main')


@section('contents')

@include('components.viewer')

<section id="photosSorter" class="container">
	<label for="droper" style="color: #999; margin-right: 12px;">Sort By</label>
	<div id="droper" class="wrapper-dropdown"><span>{{ $sorts[$selectedSort] }}</span>
		<ul class="dropdown">
			@foreach($sorts as $sort=>$sortdisplay)
				<li><a href="{{ $myurl.'?by='.$sort }}">{{ $sortdisplay }}</a></li>
			@endforeach
		</ul>
	</div>
</section>
<section id = "photosWrapper" data-seed="{{ $seed }}" data-selectedsort="{{ $selectedSort }}" class="container fs-album-wrapper">
	@foreach($photochuncks as $row)
		<div class="photo-col col-sm-{{ intval(12 / $cols) }}">
			@foreach($row as $photo)
				@include('components.photo', ['photo' => $photo])
			@endforeach
		</div>
	@endforeach
</section>

@endsection