<?php $myfiles = $album->allFiles(); ?>

@if($myfiles && count($myfiles))
<article class="albumrow">
	<div class="lefter pag-col"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
	<div class="pag-scroller">
		@foreach($myfiles as $file)
			<div class="fileitem">
				<img src="{{ $file->getImage() }}">
			</div>
		@endforeach
	</div>
	<div class="righter pag-col"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
	<h2>{{ $album->name }}</h2>
</article>
@endif