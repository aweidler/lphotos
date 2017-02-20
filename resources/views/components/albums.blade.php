@extends('layouts.main')


@section('contents')

<div class="container fs-album-wrapper">
	@foreach($albums as $album)
		@include('components.albumrow', ['album' => $album])
	@endforeach
</div>

@endsection