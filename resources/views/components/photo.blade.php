<?php use Photos\Http\Controllers\UploadController; 

$info = $photo->getInfo();
$finfo = $info['FILE'];
$cinfo = $info['COMPUTED'];
$iinfo = $info['IFD0'];
$einfo = $info['EXIF'];
$fnum = explode('/', $einfo['ApertureValue']);
if(count($fnum) > 1){
	$fnum = number_format($fnum[0] / $fnum[1], 1);
}
else{
	$fnum = null;
}

$date = date('m/d/Y  h:i a', strtotime($einfo['DateTimeOriginal']));// preg_replace('/:/', '-', $einfo['DateTimeOriginal'], 2);

$size = $photo->imageSize(UploadController::DRIVER_MD);
$lsize = $photo->imageSize(UploadController::DRIVER_LG);
$path = $photo->getImage(UploadController::DRIVER_LG);
?>

<div class="imgwrapper" data-lwidth="{{ $lsize[0] }}" data-lheight="{{ $lsize[1] }}"
 data-width="{{ $size[0] }}" data-height="{{ $size[1] }}"
 data-path="{{ $path }}">
	<img class="thumb" src="{{ $photo->getImage(UploadController::DRIVER_MD) }}">
	<div class="infowrapper">
		<h3>{{ $finfo['FileName'] }}</h3>
		<div>
			<table>
				<tr><td>Date</td><td>{{ $date }}</td></tr>
				<tr><td>Size</td><td>{{ $cinfo['Width'] }} x {{ $cinfo['Height'] }} ({{ round($finfo['FileSize'] / 1024 / 1024, 1) }} MB)</td></tr>
				<tr><td>Resolution</td><td>{{ intval($iinfo['XResolution']) }} ppi</td></tr>
				<tr><td>Shot With</td><td>{{ $iinfo['Model'] }}</td></tr>
				<tr><td>Shot By</td><td>{{ $iinfo['Artist'] }}</td></tr>
				<tr><td>Focal Length</td><td>{{ floatval($einfo['FocalLength']) }} mm</td></tr>
				<tr><td>Shutter</td><td>{{ $einfo['ExposureTime'] }}</td></tr>
				<tr><td>Aperture</td><td>{{ $fnum }}</td></tr>
				<tr><td>ISO</td><td>{{ $einfo['ISOSpeedRatings'] }}</td></tr>
				<tr><td>Tags</td><td>{{ $photo->tags }}</td></tr>
			</table>
		</div>
	</div>
	<div class ="imgoverlay noselect">
		<a class = "albumname" title="Go to Album" data-album="{{ $photo->album_id }}" href="#">
			<span><i class="fa fa-folder-open-o" aria-hidden="true"></i></span>&nbsp;{{ $photo->album->name }}
		</a>
		<a class="toggle-info" title="Info">
			<i class="fa fa-info-circle" aria-hidden="true"></i>
		</a>
		<a class="toggle-download" title="Download" href="{{ action('PhotosController@download', $photo->id) }}" download>
			<i class="fa fa-download" aria-hidden="true"></i>
		</a>
		<a class="toggle-zoom" title="Zoom In">
			<i class="fa fa-search-plus" aria-hidden="true"></i>
		</a>
	</div>
</div>