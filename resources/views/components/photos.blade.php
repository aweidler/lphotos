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

?>
@extends('layouts.main')


@section('contents')

<section id = "photosWrapper" class="container fs-album-wrapper">
	@foreach($photochuncks as $row)
		<div class="photo-col col-sm-{{ intval(12 / $cols) }}">
			@foreach($row as $photo)
				<div class="imgwrapper">
					<img src="{{ $photo->getImage(UploadController::DRIVER_MD) }}">
					<div class ="imgoverlay">
						<a href="#">{{ $photo->albumo->name }}</a>
						<a href="#"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
						<a href="#"><i class="fa fa-download" aria-hidden="true"></i></a>
					</div>
				</div>
			@endforeach
		</div>
	@endforeach
</section>

@endsection