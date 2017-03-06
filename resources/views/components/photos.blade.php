<?php
use Photos\Http\Controllers\UploadController; 

$photochuncks = [];
$chunksize = intval(count($photos) / $cols);
for($i = 0; $i < $cols; $i++) {
	$photochuncks[] = $photos->slice($i * $chunksize, $chunksize);
}

?>
@extends('layouts.main')


@section('contents')

<div id = "photosWrapper" class="container fs-album-wrapper">
@foreach($photochuncks as $row)
<div class="photo-col" style="width: {{ 1 / $cols * 100 }}%;">
	@foreach($row as $photo)
		<div><img src="{{ $photo->getImage(UploadController::DRIVER_MD) }}"></div>
	@endforeach
</div>@endforeach
</div>

@endsection