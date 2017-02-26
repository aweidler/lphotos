<?php use Photos\Http\Controllers\UploadController; ?>

@foreach($albums as $album)

	<?php $myfiles = $album->allFiles(); ?>

	@if($myfiles && count($myfiles))
	<article class="albumrow">
		<div class="lefter pag-col"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
		<div class="pag-scroller">
			@foreach($myfiles as $file)
				<div class="fileitem" style="width: {{ $file->widthForHeight(485) }}px;">
					<img src="" data-src="{{ $file->getImage() }}" data-src-retina="{{ $file->getImage(UploadController::DRIVER_LG) }}" >
					<i class="loader fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
				</div>
			@endforeach
		</div>
		<div class="righter pag-col"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
		<h2><a>{{ $album->name }}</a></h2>
	</article>
	@endif

@endforeach