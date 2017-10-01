@extends('layouts.main')

@section('title') {{ 'Albums - '.Config::get('mysite.default_title') }} @endsection

@section('contents')

<div id="albumWrapper" class="container fs-album-wrapper">
	@include('components.albumrow', ['albums' => $albums])
</div>

@endsection