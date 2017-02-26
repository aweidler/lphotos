@extends('layouts.main')


@section('contents')

<div id="albumWrapper" class="container fs-album-wrapper">
	@include('components.albumrow', ['albums' => $albums])
</div>

@endsection