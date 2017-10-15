@extends('layouts.main')

@section('contents')

<div id="upload_wrapper" class="container">
	<h1>{{trans('aria.upload.addalbum')}}</h1>
	{{-- Enter foreach of albums to edit --}}
	
	@foreach($albums as $album)
		<form action="{{ url('aupload/'.$album->id) }}" class="fsadd form-inline" method="post">
			{!! csrf_field() !!}
			<div class="form-group curalbum">
				<input name="name" type="text" maxlength="100" autocomplete="off" value="{{ $album->name }}">
				<input name="location" type="text" placeholder="Location (City, Province)" maxlength="500" autocomplete="off" value="{{ $album->location }}">
				{{-- {!! Form::select('parent', array(null=>'No Parent') + $albumkeys, $album->parent, array('id'=>'parent', 'autocomplete'=>'off')) !!} --}}
				<button type="submit" name="save" class="btn btn-default btn-sm">{{trans('pagination.save')}}</button>
				<button type="submit" name="delete" class="btn btn-danger btn-sm">{{trans('pagination.delete')}}</button>
			</div>
		</form>
	@endforeach

	<form action="{{ url('aupload') }}" class="fsadd form-inline" method="post">
		{!! csrf_field() !!}
		<div class="form-group newalbum">
			<input type="text" placeholder="Name" name="newalbum" maxlength="100" autocomplete="off">
			<input type="text" placeholder="Location (City, Province)" name="newlocation" maxlength="500" autocomplete="off">
			{{-- {!! Form::select('newparent', array(null=>'No Parent') + $albumkeys, null, array('id'=>'newparent', 'autocomplete'=>'off')) !!} --}}
			<button type="submit" class="btn btn-success btn-sm">{{trans('pagination.add')}}</button>
		</div>
	</form>

	<h1>{{trans('aria.upload.uploadphoto')}}</h1>

	{!!
		Form::select('albumchoice', $albumkeys, ($selected ? $selected->id : null), array('id'=>'albumchoice', 'autocomplete'=>'off'))
	!!}

	@if($selected)
		<form action="{{ url('aupload/'.$selected->id) }}" class="fsadd form-inline" autocomplete="off" enctype="multipart/form-data" method="post">
			{!! csrf_field() !!}

			<div class="form-group newfiles">
				<label for="newfiles">{{ trans('pagination.addphotos') . ' ' .$selected->name . ' ('.count($files).')' }}</label>
				<input type="file" name="newfiles[]" id="newfiles" multiple accept="image/jpeg,.xmp,.CR2">
				<button type="submit" name="photoup" class="btn btn-success btn-sm">{{trans('pagination.upload')}}</button>
			</div>
		</form>
	@endif

	<div id="imagecells">
	@foreach($files as $file)
		<div class="imagecell ui-state-default" data-name="{{ $file->filename }}" data-file="{{ $file->id }}" data-token="{!! csrf_token() !!}">
			<img src="{{ url('img/small/'.$file->filename) }}" >
			<div class = 'labels'>
				@if(file_exists(storage_path('photos/small/'.$file->filename)))
					<label>sml</label>
				@endif
				@if(file_exists(storage_path('photos/medium/'.$file->filename)))
					<label>med</label>
				@endif
				@if(file_exists(storage_path('photos/large/'.$file->filename)))
					<label>lrg</label>
				@endif
				@if(file_exists(storage_path('photos/full/'.$file->filename)))
					<label>ful</label>
				@endif
				@if(file_exists(storage_path('photos/rawedits/'.$file->hash.'.CR2')))
					<label>CR2</label>
				@endif
				@if(file_exists(storage_path('photos/rawedits/'.$file->hash.'.xmp')))
					<label>xmp</label>
				@endif
			</div>

			<div class="tags">
				<input class='tags-text' type="text" placeholder="tags" maxlength="500" value="{{ $file->tags }}" />
			</div>

			<div class="options">
				<a class='save-tags' tabindex="-1">
					<i class="fa fa-floppy-o" aria-hidden="true"></i>
				</a>
				<a class="auto-tags" tabindex="-1">
					<i class="fa fa-tag" aria-hidden="true"></i>
				</a>
				<span>{{ $file->hash }}</span>
				<a href="{{ url('aupload/fdelete/'.$file->id) }}" tabindex="-1">
					<i class="fa fa-trash-o" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	@endforeach
	</div>

</div>
<script type="text/javascript" src="https://sdk.clarifai.com/js/clarifai-latest.js"></script>

@endsection