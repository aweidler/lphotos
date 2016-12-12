@extends('layouts.main')

@section('contents')

<div id="upload_wrapper" class="container">
	<h1>{{trans('aria.upload.addalbum')}}</h1>
	{{-- Enter foreach of albums to edit --}}
	
	@foreach($albums as $album)
		<form action="{{ url('aupload/'.$album->id) }}" class="fsadd form-inline" method="post">
			{{ csrf_field() }}
			<div class="form-group curalbum">
				<input name="name" type="text" maxlength="100" autocomplete="off" value="{{ $album->name }}">
				<button type="submit" name="save" class="btn btn-default btn-sm">{{trans('pagination.save')}}</button>
				<button type="submit" name="delete" class="btn btn-danger btn-sm">{{trans('pagination.delete')}}</button>
			</div>
		</form>
	@endforeach

	<form action="{{ url('aupload') }}" class="fsadd form-inline" method="post">
		{{ csrf_field() }}
		<div class="form-group newalbum">
			<input type="text" name="newalbum" maxlength="100" autocomplete="off">
			<button type="submit" class="btn btn-success btn-sm">{{trans('pagination.add')}}</button>
		</div>
	</form>

	<h1>{{trans('aria.upload.uploadphoto')}}</h1>

	{!!
		Form::select('albumchoice', $albumkeys, $aid, array('id'=>'albumchoice', 'autocomplete'=>'off'))
	!!}

	<form action="{{ url('aupload/'.$album->id) }}" class="fsadd form-inline" enctype="multipart/form-data" method="post">
		{{ csrf_field() }}

		<div class="form-group newfiles">
			<label for="newfiles">Add Photos:</label>
			<input type="file" name="newfiles" id="newfiles" multiple accept="image/jpeg,.xmp,.CR2">
			<button type="submit" class="btn btn-success btn-sm">{{trans('pagination.upload')}}</button>
		</div>
	</form>

</div>

@endsection