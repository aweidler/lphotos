<?php use Photos\Http\Controllers\UploadController; 

$info = $photo->getInfo();
$finfo = $info['FILE'];
$cinfo = $info['COMPUTED'];
$iinfo = isset($info['IFD0']) ? $info['IFD0'] : null;
$einfo = isset($info['EXIF']) ? $info['EXIF'] : null;
$nodata = '--';
$fnum = (isset($einfo['ApertureValue']) ? explode('/', $einfo['ApertureValue']) : null); 
if($fnum && count($fnum) > 1){
	$fnum = number_format($fnum[0] / $fnum[1], 1);
}
else{
	$fnum = null; 
}

if(isset($einfo['DateTimeOriginal'])){
	$date = date('m/d/Y  h:i a', strtotime($einfo['DateTimeOriginal']));// preg_replace('/:/', '-', $einfo['DateTimeOriginal'], 2);
}
else{
	$date = $nodata;
}

$size = $photo->imageSize(UploadController::DRIVER_MD);
$lsize = $photo->imageSize(UploadController::DRIVER_LG);
$path = $photo->getImage(UploadController::DRIVER_LG);
?>

<div class="imgwrapper" data-lwidth="{{ $lsize[0] }}" data-lheight="{{ $lsize[1] }}"
 data-width="{{ $size[0] }}" data-height="{{ $size[1] }}"
 data-path="{{ $path }}">
	<img class="thumb" src="{{ $photo->getImage(UploadController::DRIVER_MD) }}">
	<i class="loader fa fa-picture-o" title="Loading&hellip;"></i>
	<div class="infowrapper">
		<h3>{{ $photo->original_filename }}</h3>
		<div>
			<table>
				<tr><td>Date</td><td>{{ $date }}</td></tr>
				<tr><td>Size</td><td>{{ $cinfo['Width'] }} x {{ $cinfo['Height'] }} ({{ round($finfo['FileSize'] / 1024 / 1024, 1) }} MB)</td></tr>
				<tr><td>Resolution</td><td>{{ intval($iinfo['XResolution']) > 1 ? min(300, intval($iinfo['XResolution'])) : 72 }} ppi</td></tr>
				<tr><td>Shot With</td><td>{{ $iinfo['Model'] or $nodata }}</td></tr>
				<tr><td>Shot By</td><td>{{ $iinfo['Artist'] or $nodata }}</td></tr>
				<tr><td>Focal Length</td><td>{{ isset($einfo['FocalLength']) && floatval($einfo['FocalLength']) > 1 ? floatval($einfo['FocalLength']).' mm' : $nodata }}</td></tr>
				<tr><td>Shutter</td><td>{{ $einfo['ExposureTime'] or $nodata }}</td></tr>
				<tr><td>Aperture</td><td>{{ $fnum or $nodata }}</td></tr>
				<tr><td>ISO</td><td>{{ $einfo['ISOSpeedRatings'] or $nodata }}</td></tr>
				<tr><td>Tags</td><td>{{ str_replace(',', ', ', $photo->tags) }}</td></tr>
			</table>
		</div>
	</div>
	<div class ="imgoverlay noselect">
		<a class = "albumname" title="{{ trans('aria.photos.gotoalbum') }}" data-album="{{ $photo->album_id }}" href="#">
			<span><i class="fa fa-folder-open-o" aria-hidden="true"></i></span>&nbsp;{{ $photo->album->name }}
		</a>
		<div class="tool-buttons">
			<a class="toggle-info" title="{{ trans('aria.photos.info') }}">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
			</a>
			<a class="toggle-download" title="{{ trans('aria.photos.download') }}" href="{{ action('PhotosController@download', $photo->id) }}" download>
				<i class="fa fa-download" aria-hidden="true"></i>
			</a>
			<a class="toggle-zoom" title="{{ trans('aria.photos.zoomin') }}">
				<i class="fa fa-search-plus" aria-hidden="true"></i>
			</a>
		</div>
	</div>
</div>